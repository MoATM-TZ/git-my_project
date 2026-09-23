@extends('layouts.app')
@section('title', 'Complete lecturer profile')
@section('content')
<div class="ll-auth"><div class="ll-card ll-auth-card"><h1 class="ll-auth-title">Complete your lecturer profile</h1>
<form method="POST" action="{{ route('onboarding.lecturer.save') }}" class="ll-form">@csrf
@foreach(['full_name'=>'Full name','department'=>'Department','phone_number'=>'Phone number'] as $field=>$label)<div class="ll-field"><label class="ll-label" for="{{ $field }}">{{ $label }}</label><input class="ll-input" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $field === 'full_name' ? auth()->user()->name : '') }}" @if($field !== 'phone_number') required @endif>@error($field)<p class="ll-field-error">{{ $message }}</p>@enderror</div>@endforeach
<button class="ll-btn ll-btn-primary ll-btn-block">Save profile</button></form></div></div>
@endsection
