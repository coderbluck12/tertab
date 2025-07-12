@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-10 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Dispute for Reference #{{ $dispute->reference_id }}</h3>

                    <!-- Dispute Status -->
                    <p><strong>Status:</strong>
                        <span class="px-2 py-1 text-sm font-medium rounded
                            {{ $dispute->status == 'open' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ ucfirst($dispute->status) }}
                        </span>
                    </p>

                    <!-- Uploaded Documents -->
                    <div class="mt-6">
                        <h4 class="text-md font-semibold">Uploaded Documents</h4>
                        <div class="border rounded p-4 bg-gray-50 mt-2">
                            @forelse($documents as $document)
                                <div class="flex items-center justify-between mb-2 p-2 bg-white rounded border">
                                    <div class="flex items-center space-x-3">
                                        <div class="text-blue-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $document->original_name }}</p>
                                            <p class="text-sm text-gray-500">Uploaded by {{ $document->user->name }} on {{ $document->created_at->format('M d, Y h:i A') }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('disputes.documents.download', $document->id) }}" 
                                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                        Download
                                    </a>
                                </div>
                            @empty
                                <p class="text-gray-500">No documents uploaded yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Document Upload Form -->
                    @if($dispute->status === 'open')
                        <div class="mt-6">
                            <h4 class="text-md font-semibold">Upload Document</h4>
                            <form method="POST" action="{{ route('disputes.documents.store', $dispute->id) }}" 
                                  enctype="multipart/form-data" class="mt-2">
                                @csrf
                                <div class="mb-4">
                                    <label for="document" class="block text-sm font-medium text-gray-700">Select Document</label>
                                    <input type="file" id="document" name="document" 
                                           class="w-full p-2 border rounded mt-1" 
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt" required>
                                    <p class="text-sm text-gray-500 mt-1">Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT (Max 10MB)</p>
                                    <x-input-error :messages="$errors->get('document')" class="mt-2" />
                                </div>
                                <x-primary-button>Upload Document</x-primary-button>
                            </form>
                        </div>
                    @endif

                    <!-- Messages -->
                    <div class="mt-6">
                        <h4 class="text-md font-semibold">Discussion</h4>
                        <div class="border rounded p-4 bg-gray-100 mt-2">
                            @forelse($messages as $message)
                                <div class="mb-2">
                                    <strong>{{ $message->user->name }}:</strong>
                                    <p class="text-gray-700">{{ $message->message }}</p>
                                    <p class="text-sm text-gray-500">{{ $message->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <hr class="my-2">
                            @empty
                                <p class="text-gray-500">No messages yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Message Input -->
                    @if($dispute->status === 'open')
                        <form method="POST" action="{{ route('disputes.messages.send', $dispute->id) }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="dispute_id" value="{{ $dispute->id }}">

                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700">Your Message</label>
                                <textarea id="message" name="message" class="w-full p-2 border rounded mt-1" required></textarea>
                                <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            </div>

                            <x-primary-button>Send Message</x-primary-button>
                        </form>

                        <form method="POST" action="{{ route('disputes.resolve', $dispute->id) }}" class="mt-4">
                            @csrf
                            @method('PUT')
                            <x-primary-button class="bg-green-600 hover:bg-green-700">Resolve Dispute</x-primary-button>
                        </form>
                    @else
                        <p class="text-red-600 text-sm">This dispute has been resolved and is now closed.</p>
                        <p class="text-red-600 text-sm">Reopen to continue.</p>

                        <form method="POST" action="{{ route('disputes.open', $dispute->id) }}" class="mt-4">
                            @csrf
                            @method('PUT')
                            <x-primary-button class="bg-green-600 hover:bg-green-700">Reopen Dispute</x-primary-button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
