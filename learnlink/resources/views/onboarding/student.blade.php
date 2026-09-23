@extends('layouts.app')
@section('title', 'Complete student profile')
@section('content')
<div class="ll-auth"><div class="ll-card ll-auth-card"><h1 class="ll-auth-title">Complete your student profile</h1>
<form method="POST" action="{{ route('onboarding.student.save') }}" class="ll-form">@csrf
@foreach(['full_name'=>'Full name','registration_number'=>'Registration number','year_of_study'=>'Year of study','phone_number'=>'Phone number','department'=>'Department','program_level'=>'Program level','program_name'=>'Program name'] as $field=>$label)
<div class="ll-field"><label class="ll-label" for="{{ $field }}">{{ $label }}</label>
@if($field === 'program_level')<select class="ll-input" name="{{ $field }}" id="{{ $field }}" required>@foreach(['Certificate','Diploma','Degree','Masters','PhD'] as $level)<option value="{{ $level }}">{{ $level }}</option>@endforeach</select>
@else<input class="ll-input" id="{{ $field }}" name="{{ $field }}" value="{{ old($field, $field === 'full_name' ? auth()->user()->name : '') }}" @if($field === 'year_of_study') type="number" min="1" max="10" @endif required>@endif
@error($field)<p class="ll-field-error">{{ $message }}</p>@enderror</div>@endforeach
<button class="ll-btn ll-btn-primary ll-btn-block">Save profile</button></form></div></div>
@endsection
