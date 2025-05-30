@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">All Students</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($students as $student)
                        <x-user-card :user="$student" />
                    @empty
                        <p class="text-center text-gray-500">No students available.</p>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
