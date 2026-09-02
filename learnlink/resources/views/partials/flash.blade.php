@php
$alert = $alert ?? null;
$errorBag = $errors->any() ? $errors->first() : null;
@endphp

@if (session('status'))
    <div class="ll-flash ll-flash-success" role="status">
        <x-icon name="check" :size="18" />
        <span>{{ session('status') }}</span>
        <button type="button" class="ll-flash-close" data-flash-close aria-label="Dismiss">
            <x-icon name="x" :size="16" />
        </button>
    </div>
@endif

@if ($alert)
    <div class="ll-flash ll-flash-error" role="alert">
        <x-icon name="alert" :size="18" />
        <span>{{ $alert }}</span>
        <button type="button" class="ll-flash-close" data-flash-close aria-label="Dismiss">
            <x-icon name="x" :size="16" />
        </button>
    </div>
@endif

@if ($errorBag)
    <div class="ll-flash ll-flash-error" role="alert">
        <x-icon name="alert" :size="18" />
        <span>{{ $errorBag }}</span>
        <button type="button" class="ll-flash-close" data-flash-close aria-label="Dismiss">
            <x-icon name="x" :size="16" />
        </button>
    </div>
@endif
