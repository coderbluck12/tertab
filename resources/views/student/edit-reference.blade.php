<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Reference Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-10 text-gray-900">
                    <form method="POST" action="{{ route('reference.update', $reference->id) }}" class="max-w-lg">
                        @csrf
                        @method('PUT')

                        <!-- Lecturer Selection -->
                        <div class="mb-4">
                            <label for="lecturer" class="text-sm">Select Lecturer</label>
                            <select id="lecturer" name="lecturer_id" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select a Lecturer</option>
                                @foreach($lecturers as $lecturer)
                                    <option value="{{ $lecturer->id }}" {{ $reference->lecturer_id == $lecturer->id ? 'selected' : '' }}>{{ $lecturer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex gap-6">
                            <!-- Reference Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Reference Type</label>
                                <select id="reference_type" name="reference_type" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                    <option value="">Select Reference Format</option>
                                    <option value="email" {{ $reference->reference_type == 'email' ? 'selected' : '' }}>Email Reference</option>
                                    <option value="document" {{ $reference->reference_type == 'document' ? 'selected' : '' }}>Document Reference</option>
                                </select>
                            </div>

                            <!-- Request Type -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Request Type</label>
                                <select id="request_type" name="request_type" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                    <option value="">Select Request Type</option>
                                    <option value="normal" {{ $reference->request_type == 'normal' ? 'selected' : '' }}>Normal Request</option>
                                    <option value="express" {{ $reference->request_type == 'express' ? 'selected' : '' }}>Express Request</option>
                                </select>
                            </div>
                        </div>

                        <!-- Request Description -->
                        <div class="mb-4">
                            <label for="reference_description" class="block text-sm font-medium text-gray-700">Request Detail / Description</label>
                            <textarea id="reference_description" name="reference_description" class="w-full p-2 border rounded mt-1" required>{{ old('reference_description', $reference->reference_description) }}</textarea>
                            <x-input-error :messages="$errors->get('reference_description')" class="mt-2" />
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-2">
                            <a href="{{ route('student.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded">
                                Cancel
                            </a>
                            <x-primary-button>Update Request</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
