@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Manage Courses</h1>
        <a href="{{ route('admin.courses.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Course
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="courses-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Duration</th>
                            <th>Fee</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->code }}</td>
                                <td>{{ $course->name }}</td>
                                <td>{{ $course->duration_months }} months</td>
                                <td>{{ number_format($course->fee, 2) }}</td>
                                <td>
                                    <span class="badge {{ $course->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $course->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.courses.show', $course->code) }}" class="btn btn-sm btn-info" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.courses.edit', $course->code) }}" class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.courses.destroy', $course->code) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this course?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No courses found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table th, .table td {
        vertical-align: middle;
    }
    .btn-sm {
        padding: 0.25rem 0.5rem;
    }
</style>
@endpush
