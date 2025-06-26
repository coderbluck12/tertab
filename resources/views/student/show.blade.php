@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-start">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 md:mb-0">Lecturer: {{ $request->lecturer->name }}</h3>
                    <span class="text-sm text-gray-500">Date Requested: {{ $request->created_at->format('M d, Y') }}</span>
                </div>

                <div class="mt-4 text-gray-700">
                    <p><strong class="font-semibold">Reference Type:</strong> {{ ucfirst($request->reference_type) }}</p>
                    <p><strong class="font-semibold">Request Type:</strong> {{ ucfirst($request->request_type) }}</p>

                    <p><strong class="font-semibold">Status:</strong>
                        <span class="capitalize {{ $request->status == 'pending' ? 'text-yellow-800' : ($request->status == 'lecturer approved' ? 'text-green-600' : 'text-green-800') }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </p>
                    <p><strong class="font-semibold">Description:</strong> {{ $request->reference_description }}</p>
                </div>
            </div>

            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-800 border-b pb-4 mb-4">Reference Request Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p><strong class="font-semibold">Reference Type:</strong> {{ ucfirst($request->reference_type) }}</p>
                        <p><strong class="font-semibold">Request Type:</strong> {{ ucfirst($request->request_type) }}</p>

                        <p><strong class="font-semibold">Status:</strong>
                            <span class="capitalize {{ $request->status == 'pending' ? 'text-yellow-800' : ($request->status == 'lecturer approved' ? 'text-green-600' : 'text-green-800') }}">
                                {{ ucfirst($request->status) }}
                            </span>
                        </p>
                        <p><strong class="font-semibold">Description:</strong> {{ $request->reference_description }}</p>
                    </div>
                </div>
            </div>

            @if($request->document_path)
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Uploaded Reference Document</h4>
                <ul class="list-disc list-inside">
                    <li>
                        <a href="{{ asset('storage/' . $request->document_path) }}" target="_blank" class="text-blue-600 hover:underline">
                            Download Document
                        </a>
                    </li>
                </ul>
                @if(in_array($request->status, ['document_uploaded', 'lecturer completed']))
                    <form action="{{ route('student.reference.mark_completed', $request->id) }}" method="GET" class="mt-4">
                        <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded">
                            Mark as Completed
                        </button>
                    </form>
                @endif
            </div>
            @else
                @if(in_array($request->status, ['document_uploaded', 'lecturer completed']))
                    <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                        <form action="{{ route('student.reference.mark_completed', $request->id) }}" method="GET">
                            <button type="submit" class="bg-green-700 hover:bg-green-800 text-white px-4 py-2 rounded">
                                Mark as Completed
                            </button>
                        </form>
                    </div>
                @endif
            @endif

            <!-- Institution Attended -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h4 class="text-lg font-semibold text-gray-800">Institution Attended</h4>
                </div>
                <p>{{ $request->student->institution_attended }}</p>
            </div>

            @if($request->status == 'lecturer declined')
                <div class="bg-white shadow-lg sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Rejection Reason</h4>
                    <p class="text-red-500">{{ $request->reference_rejection_reason }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection
