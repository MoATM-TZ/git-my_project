@extends('layouts.app')
@section('title', 'Academic Setup')
@section('content')
<div style="max-width: 1280px; margin: 0 auto; padding: 24px; font-family: system-ui, -apple-system, sans-serif; color: #0f172a;">
    <header style="margin-bottom: 24px;">
        <h1 style="font-size: 26px; font-weight: 700; margin: 0 0 5px;">Academic Setup</h1>
        <p style="font-size: 14px; color: #64748b; margin: 0;">Manage departments, courses, and their connection to uploaded learning resources.</p>
    </header>

    <section aria-label="Create department and course" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <form method="POST" action="{{ route('admin.academic.departments.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); align-items: end; gap: 10px; padding: 18px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 1px 3px rgba(15,23,42,.04);">
            @csrf
            <h2 style="grid-column: 1 / -1; margin: 0; font-size: 16px;">Add Department</h2>
            <label style="font-size: 12px; font-weight: 600; color: #64748b;">Department name<input name="name" value="{{ old('name') }}" required maxlength="120" placeholder="e.g. Computing" style="display: block; width: 100%; box-sizing: border-box; margin-top: 5px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; font-size: 14px;"></label>
            <label style="font-size: 12px; font-weight: 600; color: #64748b;">Department code<input name="code" value="{{ old('code') }}" required maxlength="30" placeholder="e.g. COMP" style="display: block; width: 100%; box-sizing: border-box; margin-top: 5px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; font-size: 14px;"></label>
            <button type="submit" style="padding: 10px 14px; border: 0; border-radius: 8px; background: #4f46e5; color: #fff; font-size: 14px; font-weight: 600; cursor: pointer;">Add Department</button>
        </form>

        <form method="POST" action="{{ route('admin.academic.courses.store') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); align-items: end; gap: 10px; padding: 18px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 1px 3px rgba(15,23,42,.04);">
            @csrf
            <h2 style="grid-column: 1 / -1; margin: 0; font-size: 16px;">Add Course</h2>
            <label style="font-size: 12px; font-weight: 600; color: #64748b;">Course name<input name="name" value="{{ old('name') }}" required maxlength="160" placeholder="e.g. Software Engineering" style="display: block; width: 100%; box-sizing: border-box; margin-top: 5px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; font: inherit; font-size: 14px;"></label>
            <label style="font-size: 12px; font-weight: 600; color: #64748b;">Department<select name="department_id" required style="display: block; width: 100%; box-sizing: border-box; margin-top: 5px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font: inherit; font-size: 14px;"><option value="">Choose a department</option>@foreach($departments as $department)<option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>@endforeach</select></label>
            <label style="font-size: 12px; font-weight: 600; color: #64748b;">Level<select name="level" required style="display: block; width: 100%; box-sizing: border-box; margin-top: 5px; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font: inherit; font-size: 14px;">@foreach(['Certificate', 'Diploma', 'Degree', 'Masters', 'PhD'] as $level)<option value="{{ $level }}" @selected(old('level') === $level)>{{ $level }}</option>@endforeach</select></label>
            <button type="submit" style="padding: 10px 14px; border: 0; border-radius: 8px; background: #4f46e5; color: #fff; font-size: 14px; font-weight: 600; cursor: pointer;">Add Course</button>
        </form>
    </section>

    @if($errors->any())
        <div role="alert" style="margin-bottom: 18px; padding: 12px 15px; border: 1px solid #fecaca; border-radius: 10px; background: #fef2f2; color: #b91c1c; font-size: 14px;">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
    @endif

    <section style="margin-bottom: 24px; padding: 18px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px;">
            <div><h2 style="margin: 0; font-size: 18px;">Departments</h2><p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Edit department details or review its course count.</p></div>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; min-width: 700px; border-collapse: collapse; text-align: left;">
                <thead><tr style="color: #64748b; background: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: .04em;"><th colspan="3" style="padding: 12px;">Department</th><th style="padding: 12px;">Courses</th><th style="padding: 12px;">Actions</th></tr></thead>
                <tbody>
                    @forelse($departments as $department)
                        <tr style="border-top: 1px solid #f1f5f9;">
                            <td colspan="3" style="padding: 12px;">
                                <form id="department-{{ $department->id }}" method="POST" action="{{ route('admin.academic.departments.update', $department) }}" style="display: grid; grid-template-columns: minmax(180px, 1fr) minmax(100px, 160px) 90px; gap: 8px; align-items: center;">
                                    @csrf @method('PATCH')
                                    <input name="name" value="{{ $department->name }}" required maxlength="120" aria-label="Department name" style="width: 100%; box-sizing: border-box; padding: 8px 9px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px;">
                                    <input name="code" value="{{ $department->code }}" required maxlength="30" aria-label="Department code" style="width: 100%; box-sizing: border-box; padding: 8px 9px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px;">
                                    <button type="submit" style="padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; color: #334155; font-size: 12px; font-weight: 600; cursor: pointer;">Save</button>
                                </form>
                            </td>
                            <td style="padding: 12px;"><span style="padding: 5px 9px; border-radius: 999px; background: #eef2ff; color: #4338ca; font-size: 12px; font-weight: 700;">{{ $department->programs_count }}</span></td>
                            <td style="padding: 12px;">
                                <form method="POST" action="{{ route('admin.academic.departments.destroy', $department) }}" onsubmit="return confirm('Delete this department? It must not have courses or course classes.')" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 8px 10px; border: 1px solid #fecaca; border-radius: 7px; background: #fff; color: #b91c1c; font-size: 12px; font-weight: 600; cursor: pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding: 26px 12px; text-align: center; color: #64748b; font-size: 14px;">No departments have been added.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <section style="margin-bottom: 24px; padding: 18px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;">
        <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px;">
            <div><h2 style="margin: 0; font-size: 18px;">Courses</h2><p style="margin: 4px 0 0; color: #64748b; font-size: 13px;">Courses are grouped under a department. Resource totals include resources linked to their course classes.</p></div>
            <form method="GET" action="{{ route('admin.academic.index') }}" style="display: flex; gap: 7px;">
                <input name="course_search" value="{{ request('course_search') }}" placeholder="Search courses or departments" aria-label="Search courses" style="width: min(280px, 55vw); padding: 9px 10px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 13px;">
                <button type="submit" style="padding: 9px 12px; border: 0; border-radius: 8px; background: #4f46e5; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">Search</button>
            </form>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; min-width: 850px; border-collapse: collapse; text-align: left;">
                <thead><tr style="color: #64748b; background: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: .04em;"><th colspan="3" style="padding: 12px;">Course details</th><th style="padding: 12px;">Uploaded resources</th><th style="padding: 12px;">Actions</th></tr></thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr style="border-top: 1px solid #f1f5f9;">
                            <td colspan="3" style="padding: 12px;">
                                <form id="course-{{ $course->id }}" method="POST" action="{{ route('admin.academic.courses.update', $course) }}" style="display: grid; grid-template-columns: minmax(180px, 1.2fr) minmax(150px, 1fr) minmax(110px, .7fr) 80px; gap: 8px; align-items: center;">
                                    @csrf @method('PATCH')
                                    <input name="name" value="{{ $course->name }}" required maxlength="160" aria-label="Course name" style="width: 100%; box-sizing: border-box; padding: 8px 9px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px;">
                                    <select name="department_id" required aria-label="Course department" style="width: 100%; box-sizing: border-box; padding: 8px 9px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; font-size: 13px;">@foreach($departments as $department)<option value="{{ $department->id }}" @selected($course->department_id === $department->id)>{{ $department->name }}</option>@endforeach</select>
                                    <select name="level" required aria-label="Course level" style="width: 100%; box-sizing: border-box; padding: 8px 9px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; font-size: 13px;">@foreach(['Certificate', 'Diploma', 'Degree', 'Masters', 'PhD'] as $level)<option value="{{ $level }}" @selected($course->level === $level)>{{ $level }}</option>@endforeach</select>
                                    <button type="submit" style="padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; color: #334155; font-size: 12px; font-weight: 600; cursor: pointer;">Save</button>
                                </form>
                            </td>
                            <td style="padding: 12px; white-space: nowrap; color: #475569; font-size: 13px;">{{ $course->resources_count }}</td>
                            <td style="padding: 12px;">
                                <form method="POST" action="{{ route('admin.academic.courses.destroy', $course) }}" onsubmit="return confirm('Delete this course? Courses linked to active classes cannot be removed.')" style="margin: 0;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 8px 10px; border: 1px solid #fecaca; border-radius: 7px; background: #fff; color: #b91c1c; font-size: 12px; font-weight: 600; cursor: pointer;">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding: 26px 12px; text-align: center; color: #64748b; font-size: 14px;">No courses match this search.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 14px;">{{ $courses->links() }}</div>
    </section>

    <details style="padding: 18px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;">
        <summary style="cursor: pointer; color: #334155; font-size: 15px; font-weight: 650;">Academic periods and course classes</summary>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 18px; margin-top: 18px;">
            <form method="POST" action="{{ route('admin.academic.settings.store') }}" style="display: grid; gap: 10px; align-content: start; padding: 16px; border: 1px solid #e2e8f0; border-radius: 12px;">
                @csrf
                <h3 style="margin: 0; font-size: 15px;">Add academic period</h3>
                <input name="academic_year" required placeholder="2026/2027" style="padding: 9px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px;">
                <input name="semester" required placeholder="Semester 1" style="padding: 9px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px;">
                <label style="font-size: 13px; color: #475569;"><input type="checkbox" name="is_current" value="1"> Set as current period</label>
                <button type="submit" style="justify-self: start; padding: 9px 12px; border: 0; border-radius: 8px; background: #4f46e5; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;">Save period</button>
                @if($settings->isNotEmpty())<p style="margin: 0; color: #64748b; font-size: 12px;">Current: {{ $settings->firstWhere('is_current', true)?->academic_year ?? 'Not set' }} {{ $settings->firstWhere('is_current', true)?->semester }}</p>@endif
            </form>

            <div style="display: grid; gap: 10px;">
                <h3 style="margin: 0; font-size: 15px;">Assign class ownership and course</h3>
                @forelse($classes as $class)
                    <form method="POST" action="{{ route('admin.classes.update', $class) }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 8px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 10px;">
                        @csrf @method('PATCH')
                        <div style="grid-column: 1 / -1; font-size: 13px; font-weight: 600;">{{ $class->course_code }} — {{ $class->course_title }} <span style="font-weight: 400; color: #64748b;">· {{ $class->resources_count }} resources</span></div>
                        <select name="lecturer_id" required aria-label="Lecturer" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; font-size: 12px;">@foreach($lecturers as $lecturer)<option value="{{ $lecturer->id }}" @selected($class->lecturer_id === $lecturer->id)>{{ $lecturer->name }}</option>@endforeach</select>
                        <select name="program_id" aria-label="Linked course" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; font-size: 12px;"><option value="">No linked course</option>@foreach($programs as $program)<option value="{{ $program->id }}" @selected($class->program_id === $program->id)>{{ $program->name }} · {{ $program->department->name }}</option>@endforeach</select>
                        <input name="academic_year" value="{{ $class->academic_year }}" required aria-label="Academic year" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 12px;">
                        <input name="semester" value="{{ $class->semester }}" required aria-label="Semester" style="padding: 8px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 12px;">
                        <button type="submit" style="padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; color: #334155; font-size: 12px; font-weight: 600; cursor: pointer;">Save assignment</button>
                    </form>
                @empty
                    <p style="margin: 0; color: #64748b; font-size: 13px;">No course classes to assign.</p>
                @endforelse
                {{ $classes->links() }}
            </div>
        </div>
    </details>
</div>
@endsection
