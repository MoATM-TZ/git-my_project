<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSetting;
use App\Models\CourseClass;
use App\Models\Department;
use App\Models\Program;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function index(Request $request): View
    {
        $courseSearch = trim((string) $request->query('course_search', ''));

        return view('admin.academic.index', [
            'departments' => Department::query()->withCount('programs')->orderBy('name')->get(),
            'courses' => Program::query()
                ->with('department')
                ->withCount('resources')
                ->when($courseSearch !== '', function ($query) use ($courseSearch): void {
                    $query->where(function ($query) use ($courseSearch): void {
                        $query->where('name', 'like', "%{$courseSearch}%")
                            ->orWhere('level', 'like', "%{$courseSearch}%")
                            ->orWhereHas('department', fn ($department) => $department->where('name', 'like', "%{$courseSearch}%"));
                    });
                })
                ->orderBy('name')
                ->paginate(15, ['*'], 'courses_page')
                ->withQueryString(),
            'settings' => AcademicSetting::query()->latest()->get(),
            'classes' => CourseClass::query()->with(['lecturer', 'program'])->withCount('resources')->latest()->paginate(15, ['*'], 'classes_page'),
            'lecturers' => User::query()->where('role', 'lecturer')->orderBy('name')->get(),
            'programs' => Program::query()->with('department')->orderBy('name')->get(),
        ]);
    }

    public function storeDepartment(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', 'unique:departments,name'],
            'code' => ['required', 'string', 'max:30', 'unique:departments,code'],
        ]);

        Department::create($data);

        return back()->with('status', 'Department added.');
    }

    public function updateDepartment(Request $request, Department $department): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120', Rule::unique('departments', 'name')->ignore($department->id)],
            'code' => ['required', 'string', 'max:30', Rule::unique('departments', 'code')->ignore($department->id)],
        ]);

        DB::transaction(function () use ($department, $data): void {
            $oldName = $department->name;
            $department->update($data);

            if ($oldName !== $data['name']) {
                CourseClass::query()->where('department', $oldName)->update(['department' => $data['name']]);
            }
        });

        return back()->with('status', 'Department updated.');
    }

    public function destroyDepartment(Department $department): RedirectResponse
    {
        $hasPrograms = $department->programs()->exists();
        $hasCourseClasses = CourseClass::query()->where('department', $department->name)->exists();

        abort_if($hasPrograms || $hasCourseClasses, 422, 'This department still has courses or course classes attached. Move or remove them before deleting the department.');

        $department->delete();

        return back()->with('status', 'Department deleted.');
    }

    public function storeCourse(Request $request): RedirectResponse
    {
        $data = $request->validate($this->courseRules());
        Program::create($data);

        return back()->with('status', 'Course added.');
    }

    public function updateCourse(Request $request, Program $program): RedirectResponse
    {
        $data = $request->validate($this->courseRules($program));
        $program->update($data);

        return back()->with('status', 'Course updated.');
    }

    public function destroyCourse(Program $program): RedirectResponse
    {
        abort_if($program->courseClasses()->exists(), 422, 'This course is attached to one or more course classes and cannot be deleted.');

        $program->delete();

        return back()->with('status', 'Course deleted.');
    }

    /** Legacy endpoint retained for existing bookmarks/forms. */
    public function storeProgram(Request $request): RedirectResponse
    {
        return $this->storeCourse($request);
    }

    public function storeSetting(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'academic_year' => ['required', 'regex:/^\d{4}\/\d{4}$/'],
            'semester' => ['required', 'max:30'],
            'is_current' => ['nullable', 'boolean'],
        ]);

        if ($request->boolean('is_current')) {
            AcademicSetting::query()->update(['is_current' => false]);
        }

        AcademicSetting::create([...$data, 'is_current' => $request->boolean('is_current')]);

        return back()->with('status', 'Academic period saved.');
    }

    public function updateClass(Request $request, CourseClass $courseClass): RedirectResponse
    {
        $data = $request->validate([
            'lecturer_id' => ['required', 'exists:users,id'],
            'program_id' => ['nullable', 'exists:programs,id'],
            'academic_year' => ['required', 'max:20'],
            'semester' => ['required', 'max:30'],
        ]);

        $lecturer = User::findOrFail($data['lecturer_id']);
        abort_unless($lecturer->role === 'lecturer', 422);
        $courseClass->update($data);

        return back()->with('status', 'Class reassigned.');
    }

    public function destroyClass(CourseClass $courseClass): RedirectResponse
    {
        $courseClass->delete();

        return back()->with('status', 'Class deleted.');
    }

    private function courseRules(?Program $program = null): array
    {
        $uniqueCourse = Rule::unique('programs', 'name')
            ->where(fn ($query) => $query->where('department_id', request('department_id'))->where('level', request('level')));

        if ($program) {
            $uniqueCourse->ignore($program->id);
        }

        return [
            'department_id' => ['required', 'exists:departments,id'],
            'name' => ['required', 'string', 'max:160', $uniqueCourse],
            'level' => ['required', Rule::in(['Certificate', 'Diploma', 'Degree', 'Masters', 'PhD'])],
        ];
    }
}
