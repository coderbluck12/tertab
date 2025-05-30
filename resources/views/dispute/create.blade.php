@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-10 text-gray-900">
                    <form method="POST" action="{{ route('disputes.store') }}" class="max-w-lg">
                        @csrf

                        <!-- Hidden Reference ID -->
                        <input type="hidden" name="reference_id" value="{{ $reference->id }}">

                        <!-- Dispute Message -->
                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700">Dispute Message</label>
                            <textarea id="message" name="message" class="w-full p-2 border rounded mt-1" required>{{ old('message') }}</textarea>
                            <x-input-error :messages="$errors->get('message')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <a href="{{ route('student.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                                Cancel
                            </a>
                            <x-primary-button>Create Dispute</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
