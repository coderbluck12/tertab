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

            <!-- Verification Alert Banner for Unverified Users -->
            @if($user->status === 'pending')
                <div class="bg-blue-600 text-white p-4 rounded-lg shadow-lg mb-6 border-l-4 border-blue-300">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-100" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg">Identity Verification Required</h4>
                                <p class="text-blue-100 text-sm mt-1">Complete your identity verification to access all features and approve student references.</p>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" 
                                    x-data 
                                    x-on:click="$dispatch('open-verification-modal')" 
                                    class="bg-white text-blue-600 hover:bg-blue-50 font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Verify Now</span>
                            </button>
                        </div>
                    </div>
                </div>
            @elseif($user->status === 'rejected')
                <div class="bg-red-600 text-white p-4 rounded-lg shadow-lg mb-6 border-l-4 border-red-300">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-red-100" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-lg">Identity Verification Rejected</h4>
                                <p class="text-red-100 text-sm mt-1">Your identity verification was rejected. Please review the feedback and resubmit your documents to access all features.</p>
                                @if($user->verificationRequest && $user->verificationRequest->rejection_reason)
                                    <p class="text-red-100 text-xs mt-2 bg-red-500 bg-opacity-30 p-2 rounded">
                                        <strong>Reason:</strong> {{ $user->verificationRequest->rejection_reason }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <button type="button" 
                                    x-data 
                                    x-on:click="$dispatch('open-verification-modal')" 
                                    class="bg-white text-red-600 hover:bg-red-50 font-semibold px-4 py-2 rounded-lg shadow-sm transition-colors duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Resubmit Documents</span>
                            </button>
                        </div>
                    </div>
                </div>
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

            <!-- Quick Actions (Only for verified users) -->
            @if(auth()->user()->status === 'verified')
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('lecturer.references') }}" class="bg-blue-600 hover:bg-blue-700 text-white p-4 rounded-lg shadow-md flex items-center justify-center transition-colors">
                    <i class="fas fa-list-alt mr-2"></i>
                    View All References
                </a>
                <a href="{{ route('notifications.index') }}" class="bg-green-600 hover:bg-green-700 text-white p-4 rounded-lg shadow-md flex items-center justify-center transition-colors">
                    <i class="fas fa-bell mr-2"></i>
                    View Notifications
                </a>
                <a href="{{ route('institution.attended.list') }}" class="bg-blue-600 hover:bg-purple-700 text-white p-4 rounded-lg shadow-md flex items-center justify-center transition-colors">
                    <i class="fas fa-university mr-2"></i>
                    View Institutions
                </a>
            </div>
            @endif

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
                                                Please upload a valid government-issued ID to verify your account.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="{{ route('verification.submit') }}" enctype="multipart/form-data" class="mt-5 sm:mt-6" 
                                    x-data="verificationForm"
                                    @submit.prevent="submitForm"
                                >
                                    @csrf
                                    
                                    <!-- Success Message -->
                                    <div x-show="success" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-green-700">
                                                    Verification request submitted successfully! We will review your documents shortly.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Error Message -->
                                    <div x-show="error" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 transform -translate-y-2"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         class="mb-4 bg-red-50 border-l-4 border-red-400 p-4">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm text-red-700" x-text="errorMessage"></p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- ID Card Type -->
                                    <div class="mb-4">
                                        <x-input-label for="document_type" :value="__('ID Card Type')" />
                                        <select id="document_type" name="document_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                            <option value="">Select ID Card Type</option>
                                            <option value="national_id">National ID Card</option>
                                            <option value="passport">Passport</option>
                                            <option value="drivers_license">Driver's License</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('document_type')" class="mt-2" />
                                    </div>

                                    <!-- ID Document -->
                                    <div class="mb-4">
                                        <x-input-label for="id_document" :value="__('Upload ID Card')" />
                                        <input id="id_document" type="file" name="id_document" class="block mt-1 w-full" accept="image/*,.pdf" required />
                                        <p class="mt-1 text-sm text-gray-500">Upload a clear image or PDF of your document. Maximum file size: 2MB</p>
                                        <x-input-error :messages="$errors->get('id_document')" class="mt-2" />
                                    </div>

                                    <!-- Notes -->
                                    <div class="mb-4">
                                        <x-input-label for="notes" :value="__('Additional Notes (Optional)')" />
                                        <textarea id="notes" name="notes" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="3"></textarea>
                                        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                                    </div>

                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-flow-row-dense sm:grid-cols-2 sm:gap-3">
                                        <x-primary-button type="submit" 
                                                        class="w-full justify-center"
                                                        x-bind:disabled="submitting">
                                            <span x-show="!submitting">Submit Verification</span>
                                            <span x-show="submitting" class="flex items-center">
                                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                Submitting...
                                            </span>
                                        </x-primary-button>
                                        <button type="button" 
                                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" 
                                                @click="open = false">
                                            {{ __('Cancel') }}
                                        </button>
                                    </div>
                                </form>

                                <script>
                                    document.addEventListener('alpine:init', () => {
                                        Alpine.data('verificationForm', () => ({
                                            submitting: false,
                                            success: false,
                                            error: false,
                                            errorMessage: '',
                                            submitForm(event) {
                                                this.submitting = true;
                                                this.success = false;
                                                this.error = false;
                                                
                                                const form = event.target;
                                                const formData = new FormData(form);
                                                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                                
                                                fetch(form.action, {
                                                    method: 'POST',
                                                    body: formData,
                                                    headers: {
                                                        'X-CSRF-TOKEN': csrfToken,
                                                        'Accept': 'application/json',
                                                        'X-Requested-With': 'XMLHttpRequest'
                                                    },
                                                    credentials: 'same-origin'
                                                })
                                                .then(async response => {
                                                    const contentType = response.headers.get('content-type');
                                                    if (!response.ok) {
                                                        if (contentType && contentType.includes('application/json')) {
                                                            const errorData = await response.json();
                                                            throw new Error(errorData.message || 'Network response was not ok');
                                                        } else {
                                                            throw new Error('Server error occurred. Please try again.');
                                                        }
                                                    }
                                                    if (!contentType || !contentType.includes('application/json')) {
                                                        throw new Error('Invalid response from server');
                                                    }
                                                    return response.json();
                                                })
                                                .then(data => {
                                                    this.submitting = false;
                                                    if (data.success) {
                                                        this.success = true;
                                                        setTimeout(() => {
                                                            window.location.reload();
                                                        }, 2000);
                                                    } else {
                                                        this.error = true;
                                                        this.errorMessage = data.message || 'An error occurred. Please try again.';
                                                    }
                                                })
                                                .catch(error => {
                                                    console.error('Error:', error);
                                                    this.submitting = false;
                                                    this.error = true;
                                                    this.errorMessage = error.message || 'An error occurred. Please try again.';
                                                });
                                            }
                                        }));
                                    });
                                </script>
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
                                @if($request->status !== 'pending' && auth()->user()->status === 'verified')
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
