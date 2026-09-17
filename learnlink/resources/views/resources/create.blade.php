@extends('layouts.app')

@section('title', 'Upload a resource')

@section('content')
    <section class="ll-page-head">
        <h1>Share a learning resource</h1>
        <p>Upload notes, slides, past papers, books, and other useful materials. Files can be up to 20 MB.</p>
    </section>

    <form method="POST" action="{{ route('resources.store') }}" enctype="multipart/form-data" class="ll-card ll-form ll-form-wide">
        @csrf
        @if(isset($classes) && $classes->isNotEmpty())
            <div class="ll-field">
                <label class="ll-label" for="course_class_id">Class <span class="ll-muted">(optional)</span></label>
                <select class="ll-input" id="course_class_id" name="course_class_id">
                    <option value="">Share globally</option>
                    @foreach($classes as $class)<option value="{{ $class->id }}" @selected(old('course_class_id') == $class->id)>{{ $class->course_code }} — {{ $class->course_title }}</option>@endforeach
                </select>
            </div>
        @endif
        <div class="ll-field">
            <label class="ll-label" for="title">Title</label>
            <input class="ll-input" id="title" name="title" value="{{ old('title') }}" required maxlength="255" placeholder="e.g. Introduction to Economics notes">
            @error('title') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="ll-field">
            <label class="ll-label" for="category">Category</label>
            <select class="ll-input" id="category" name="category" required>
                @foreach (\App\Models\Resource::CATEGORIES as $value => $label)
                    <option value="{{ $value }}" @selected(old('category') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('category') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="ll-field">
            <label class="ll-label" for="description">Description <span class="ll-muted">(optional)</span></label>
            <textarea class="ll-input" id="description" name="description" rows="6" maxlength="5000" placeholder="What does this resource cover and who is it for?">{{ old('description') }}</textarea>
            @error('description') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="ll-field">
            <label class="ll-label" for="file">File</label>
            <input class="ll-input" type="file" id="file" name="file" required accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.md,.csv,.zip,.jpg,.jpeg,.png,.gif,.webp,.php,.js,.ts,.jsx,.tsx,.py,.java,.c,.cpp,.cs,.json,.html,.css,.sql">
            <p class="ll-help">Documents, presentations, spreadsheets, archives, and images are supported.</p>
            @error('file') <p class="ll-field-error">{{ $message }}</p> @enderror
        </div>
        <div class="ll-form-actions"><button class="ll-btn ll-btn-primary" type="submit"><x-icon name="upload" :size="16" /> Upload resource</button></div>
    </form>
@endsection
