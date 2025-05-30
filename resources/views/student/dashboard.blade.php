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
                        <p class="text-sm text-blue-600 font-semibold">PENDING REQUESTS</p>
                        <h3 class="text-2xl font-bold">{{ $requestCounts['pending'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-file text-blue-500 text-3xl"></i>
                    </div>
                </div>

                <!-- Approved Requests -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-green-500">
                    <div class="flex-grow">
                        <p class="text-sm text-green-600 font-semibold">APPROVED REQUESTS</p>
                        <h3 class="text-2xl font-bold">{{ $requestCounts['approved'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-check-circle text-green-500 text-3xl"></i>
                    </div>
                </div>

                <!-- Rejected Requests -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-red-500">
                    <div class="flex-grow">
                        <p class="text-sm text-red-600 font-semibold">REJECTED REQUESTS</p>
                        <h3 class="text-2xl font-bold">{{ $requestCounts['rejected'] ?? 0 }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-times-circle text-red-500 text-3xl"></i>
                    </div>
                </div>

                <!-- Total Requests -->
                <div class="bg-white shadow-lg rounded-lg p-4 flex items-center border-l-4 border-teal-500">
                    <div class="flex-grow">
                        <p class="text-sm text-teal-600 font-semibold">TOTAL REQUESTS</p>
                        <h3 class="text-2xl font-bold">{{ array_sum($requestCounts) }}</h3>
                    </div>
                    <div>
                        <i class="fas fa-file-alt text-teal-500 text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Alpine.js wrapper -->
            <div x-data="{ open: false, init() { console.log('Modal state on load:', this.open); } }">
                <!-- Button to Open Modal -->
                {{--                <div class="flex justify-end">--}}
                {{--                    <button @click="open = true" class="bg-red-500 hover:bg-blue-600 text-white px-4 py-2 rounded">--}}
                {{--                        Request a Reference--}}
                {{--                    </button>--}}
                {{--                </div>--}}

                <!-- Modal -->
                <div x-show="open" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center">
                    <div @click.away="open = false" class="bg-white shadow-lg rounded-lg p-6 w-full max-w-md">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Request a Reference</h3>

                        <form method="POST" action="{{ route('reference.store') }}">
                            @csrf

                            <!-- Lecturer Selection -->
                            <div class="mb-4">
                                <label for="lecturer" class="block text-sm font-medium text-gray-700">Select Lecturer</label>
                                <select id="lecturer" name="lecturer_id" class="w-full p-2 border rounded mt-1">
                                    @foreach($lecturers as $lecturer)
                                        <option value="{{ $lecturer->id }}">{{ $lecturer->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Reference Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Reference Type</label>
                                <div class="flex space-x-4 mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="reference_type" value="document" class="form-radio">
                                        <span class="ml-2">Document Upload</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="reference_type" value="email" class="form-radio">
                                        <span class="ml-2">Email Reference</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Request Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Request Type</label>
                                <div class="flex space-x-4 mt-2">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="request_type" value="normal" class="form-radio">
                                        <span class="ml-2">Normal</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="request_type" value="express" class="form-radio">
                                        <span class="ml-2">Express</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Request Description -->
                            <div class="mb-4">
                                <label for="request_description" class="block text-sm font-medium text-gray-700">Request Detail / Description</label>
                                <textarea id="request_description" name="request_description" class="w-full p-2 border rounded mt-1" placeholder="Add a note to your reference request..." required></textarea>
                                <x-input-error :messages="$errors->get('request_description')" class="mt-2" />
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-2">
                                <button type="button" @click="open = false" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                                    Submit Request
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Submitted Requests Table -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                <div class="flex justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Your Reference Requests</h3>
                    @if($user->status !== 'pending')
                        <a href="{{ route('student.reference') }}" class="bg-red-500 hover:text-red-600 hover:border hover:border-red-600 text-white px-4 py-2 rounded">
                            Request for Reference
                        </a>
                    @else
                        <button type="button" x-data x-on:click="$dispatch('open-verification-modal')" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                            Verify ID
                        </button>
                    @endif
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

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($requests as $request)
                        <div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-white">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-lg font-semibold">{{ $request->lecturer->name }}</h3>
                                <span class="px-2 py-1 text-sm font-medium rounded
                {{ $request->status == 'pending' ? 'bg-blue-800 text-white' :
                   ($request->status == 'lecturer approved' ? 'bg-green-800 text-white' : 'bg-gray-800 text-white') }}">
                {{ ucfirst($request->status) }}
            </span>
                            </div>
                            <p class="text-gray-600"><strong>Format:</strong> {{ ucfirst($request->reference_type) }}</p>
                            <p class="text-gray-600"><strong>Type:</strong> {{ ucfirst($request->request_type) }}</p>
                            <p class="text-gray-600"><strong>Description:</strong> {{ $request->reference_description }}</p>
                            <p class="text-gray-500 text-sm"><strong>Date Requested:</strong> {{ $request->created_at->format('M d, Y') }}</p>

                            @php
                                switch ($request->status) {
                                    case 'pending':
                                        $progress = 25;
                                        break;
                                    case 'lecturer approved':
                                        $progress = 50;
                                        break;
                                    case 'lecturer completed':
                                        $progress = 75;
                                        break;
                                    case 'student confirmed':
                                        $progress = 100;
                                        break;
                                    default:
                                        $progress = 0;
                                        break;
                                }
                            @endphp

                                <!-- Progress Bar with Percentage -->
                            <div class="mt-3">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-800 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="text-right text-sm text-gray-600 mt-1">{{ $progress }}%</div>
                            </div>

                            <div class="mt-3 flex space-x-3">
                                <a href="{{ route('student.reference.show', $request->id) }}" class="text-blue-500 hover:underline">View</a>
                                @if(in_array($request->status, ['lecturer completed', 'lecturer email sent']))
                                    <a href="{{ route('student.reference.mark_completed', $request->id) }}" class="text-gray-800 hover:underline">Confirm Completed</a>
                                @endif
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
                                    <a href="{{ route('student.reference.edit', $request->id) }}" class="text-green-500 hover:underline">Edit</a>
                                    <form action="{{ route('reference.destroy', $request->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline"
                                                onclick="return confirm('Are you sure you want to delete this request?')">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">No reference requests yet.</p>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
