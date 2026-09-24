@extends('layouts.app')
@section('title', 'User Management')
@section('content')
<div style="max-width: 1280px; margin: 0 auto; padding: 24px; font-family: system-ui, -apple-system, sans-serif; color: #0f172a;">
    <header style="display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 16px; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 26px; font-weight: 700; margin: 0 0 5px;">User Management</h1>
            <p style="font-size: 14px; color: #64748b; margin: 0;">Review accounts, manage roles, and control access.</p>
        </div>
        <div style="padding: 10px 14px; border: 1px solid #e2e8f0; border-radius: 10px; background: #fff; color: #475569; font-size: 14px;">
            Total users <strong style="color: #0f172a; margin-left: 5px;">{{ number_format($totalUsers) }}</strong>
        </div>
    </header>

    <form method="GET" action="{{ route('admin.users.index') }}" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 10px; padding: 16px; margin-bottom: 18px; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px;">
        <label style="display: block;">
            <span style="display: block; margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: 600;">Search</span>
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Name or email" style="width: 100%; box-sizing: border-box; padding: 10px 11px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font: inherit; font-size: 14px;">
        </label>
        <label style="display: block;">
            <span style="display: block; margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: 600;">Role</span>
            <select name="role" style="width: 100%; box-sizing: border-box; padding: 10px 11px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font: inherit; font-size: 14px;">
                <option value="">All roles</option>
                @foreach(['student' => 'Student', 'lecturer' => 'Lecturer', 'admin' => 'Administrator'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('role') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <label style="display: block;">
            <span style="display: block; margin-bottom: 5px; color: #64748b; font-size: 12px; font-weight: 600;">Approval status</span>
            <select name="status" style="width: 100%; box-sizing: border-box; padding: 10px 11px; border: 1px solid #cbd5e1; border-radius: 8px; background: #fff; font: inherit; font-size: 14px;">
                <option value="">All statuses</option>
                @foreach(['approved' => 'Approved', 'pending' => 'Pending', 'rejected' => 'Rejected'] as $value => $label)
                    <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                @endforeach
            </select>
        </label>
        <div style="display: flex; align-items: flex-end; gap: 8px;">
            <button type="submit" style="padding: 10px 16px; border: 0; border-radius: 8px; background: #4f46e5; color: #fff; font-size: 14px; font-weight: 600; cursor: pointer;">Filter</button>
            <a href="{{ route('admin.users.index') }}" style="padding: 10px 12px; color: #475569; font-size: 14px; text-decoration: none;">Reset</a>
        </div>
    </form>

    <div style="overflow-x: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; box-shadow: 0 1px 3px rgba(15,23,42,.04);">
        <table style="width: 100%; min-width: 970px; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .04em;">
                    <th style="padding: 13px 16px;">User</th>
                    <th style="padding: 13px 16px;">Role</th>
                    <th style="padding: 13px 16px;">Approval</th>
                    <th style="padding: 13px 16px;">Joined</th>
                    <th style="padding: 13px 16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        $roleColors = ['student' => ['#eff6ff', '#1d4ed8'], 'lecturer' => ['#f5f3ff', '#6d28d9'], 'admin' => ['#fff7ed', '#c2410c']];
                        $approvalColors = ['approved' => ['#ecfdf5', '#047857'], 'pending' => ['#fffbeb', '#b45309'], 'rejected' => ['#fff1f2', '#be123c']];
                        $roleColor = $roleColors[$user->role] ?? ['#f1f5f9', '#475569'];
                        $approvalColor = $approvalColors[$user->approval_status] ?? ['#f1f5f9', '#475569'];
                        $isCurrentAdmin = $user->is(auth()->user());
                    @endphp
                    <tr style="border-top: 1px solid #f1f5f9;">
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 11px;">
                                <img src="{{ $user->avatar_url }}" alt="" style="width: 40px; height: 40px; flex: none; border-radius: 50%; object-fit: cover; background: #e2e8f0;">
                                <div style="min-width: 0;">
                                    <div style="font-size: 14px; font-weight: 650; color: #0f172a;">{{ $user->name }}</div>
                                    <div style="margin-top: 3px; color: #64748b; font-size: 12px;">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 14px 16px;">
                            <span style="display: inline-block; padding: 5px 9px; border-radius: 999px; background: {{ $roleColor[0] }}; color: {{ $roleColor[1] }}; font-size: 12px; font-weight: 650;">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td style="padding: 14px 16px;">
                            <span style="display: inline-block; padding: 5px 9px; border-radius: 999px; background: {{ $approvalColor[0] }}; color: {{ $approvalColor[1] }}; font-size: 12px; font-weight: 650;">{{ ucfirst($user->approval_status ?? 'pending') }}</span>
                        </td>
                        <td style="padding: 14px 16px; white-space: nowrap; color: #475569; font-size: 13px;">{{ $user->created_at?->format('M d, Y') ?? '—' }}</td>
                        <td style="padding: 14px 16px;">
                            <div style="display: flex; align-items: center; gap: 7px;">
                                <form method="POST" action="{{ route('admin.users.role.update', $user) }}" style="display: flex; align-items: center; gap: 5px; margin: 0;">
                                    @csrf @method('PATCH')
                                    <select name="role" aria-label="Role for {{ $user->name }}" @disabled($isCurrentAdmin) style="max-width: 125px; padding: 7px 8px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; font-size: 12px;">
                                        @foreach(['student' => 'Student', 'lecturer' => 'Lecturer', 'admin' => 'Administrator'] as $value => $label)
                                            <option value="{{ $value }}" @selected($user->role === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @unless($isCurrentAdmin)
                                        <button type="submit" style="padding: 7px 9px; border: 1px solid #cbd5e1; border-radius: 7px; background: #fff; color: #334155; font-size: 12px; font-weight: 600; cursor: pointer;">Save</button>
                                    @endunless
                                </form>
                                <form method="POST" action="{{ route('admin.users.status.toggle', $user) }}" style="margin: 0;">
                                    @csrf @method('PATCH')
                                    <button type="submit" style="padding: 7px 9px; border: 1px solid {{ $user->is_active ? '#fed7aa' : '#bbf7d0' }}; border-radius: 7px; background: {{ $user->is_active ? '#fff7ed' : '#f0fdf4' }}; color: {{ $user->is_active ? '#c2410c' : '#15803d' }}; font-size: 12px; font-weight: 600; cursor: pointer;">{{ $user->is_active ? 'Deactivate' : 'Activate' }}</button>
                                </form>
                                @unless($isCurrentAdmin)
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Delete this user and their account data?')" style="margin: 0;">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="padding: 7px 9px; border: 1px solid #fecaca; border-radius: 7px; background: #fff; color: #b91c1c; font-size: 12px; font-weight: 600; cursor: pointer;">Delete</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" style="padding: 40px 16px; text-align: center; color: #64748b; font-size: 14px;">No users match these filters.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-top: 18px; color: #64748b; font-size: 13px;">
        <span>Showing {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} of {{ number_format($users->total()) }} matching users</span>
        {{ $users->links() }}
    </div>
</div>
@endsection
