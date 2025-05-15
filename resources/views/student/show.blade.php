<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Reference Request Details') }}
        </h2>
    </x-slot>

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

            @if($request->status == 'lecturer approved')
                <div class="bg-white shadow-lg sm:rounded-lg p-6 mb-8">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Reference Documents</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @if($reference_documents->isEmpty())
                            <p>No reference documents available.</p>
                        @else
                            @foreach($reference_documents as $document)
                                <div class="bg-gray-100 p-4 rounded-lg shadow-sm">
                                    <h6 class="text-sm font-semibold">{{ ucfirst($document->type) }}</h6>
                                    <a href="{{ asset('storage/'.$document->path) }}" class="text-blue-500 hover:text-blue-700" target="_blank">
                                        Download Document
                                    </a>
                                </div>
                            @endforeach
                        @endif

                    </div>
                </div>
            @endif

            @if($request->status == 'lecturer declined')
                <div class="bg-white shadow-lg sm:rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Rejection Reason</h4>
                    <p class="text-red-500">{{ $request->reference_rejection_reason }}</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
