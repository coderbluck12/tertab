<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Request for Reference') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-10 text-gray-900">
                    <form method="POST" action="{{ route('reference.store') }}" class="max-w-lg">
                        @csrf
                        <div class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-300">
                            <h5 class="text-md font-semibold text-gray-700">Current Reference Request Price</h5>
                            <p class="text-lg font-bold text-green-600 mt-2">₦{{ number_format($settings->reference_request_price, 2) }}</p>
                        </div>

                        <!-- State Selection -->
                        <div class="mb-4">
                            <label for="state" class="text-sm">Select State</label>
                            <select id="state" name="state_id" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select a State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lecturer Selection (Dynamic) -->
                        <div class="mb-4">
                            <label for="lecturer" class="text-sm">Select Lecturer</label>
                            <select id="lecturer" name="lecturer_id" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select a Lecturer</option>
                            </select>
                        </div>

                        <!-- Reference Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Reference Type</label>
                            <select id="reference_type" name="reference_type" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select Reference Format</option>
                                <option value="email">Email Reference</option>
                                <option value="document">Document Reference</option>
                            </select>
                        </div>

                        <!-- Request Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Request Type</label>
                            <select id="request_type" name="request_type" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select Request Type</option>
                                <option value="normal">Normal Request</option>
                                <option value="express">Express Request</option>
                            </select>
                        </div>

                        <!-- Request Description -->
                        <div class="mb-4">
                            <label for="reference_description" class="block text-sm font-medium text-gray-700">Request Detail / Description</label>
                            <textarea id="reference_description" name="reference_description" class="w-full p-2 border rounded mt-1" placeholder="Add a note to your reference request..." required></textarea>
                            <x-input-error :messages="$errors->get('request_description')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <x-primary-button>Submit Request</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Lecturer Filtering -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let stateDropdown = document.getElementById("state");
            let lecturerDropdown = document.getElementById("lecturer");

            stateDropdown.addEventListener("change", function () {
                let stateId = this.value;
                lecturerDropdown.innerHTML = '<option value="">Select a Lecturer</option>';

                if (stateId) {
                    fetch(`/reference-lecturers-by-state/${stateId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(lecturer => {
                                let option = document.createElement("option");
                                option.value = lecturer.id;
                                option.textContent = lecturer.name;
                                lecturerDropdown.appendChild(option);
                            });
                        })
                        .catch(error => console.error("Error fetching lecturers:", error));
                }
            });
        });
    </script>

</x-app-layout>
