@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('success'))
                <div id="success-message" class="bg-green-500 text-white p-4 rounded-md shadow-md mb-4">
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(() => {
                        document.getElementById('success-message').style.display = 'none';
                    }, 5000); // Hide after 5 seconds
                </script>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">

                <!-- Pending Requests -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-blue-500">
                    <div class="flex-grow">
                        <p class="text-sm text-blue-600 font-semibold">PENDING APPROVALS</p>
                        <h3 class="text-2xl font-bold">{{ $lecturerStats['pending'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-file text-blue-500 text-3xl"></i>
                    </div>
                </div>

                <!-- Approved Requests -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-green-500">
                    <div class="flex-grow">
                        <p class="text-sm text-green-600 font-semibold">APPROVED REFERENCES</p>
                        <h3 class="text-2xl font-bold">{{ $lecturerStats['approved'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                    </div>
                </div>

                <!-- Rejected Requests -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-red-500">
                    <div class="flex-grow">
                        <p class="text-sm text-red-600 font-semibold">REJECTED REQUESTS</p>
                        <h3 class="text-2xl font-bold">{{ $lecturerStats['rejected'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-times-circle text-red-500 text-3xl"></i>
                    </div>
                </div>

                <!-- Total Requests -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-teal-500">
                    <div class="flex-grow">
                        <p class="text-sm text-teal-600 font-semibold">TOTAL REQUESTS RECEIVED</p>
                        <h3 class="text-2xl font-bold">{{ $lecturerStats['pending'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-file-alt text-teal-500 text-3xl"></i>
                    </div>
                </div>

                <!-- Awaiting -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-gray-800">
                    <div class="flex-grow">
                        <p class="text-sm text-gray-800 font-semibold">AWAITING STUDENT CONFIRMATION</p>
                        <h3 class="text-2xl font-bold">{{ $lecturerStats['awaiting'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-circle text-gray-800 text-3xl"></i>
                    </div>
                </div>

            </div>

            <!-- Verification Modal -->
            <div x-data="{ open: false }" 
                 x-show="open" 
                 x-on:open-verification-modal.window="open = true"
                 x-on:keydown.escape.window="open = false"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                 style="display: none;">
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                            <div>
                                <div class="mt-3 text-center sm:mt-5">
                                    <h3 class="text-base font-semibold leading-6 text-gray-900">Verify Your Identity</h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            Please provide the following information to verify your account.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <form method="POST" action="{{ route('verification.submit') }}" enctype="multipart/form-data" class="mt-5 sm:mt-6">
                                @csrf
                                
                                <!-- Full Name -->
                                <div class="mb-4">
                                    <x-input-label for="verification_name" :value="__('Full Name')" />
                                    <x-text-input id="verification_name" class="block mt-1 w-full" type="text" name="verification_name" required />
                                    <x-input-error :messages="$errors->get('verification_name')" class="mt-2" />
                                </div>

                                <!-- School Email -->
                                <div class="mb-4">
                                    <x-input-label for="school_email" :value="__('School Email')" />
                                    <x-text-input id="school_email" class="block mt-1 w-full" type="email" name="school_email" required />
                                    <x-input-error :messages="$errors->get('school_email')" class="mt-2" />
                                </div>

                                <!-- Institution -->
                                <div class="mb-4">
                                    <x-input-label for="institution" :value="__('Institution')" />
                                    <x-text-input id="institution" class="block mt-1 w-full" type="text" name="institution" required />
                                    <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                                </div>

                                <!-- Position/Department -->
                                <div class="mb-4">
                                    <x-input-label for="position" :value="__('Position/Department')" />
                                    <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" required />
                                    <x-input-error :messages="$errors->get('position')" class="mt-2" />
                                </div>

                                <!-- ID Card -->
                                <div class="mb-4">
                                    <x-input-label for="id_card" :value="__('ID Card')" />
                                    <input id="id_card" type="file" name="id_card" class="block mt-1 w-full" accept="image/*,.pdf" required />
                                    <x-input-error :messages="$errors->get('id_card')" class="mt-2" />
                                </div>

                                <!-- Additional Documents -->
                                <div class="mb-4">
                                    <x-input-label for="additional_documents" :value="__('Additional Documents (Optional)')" />
                                    <input id="additional_documents" type="file" name="additional_documents[]" class="block mt-1 w-full" accept="image/*,.pdf" multiple />
                                    <x-input-error :messages="$errors->get('additional_documents')" class="mt-2" />
                                </div>

                                <!-- Notes -->
                                <div class="mb-4">
                                    <x-input-label for="notes" :value="__('Additional Notes (Optional)')" />
                                    <textarea id="notes" name="notes" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3"></textarea>
                                    <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                </div>

                                <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                    <x-primary-button type="submit" class="w-full justify-center">
                                        {{ __('Submit Verification') }}
                                    </x-primary-button>
                                    <button type="button" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" @click="open = false">
                                        {{ __('Cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submitted Requests Table -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Reference Requests Assigned to You</h3>
                @if($user->status === 'pending')
                    <div class="col-span-full">
                        <button type="button" x-data x-on:click="$dispatch('open-verification-modal')" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Verify ID
                        </button>
                    </div>
                @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($requests as $request)
                        <div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-white">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold">{{ $request->student->name }}</h3>
                                <span class="px-2 py-1 text-sm font-medium rounded
                    {{ $request->status == 'pending' ? 'bg-blue-800 text-white' :
                       ($request->status == 'lecturer approved' ? 'bg-green-800 text-white' : 'bg-gray-800 text-white') }}">
                    {{ ucfirst($request->status) }}
                </span>
                            </div>
                            <p class="text-gray-600"><strong>Reference Type:</strong> {{ ucfirst($request->reference_type) }}</p>
                            <p class="text-gray-600"><strong>Request Type:</strong> {{ ucfirst($request->request_type) }}</p>
                            <p class="text-gray-600"><strong>Description:</strong> {{ $request->reference_description }}</p>
                            <p class="text-gray-500 text-sm"><strong>Date Requested:</strong> {{ $request->created_at->format('M d, Y') }}</p>

                            <div class="mt-3 flex space-x-3">
                                <a href="{{ route('lecturer.reference.show', $request->id) }}" class="text-blue-500 hover:underline">View</a>
                                @if($request->status !== 'pending')
                                    @if($request->dispute)
                                        <a href="{{ route('disputes.show', $request->dispute->id) }}" class="text-blue-500 hover:underline">
                                            Manage Dispute
                                        </a>
                                    @else
                                        <a href="{{ route('disputes.create', ['reference_id' => $request->id]) }}" class="text-red-500 hover:underline">
                                            Create Dispute
                                        </a>
                                    @endif
                                @endif
                                @if($request->status == 'pending')
                                    <!-- Approve Request Button -->
                                    <form action="{{ route('lecturer.reference.approve', $request->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-500 hover:underline">Approve</button>
                                    </form>

                                    <!-- Reject Request with Modal -->
                                    <div x-data="{ showRejectModal: false }" class="inline-block">
                                        <!-- Reject Button (Opens Modal) -->
                                        <button @click="showRejectModal = true" class="text-red-500 hover:underline">
                                            Reject
                                        </button>

                                        <!-- Modal -->
                                        <div x-show="showRejectModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                                            <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
                                                <h2 class="text-lg font-semibold text-gray-800 mb-4">Reject Request</h2>

                                                <form action="{{ route('lecturer.reference.reject', $request->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')

                                                    <label for="reason" class="block text-gray-700 font-semibold mb-2">
                                                        Reason for Rejection:
                                                    </label>
                                                    <textarea id="rejection_reason" name="reference_rejection_reason"
                                                              class="w-full p-2 border border-gray-300 rounded-md" rows="3"
                                                              placeholder="Enter rejection reason..." required></textarea>

                                                    <div class="flex justify-end mt-4 gap-4">
                                                        <!-- Cancel Button -->
                                                        <button @click="showRejectModal = false" type="button"
                                                                class="px-4 py-2 bg-gray-400 text-white rounded-md shadow-sm hover:bg-gray-500">
                                                            Cancel
                                                        </button>

                                                        <!-- Submit Button -->
                                                        <button type="submit"
                                                                class="px-4 py-2 bg-red-500 text-white rounded-md shadow-sm hover:bg-red-600">
                                                            Submit Rejection
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No reference requests assigned to you.</p>
                    @endforelse
                </div>

            </div>

        </div>
    </div>
@endsection


{{--<x-app-layout>--}}
{{--    <x-slot name="header">--}}
{{--        <h2 class="font-semibold text-xl text-gray-800 leading-tight">--}}
{{--            {{ __('Dashboard') }}--}}
{{--        </h2>--}}
{{--    </x-slot>--}}

{{--    <div class="py-12">--}}
{{--        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">--}}
{{--            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">--}}
{{--                <div class="p-6 text-gray-900">--}}
{{--                    Hello Lecturer--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</x-app-layout>--}}
