@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Card -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <div class="flex flex-col md:flex-row justify-between items-center md:items-start">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 md:mb-0">Student: {{ $user->name }}</h3>
                    <span class="text-sm text-gray-500">Date Requested: {{ $request->created_at->format('M d, Y') }}</span>
                </div>

                <div class="mt-4 text-gray-700">
                    <p><strong class="font-semibold">Reference Type:</strong> {{ ucfirst($request->reference_type) }}</p>
                    <p><strong class="font-semibold">Request Type:</strong> {{ ucfirst($request->request_type) }}</p>
                    @if($request->reference_type == 'email')
                        <p><strong class="font-semibold text-red-500">Reference To be Sent To:</strong> {{ $request->reference_email }}</p>
                    @endif
                    <p><strong class="font-semibold">Status:</strong> <span class="capitalize {{ $request->status == 'pending' ? 'text-yellow-500' : ($request->status == 'lecturer approved' ? 'text-green-500' : 'text-red-500') }}">
                        {{ ucfirst($request->status) }}
                    </span>
                    </p>
                    <p><strong class="font-semibold">Description:</strong> {{ $request->reference_description }}</p>
                </div>
            </div>

            <!-- Student Details Card -->
{{--            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">--}}
{{--                <h4 class="text-lg font-semibold text-gray-800 mb-4">Student Details</h4>--}}
{{--                <p><strong class="font-semibold">Student Name:</strong> {{ $request->student->name }}</p>--}}
{{--                <p><strong class="font-semibold">Student Email:</strong> {{ $request->student->email }}</p>--}}
{{--                <p><strong class="font-semibold">Student Institution:</strong> {{ $request->student->institution->name }}</p>--}}

{{--                <div class="mt-4">--}}
{{--                    <h5 class="text-md font-semibold text-gray-800">Uploaded Documents</h5>--}}
{{--                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">--}}
{{--                        @foreach($documents as $document)--}}
{{--                            <div class="bg-gray-100 p-4 rounded-lg shadow-sm">--}}
{{--                                <h6 class="text-sm font-semibold">{{ ucfirst($document->type) }}</h6>--}}
{{--                                <a href="{{ asset('storage/'.$document->path) }}" class="text-blue-500 hover:text-blue-700" target="_blank">--}}
{{--                                    View Document--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


            <!-- Attended Institutions -->
{{--            <div class="max-w-4xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">--}}
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Attended Institutions</h3>
                <ul class="space-y-6">
                    @forelse($institutions as $institution)
                        <li class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                                <div class="w-full">
                                    <p class="text-lg font-semibold text-gray-800">{{ $institution->institution->name }}</p>
                                    @can('request-for-reference')
                                        <p class="mt-1 text-sm text-gray-600">
                                            {{ ucfirst($institution->type) }} - {{ ucfirst($institution->field_of_study) }}
                                        </p>
                                    @endcan
                                    @can('provide-a-reference')
                                        <p class="mt-1 text-sm text-gray-600">{{ ucfirst($institution->position) }}</p>
                                    @endcan
                                </div>
                                <!-- Optional view button -->
                                {{-- <button class="mt-2 sm:mt-0 text-indigo-600 text-sm font-medium hover:underline">
                                    View
                                </button> --}}
                            </div>

                            <!-- Supporting Documents -->
                            @if($institution->documents->isNotEmpty())
                                <div class="mt-4">
                                    <p class="text-sm font-semibold text-gray-700">Supporting Documents:</p>
                                    <ul class="mt-2 space-y-1">
                                        @foreach($institution->documents as $document)
                                            <li class="flex items-center gap-2">
                                                <a href="{{ asset('storage/' . $document->path) }}" target="_blank"
                                                   class="text-blue-600 text-sm hover:underline flex items-center">
                                                    {{ $document->name }}
                                                    <svg class="w-5 h-5 ml-1 text-gray-800" fill="none" stroke="currentColor"
                                                         stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M14 4v6h6m-2-2L10 16m4-8H4v14h16V10l-6-6z" />
                                                    </svg>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @else
                                <p class="text-sm text-gray-500 mt-4">No supporting documents uploaded.</p>
                            @endif
                        </li>
                    @empty
                        <li class="text-center text-gray-500 py-6">
                            <p>No institutions added yet.</p>
                        </li>
                    @endforelse
                </ul>
            </div>

            <!-- Actions -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Actions</h4>
                <div class="flex gap-4">
                    <!-- Approve Request Form -->
                    <form action="{{ route('lecturer.reference.approve', $request->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-md shadow-sm hover:bg-green-600">
                            Approve Request
                        </button>
                    </form>

                    @if($request->status == 'lecturer approved')
                        @if($request->reference_type == 'email')
                            <form action="{{ route('lecturer.reference.confirm_email_sent', $request->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded-md shadow-sm hover:bg-gray-400">
                                    Confirm Email Sent
                                </button>

                            </form>
                        @endif
                            <form action="{{ route('lecturer.reference.confirm_completed', $request->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md shadow-sm hover:bg-gray-400">
                                    Confirm Completed
                                </button>
                            </form>
                    @endif

                    <!-- Reject Request Form with Reason -->
                    <div x-data="{ showModal: false }">
                        <!-- Reject Request Button -->
                        <button @click="showModal = true" class="px-4 py-2 bg-red-500 text-white rounded-md shadow-sm hover:bg-red-600">
                            Reject Request
                        </button>

                        <!-- Modal -->
                        <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Reject Request</h2>

                                <form action="{{ route('lecturer.reference.reject', $request->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <label for="reference_rejection_reason" class="block text-gray-700 font-semibold mb-2">Reason for Rejection:</label>
                                    <textarea id="reference_rejection_reason" name="reference_rejection_reason" class="w-full p-2 border border-gray-300 rounded-md" rows="3" placeholder="Enter rejection reason..." required></textarea>

                                    <div class="flex justify-end mt-4 gap-4">
                                        <!-- Cancel Button -->
                                        <button @click="showModal = false" type="button" class="px-4 py-2 bg-gray-400 text-white rounded-md shadow-sm hover:bg-gray-500">
                                            Cancel
                                        </button>
                                        <!-- Submit Button -->
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md shadow-sm hover:bg-red-600">
                                            Submit Rejection
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            @if($request->status == 'lecturer approved')
                <div class="bg-white shadow-lg sm:rounded-lg p-6 mt-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">
                        @if($request->reference_type == 'email')
                            Upload Proof of Verification for Sent Email (optional)
                        @elseif($request->reference_type == 'document')
                            Upload Document
                        @endif
                    </h4>

                    <form action="{{ route('lecturer.reference.upload', $request->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-gray-700 font-semibold" for="document">Select Document:</label>
                            <input type="file" id="document" name="document" class="w-full p-2 border border-gray-300 rounded-md" required>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600">
                            @if($request->reference_type == 'email')
                                Upload Email Proof
                            @elseif($request->reference_type == 'document')
                                Upload Document
                            @endif
                        </button>
                    </form>
                </div>
            @endif

{{--            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">--}}

{{--                <div class="mt-4">--}}
{{--                    <h5 class="text-md font-semibold text-gray-800">Uploaded Reference Documents</h5>--}}
{{--                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4">--}}
{{--                        @if($reference_documents->isEmpty())--}}
{{--                            <p>You are yet to upload/send a reference document.</p>--}}
{{--                        @else--}}
{{--                            @foreach($reference_documents as $document)--}}
{{--                                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">--}}
{{--                                    <h6 class="text-sm font-semibold">{{ ucfirst($document->type) }}</h6>--}}
{{--                                    <a href="{{ asset('storage/'.$document->path) }}" class="text-blue-500 hover:text-blue-700" target="_blank">--}}
{{--                                        View Document--}}
{{--                                    </a>--}}
{{--                                </div>--}}
{{--                            @endforeach--}}
{{--                        @endif--}}

{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

        </div>
    </div>
@endsection
