@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-start">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 md:mb-0">Reference Details</h3>
                    <span class="text-sm text-gray-500">Date Requested: {{ $request->created_at->format('M d, Y') }}</span>
                </div>

                <div class="mt-4 text-gray-700">
                    <p><strong class="font-semibold">Reference Type:</strong> {{ ucfirst($request->reference_type) }}</p>
                    <p><strong class="font-semibold">Request Type:</strong> {{ ucfirst($request->request_type) }}</p>
                    <p><strong class="font-semibold">Status:</strong>
                        <span class="capitalize {{ $request->status == 'pending' ? 'text-yellow-500' : ($request->status == 'approved' ? 'text-green-500' : 'text-red-500') }}">
                            {{ ucfirst($request->status) }}
                        </span>
                    </p>
                    <p><strong class="font-semibold">Description:</strong> {{ $request->reference_description }}</p>
                </div>
            </div>

            <!-- Student Details Card -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Student Details</h4>
                <p><strong class="font-semibold">Student Name:</strong> {{ $request->student->name }}</p>
                <p><strong class="font-semibold">Student Email:</strong> {{ $request->student->email }}</p>

                <div class="mt-4">
                    <h5 class="text-md font-semibold text-gray-800">Uploaded Documents</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        @foreach($request->student->documents as $document)
                            <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                                <h6 class="text-sm font-semibold">{{ ucfirst($document->type) }}</h6>
                                <a href="{{ asset('storage/'.$document->path) }}" class="text-blue-500 hover:text-blue-700" target="_blank">
                                    View Document
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Lecturer Details Card -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Lecturer Details</h4>
                <p><strong class="font-semibold">Lecturer Name:</strong> {{ $request->lecturer->name }}</p>
                <p><strong class="font-semibold">Lecturer Email:</strong> {{ $request->lecturer->email }}</p>

                <div class="mt-4">
                    <h5 class="text-md font-semibold text-gray-800">Uploaded Documents</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        @foreach($request->lecturer->documents as $document)
                            <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                                <h6 class="text-sm font-semibold">{{ ucfirst($document->type) }}</h6>
                                <a href="{{ asset('storage/'.$document->path) }}" class="text-blue-500 hover:text-blue-700" target="_blank">
                                    View Document
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Admin View Only - No Actions -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Reference Status</h4>
                <p class="text-gray-600">This reference request is managed by the assigned lecturer. Admins can view details but cannot approve or reject reference requests.</p>
            </div>

            @if($request->status == 'approved')
                <div class="bg-white shadow-lg sm:rounded-lg p-6 mt-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        @if($request->reference_type == 'email')
                            Upload Document for Email
                        @elseif($request->reference_type == 'document')
                            Upload Document
                        @endif
                    </h4>

                    <form action="{{ route('admin.reference.upload', $request->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold" for="document">Select Document:</label>
                            <input type="file" id="document" name="document" class="w-full p-2 border border-gray-300 rounded-md" required>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600">
                            @if($request->reference_type == 'email')
                                Upload & Send Email
                            @elseif($request->reference_type == 'document')
                                Upload Document
                            @endif
                        </button>
                    </form>
                </div>
            @endif

            <!-- Uploaded Reference Documents Section -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <div class="mt-4">
                    <h5 class="text-md font-semibold text-gray-800">Uploaded Reference Documents</h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">
                        @if($request->documents->isEmpty())
                            <p>No reference document uploaded.</p>
                        @else
                            @foreach($request->documents as $document)
                                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                                    <h6 class="text-sm font-semibold">{{ ucfirst($document->type) }}</h6>
                                    <a href="{{ asset('storage/'.$document->path) }}" class="text-blue-500 hover:text-blue-700" target="_blank">
                                        View Document
                                    </a>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
