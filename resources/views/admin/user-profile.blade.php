@extends('layouts.app')

@section('content')
    <div class="py-8">
        <!-- User Details Card -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">User profile details</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($user->role) }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Joined</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->phone }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Residential</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->address }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Attended Institutions -->
        <div class="max-w-4xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
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
                        </div>

                        <!-- Supporting Documents -->
                        @if($institution->documents->isNotEmpty())
                            <div class="mt-4">
                                <p class="text-sm font-semibold text-gray-700">Proof of Attendance/Employment:</p>
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
                            <p class="text-sm text-gray-500 mt-4">No proof uploaded.</p>
                        @endif
                    </li>
                @empty
                    <li class="text-center text-gray-500 py-6">
                        <p>No institutions added yet.</p>
                    </li>
                @endforelse
            </ul>
        </div>

        <!-- Verification Details -->
        <div class="max-w-4xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Verification Details</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">User verification information and documents</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        @if($user->verificationRequest)
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">ID Card Type</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @switch($user->verificationRequest->document_type)
                                        @case('national_id')
                                            National ID Card
                                            @break
                                        @case('passport')
                                            Passport
                                            @break
                                        @case('drivers_license')
                                            Driver's License
                                            @break
                                        @default
                                            {{ ucfirst($user->verificationRequest->document_type) }}
                                    @endswitch
                                </dd>
                            </div>
                            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 py-1 text-sm font-medium rounded
                                        {{ $user->verificationRequest->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($user->verificationRequest->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                        {{ ucfirst($user->verificationRequest->status) }}
                                    </span>
                                </dd>
                            </div>
                            @if($user->verificationRequest->notes)
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Additional Notes</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->verificationRequest->notes }}</dd>
                                </div>
                            @endif
                            @if($user->verificationRequest->rejection_reason)
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Rejection Reason</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->verificationRequest->rejection_reason }}</dd>
                                </div>
                            @endif

                            <!-- Verification Documents -->
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">ID Card Document</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    @if($user->verificationRequest && $user->verificationRequest->document_path)
                                        <a href="{{ asset('storage/' . $user->verificationRequest->document_path) }}" target="_blank"
                                           class="text-blue-600 text-sm hover:underline flex items-center">
                                            View ID Card
                                            <svg class="w-5 h-5 ml-1 text-gray-800" fill="none" stroke="currentColor"
                                                 stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M14 4v6h6m-2-2L10 16m4-8H4v14h16V10l-6-6z" />
                                            </svg>
                                        </a>
                                    @else
                                        <p class="text-gray-500">No ID card document uploaded.</p>
                                    @endif
                                </dd>
                            </div>

                            <!-- Verification Actions -->
                            @if($user->verificationRequest->status === 'pending')
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                    <dt class="text-sm font-medium text-gray-500">Actions</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        <div class="flex space-x-4">
                                            <form action="{{ route('admin.verification.approve', $user->verificationRequest->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                                                    Approve Verification
                                                </button>
                                            </form>
                                            <button type="button" 
                                                    x-data
                                                    @click="$dispatch('open-reject-modal')" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                                Reject Verification
                                            </button>
                                        </div>
                                    </dd>
                                </div>
                            @endif
                        @else
                            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500">Verification Status</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 py-1 text-sm font-medium rounded bg-gray-100 text-gray-800">
                                        No verification request submitted
                                    </span>
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
    </div>

    @include('admin.reject-modal')
@endsection
