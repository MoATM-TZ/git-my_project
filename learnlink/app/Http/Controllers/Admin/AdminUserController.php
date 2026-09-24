<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'role' => ['nullable', Rule::in(['student', 'lecturer', 'admin'])],
            'status' => ['nullable', Rule::in(['approved', 'pending', 'rejected'])],
        ]);

        $users = User::query()
            ->with(['studentProfile', 'lecturerProfile'])
            ->when(! empty($filters['search']), function ($query) use ($filters): void {
                $search = trim($filters['search']);
                $query->where(function ($query) use ($search): void {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(! empty($filters['role']), fn ($query) => $query->where('role', $filters['role']))
            ->when(! empty($filters['status']), fn ($query) => $query->where('approval_status', $filters['status']))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'totalUsers' => User::count(),
        ]);
    }

    public function show(User $user): View
    {
        return view('admin.users.show', [
            'user' => $user->load(['studentProfile', 'lecturerProfile', 'resources']),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'role' => ['required', Rule::in(['student', 'lecturer', 'admin'])],
            'is_active' => ['required', 'boolean'],
        ]);

        $this->preventSelfDemotion($request, $user, $data['role']);
        $user->update($data);

        return back()->with('status', 'User updated.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'in:student,lecturer,admin'],
        ]);

        $this->preventSelfDemotion($request, $user, $validated['role']);
        $user->update(['role' => $validated['role']]);

        return back()->with('status', "{$user->name}'s role was updated.");
    }

    public function toggleStatus(User $user): RedirectResponse
    {
        $user->is_active = ! $user->is_active;
        $user->save();

        return back()->with('status', $user->is_active
            ? "{$user->name}'s account was activated."
            : "{$user->name}'s account was deactivated.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        abort_if($user->is($request->user()), 422, 'You cannot delete your own administrator account.');

        $avatarPath = $user->avatar_path;
        $user->delete();
        if ($avatarPath) {
            Storage::disk('public')->delete($avatarPath);
        }

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }

    private function preventSelfDemotion(Request $request, User $user, string $role): void
    {
        abort_if(
            $user->is($request->user()) && $user->role === 'admin' && $role !== 'admin',
            422,
            'You cannot demote your own administrator account.'
        );
    }
}
