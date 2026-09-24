@auth
    @include(match (auth()->user()->role) {
        'admin' => 'layouts.navigation.admin',
        'lecturer' => 'layouts.navigation.lecturer',
        default => 'layouts.navigation.student',
    })
@else
    @include('layouts.navigation.guest')
@endauth
