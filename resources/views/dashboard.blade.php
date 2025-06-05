@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(auth()->user()->status === 'pending')
            <!-- Verification Required Modal -->
            <div x-data="{ open: true }" 
                 x-show="open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
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

                            <form method="POST" action="{{ route('verification.submit') }}" enctype="multipart/form-data" class="mt-5 sm:mt-6">
                                @csrf
                                
                                <!-- Document Type -->
                                <div class="mb-4">
                                    <label for="document_type" class="block text-sm font-medium text-gray-700">Select Document Type</label>
                                    <select id="document_type" name="document_type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="">Select a document type</option>
                                        <option value="national_id">National ID Card</option>
                                        <option value="drivers_license">Driver's License</option>
                                        <option value="passport">Passport</option>
                                    </select>
                                </div>

                                <!-- Document Upload -->
                                <div class="mb-4">
                                    <label for="id_document" class="block text-sm font-medium text-gray-700">Upload Document</label>
                                    <input type="file" id="id_document" name="id_document" required
                                        class="mt-1 block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-indigo-50 file:text-indigo-700
                                        hover:file:bg-indigo-100"
                                        accept=".jpg,.jpeg,.png,.pdf">
                                    <p class="mt-1 text-sm text-gray-500">Upload a clear image or PDF of your document (max 2MB)</p>
                                </div>

                                <div class="mt-5 sm:mt-6">
                                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Submit for Verification
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
</div>
@endsection
