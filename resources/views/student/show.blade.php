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

            <!-- Messaging Section -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Communication with Lecturer</h4>
                
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <!-- Message Form -->
                <form method="POST" action="{{ route('student.reference.message', $request->id) }}" class="mb-6">
                    @csrf
                    <div class="mb-4">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Send message to {{ $request->lecturer->name }}
                        </label>
                        <textarea name="message" id="message" rows="4" 
                                  class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                  placeholder="Type your message here... (e.g., 'Thank you for your feedback' or 'I have a question about the requirements')" 
                                  required>{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Send Message
                    </button>
                </form>
                
                <!-- Message History -->
                @if($request->messages && $request->messages->count() > 0)
                    <div class="border-t pt-4">
                        <h5 class="text-md font-semibold text-gray-800 mb-3">Message History</h5>
                        <div class="space-y-3 max-h-60 overflow-y-auto">
                            @foreach($request->messages->sortByDesc('created_at') as $message)
                                <div class="bg-gray-50 p-4 rounded-lg border-l-4 {{ $message->sender_id === $request->lecturer_id ? 'border-blue-500' : 'border-green-500' }}">
                                    <div class="flex justify-between items-start mb-2">
                                        <span class="text-sm font-medium text-gray-800">
                                            {{ $message->sender->name }}
                                            @if($message->sender_id === $request->lecturer_id)
                                                <span class="text-xs text-blue-600">(Lecturer)</span>
                                            @else
                                                <span class="text-xs text-green-600">(You)</span>
                                            @endif
                                        </span>
                                        <span class="text-xs text-gray-500">
                                            {{ $message->created_at->format('M d, Y H:i') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-700">{{ $message->message }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="border-t pt-4">
                        <p class="text-gray-500 text-sm">No messages yet. Send a message to start the conversation.</p>
                    </div>
                @endif
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
