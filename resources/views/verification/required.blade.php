@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8 bg-white p-8 rounded-lg shadow-lg">
        <div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Identity Verification Required
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Please verify your identity to continue using the platform.
            </p>
        </div>

        <form class="mt-8 space-y-6" method="POST" action="{{ route('verification.submit') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Document Type -->
            <div>
                <x-input-label for="document_type" :value="__('Document Type')" />
                <select id="document_type" name="document_type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">Select Document Type</option>
                    <option value="national_id">National ID Card</option>
                    <option value="drivers_license">Driver's License</option>
                    <option value="passport">Passport</option>
                </select>
                <x-input-error :messages="$errors->get('document_type')" class="mt-2" />
            </div>

            <!-- ID Document -->
            <div>
                <x-input-label for="id_document" :value="__('Upload Document')" />
                <input id="id_document" type="file" name="id_document" class="mt-1 block w-full" accept="image/*,.pdf" required />
                <p class="mt-1 text-sm text-gray-500">Upload a clear image or PDF of your document. Maximum file size: 2MB</p>
                <x-input-error :messages="$errors->get('id_document')" class="mt-2" />
            </div>

            <div>
                <x-primary-button class="w-full justify-center">
                    {{ __('Submit Verification') }}
                </x-primary-button>
            </div>
        </form>

        <div class="mt-4 text-center text-sm text-gray-600">
            <p>Your account will be reviewed by our team. This process typically takes 1-2 business days.</p>
        </div>
    </div>
</div>
@endsection 