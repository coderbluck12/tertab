@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Edit Course: {{ $course->name }}</h1>
        <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Courses
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.courses.update', $course->code) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Course Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $course->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="code" class="form-label">Course Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $course->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3">{{ old('description', $course->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="duration_months" class="form-label">Duration (months) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('duration_months') is-invalid @enderror" 
                               id="duration_months" name="duration_months" min="1" 
                               value="{{ old('duration_months', $course->duration_months) }}" required>
                        @error('duration_months')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="fee" class="form-label">Fee <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">₦</span>
                            <input type="number" step="0.01" class="form-control @error('fee') is-invalid @enderror" 
                                   id="fee" name="fee" min="0" value="{{ old('fee', $course->fee) }}" required>
                            @error('fee')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" 
                                   id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $course->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="{{ route('admin.courses.show', $course->code) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Course
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-format the course code to uppercase
    document.getElementById('code').addEventListener('input', function(e) {
        this.value = this.value.toUpperCase();
    });
</script>
@endpush
