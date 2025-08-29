@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Course Details: {{ $course->name }}</h1>
        <div>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary me-2">
                <i class="fas fa-arrow-left"></i> Back to Courses
            </a>
            <a href="{{ route('admin.courses.edit', $course->code) }}" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Course
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Course Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Course Code:</div>
                        <div class="col-md-8">{{ $course->code }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Course Name:</div>
                        <div class="col-md-8">{{ $course->name }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Duration:</div>
                        <div class="col-md-8">{{ $course->duration_months }} months</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Fee:</div>
                        <div class="col-md-8">₦{{ number_format($course->fee, 2) }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 fw-bold">Status:</div>
                        <div class="col-md-8">
                            <span class="badge {{ $course->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $course->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 fw-bold">Description:</div>
                        <div class="col-md-8">
                            {{ $course->description ?? 'No description provided.' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.courses.edit', $course->code) }}" class="btn btn-primary mb-2">
                            <i class="fas fa-edit"></i> Edit Course
                        </a>
                        <form action="{{ route('admin.courses.destroy', $course->code) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this course?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-trash"></i> Delete Course
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Course Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Created:</span>
                            <span>{{ $course->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="progress" style="height: 5px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ now()->diffInDays($course->created_at) }}%" 
                                 aria-valuenow="{{ now()->diffInDays($course->created_at) }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Last Updated:</span>
                            <span>{{ $course->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
