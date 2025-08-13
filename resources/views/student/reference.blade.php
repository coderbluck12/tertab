@extends('layouts.app')

@section('content')
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-md shadow-md mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-10 text-gray-900 flex justify-center">
                    <form method="POST" action="{{ route('reference.store') }}" class="w-full max-w-2xl">
                        @csrf
                        @if($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <div class="mb-6 p-4 bg-gray-50 rounded-md border border-gray-300">
                            <h5 class="text-md font-semibold text-gray-700">Current Reference Request Price</h5>
                            <p id="price-display" class="text-lg font-bold text-green-600 mt-2">
                                ₦{{ number_format($settings->reference_request_price, 2) }}
                            </p>
                            <input hidden id="originalPrice" type="text" value="{{ $settings->reference_request_price }}">
                            <input hidden id="expressPrice" type="text" value="{{ $settings->express_reference_request_price }}">
                        </div>

                        <!-- Request Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Request Type</label>
                            <select id="request_type" name="request_type" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select Request Type</option>
                                <option value="normal" {{ old('request_type') == 'normal' ? 'selected' : '' }}>Normal Request</option>
                                <option value="express" {{ old('request_type') == 'express' ? 'selected' : '' }}>Express Request</option>
                            </select>
                        </div>

                        <!-- Express Request Notification -->
                        <div id="express-notification" class="mb-4 hidden p-4 bg-blue-50 rounded-md border border-blue-200">
                            <p class="text-sm text-blue-600">Express Request selected: Your request will be processed within 24 hours. The price has been updated accordingly.</p>
                        </div>

                        <!-- Reference Type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Reference Type</label>
                            <select id="reference_type" name="reference_type" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select Reference Format</option>
                                <option value="email" {{ old('reference_type') == 'email' ? 'selected' : '' }}>Email Reference</option>
                                <option value="document" {{ old('reference_type') == 'document' ? 'selected' : '' }}>Document Reference</option>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                <span id="email-hint" class="hidden">Email Reference: The reference will be sent directly to the specified email address.</span>
                                <span id="document-hint" class="hidden">Document Reference: The lecturer will upload a reference document that you can download.</span>
                            </p>
                        </div>

                        <!-- Email Input -->
                        <!-- <div id="email-input-container" class="mb-4 hidden">
                            <label for="reference_email" class="block text-sm font-medium text-gray-700">Email Address (email of the institution you need reference to be sent to)</label>
                            <input type="email" id="reference_email" name="reference_email" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" placeholder="Enter email address of recipient">
                            <p class="mt-1 text-sm text-gray-500">This is where the reference will be sent. Please ensure this is a valid email address.</p>
                            @error('reference_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div> -->

                        <!-- Document Reference Info -->
                        <div id="document-info" class="mb-4 hidden p-4 bg-blue-50 rounded-md border border-blue-200">
                            <p class="text-sm text-blue-600">
                                Document Reference selected: The lecturer will upload a reference document that you can download from your dashboard.
                            </p>
                        </div>

                        <!-- Email Reference Info -->
                        <div id="email-info" class="mb-4 hidden p-4 bg-blue-50 rounded-md border border-blue-200">
                            <p class="text-sm text-blue-600">
                                Email Reference selected: The reference will be sent directly to the specified email address.
                            </p>
                        </div>

                        <!-- Institution Selection -->
                        <div class="mb-4">
                            <label for="institution" class="text-sm">Select Institution</label>
                            <select id="institution" name="institution_id" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select an Institution</option>
                                @foreach($studentInstitutions as $attended)
                                    <option value="{{ $attended->institution->id }}" {{ old('institution_id') == $attended->institution->id ? 'selected' : '' }}>
                                        {{ $attended->institution->name }} - ({{ $attended->state->name ?? 'No State' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Lecturer Selection -->
                        <div class="mb-4">
                            <label for="lecturer" class="text-sm">Select Lecturer</label>
                            <select id="lecturer" name="lecturer_id" class="block mt-2 w-full border-gray-300 focus:ring-0 focus:border-gray-500" required>
                                <option value="">Select a Lecturer</option>
                            </select>
                        </div>

                        <!-- Request Description -->
                        <div class="mb-4">
                            <label for="reference_description" class="block text-sm font-medium text-gray-700">Request Detail / Description</label>
                            <textarea id="reference_description" name="reference_description" class="w-full p-2 border rounded mt-1" placeholder="Add a note to your reference request..." required>{{ old('reference_description') }}</textarea>
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

    <!-- JavaScript for Dynamic Elements -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let referenceTypeDropdown = document.getElementById("reference_type");
            let emailContainer = document.getElementById("email-input-container");
            let emailInfo = document.getElementById("email-info");
            let documentInfo = document.getElementById("document-info");
            let emailHint = document.getElementById("email-hint");
            let documentHint = document.getElementById("document-hint");
            let emailInput = document.getElementById("reference_email");
            let requestTypeDropdown = document.getElementById("request_type");
            let expressNotification = document.getElementById("express-notification");
            let priceDisplay = document.getElementById("price-display");
            let priceValue = document.getElementById("originalPrice");

            // Store original price for recalculation
            const originalPrice = parseInt(priceValue.value);
            const expressPrice = parseInt(document.getElementById('expressPrice').value);

            // Handle old input values for reference type
            const oldReferenceType = '{{ old("reference_type") }}';
            if (oldReferenceType) {
                if (oldReferenceType === "email") {
                    emailInfo.classList.remove("hidden");
                    documentInfo.classList.add("hidden");
                    emailHint.classList.remove("hidden");
                    documentHint.classList.add("hidden");
                    if (emailInput) {
                        emailInput.setAttribute("required", "required");
                        emailInput.value = '{{ old("reference_email") }}';
                    }
                } else if (oldReferenceType === "document") {
                    emailInfo.classList.add("hidden");
                    documentInfo.classList.remove("hidden");
                    emailHint.classList.add("hidden");
                    documentHint.classList.remove("hidden");
                    if (emailInput) {
                        emailInput.removeAttribute("required");
                        emailInput.value = "";
                    }
                }
            }

            // Handle old input values for request type
            const oldRequestType = '{{ old("request_type") }}';
            if (oldRequestType === "express") {
                expressNotification.classList.remove("hidden");
                priceDisplay.textContent = '₦' + expressPrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Toggle email input and document info based on reference type selection
            referenceTypeDropdown.addEventListener("change", function () {
                if (this.value === "email") {
                    emailInfo.classList.remove("hidden");
                    documentInfo.classList.add("hidden");
                    emailHint.classList.remove("hidden");
                    documentHint.classList.add("hidden");
                    if (emailInput) {
                        emailInput.setAttribute("required", "required");
                        emailInput.value = "";
                    }
                } else if (this.value === "document") {
                    emailInfo.classList.add("hidden");
                    documentInfo.classList.remove("hidden");
                    emailHint.classList.add("hidden");
                    documentHint.classList.remove("hidden");
                    if (emailInput) {
                        emailInput.removeAttribute("required");
                        emailInput.value = "";
                    }
                } else {
                    emailInfo.classList.add("hidden");
                    documentInfo.classList.add("hidden");
                    emailHint.classList.add("hidden");
                    documentHint.classList.add("hidden");
                    if (emailInput) {
                        emailInput.removeAttribute("required");
                        emailInput.value = "";
                    }
                }
            });

            // Update price and show express notification based on request type selection
            requestTypeDropdown.addEventListener("change", function () {
                if (this.value === "express") {
                    expressNotification.classList.remove("hidden");
                    // Update the price display to show express price
                    priceDisplay.textContent = '₦' + expressPrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                } else {
                    expressNotification.classList.add("hidden");
                    // Reset the price display to the original price
                    priceDisplay.textContent = '₦' + originalPrice.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let institutionDropdown = document.getElementById("institution");
            let lecturerDropdown = document.getElementById("lecturer");

            // Handle old input values for lecturer selection
            const oldInstitutionId = '{{ old("institution_id") }}';
            const oldLecturerId = '{{ old("lecturer_id") }}';

            institutionDropdown.addEventListener("change", function () {
                let institutionId = this.value;
                lecturerDropdown.innerHTML = '<option value="">Select a Lecturer</option>'; // Reset dropdown

                if (institutionId) {
                    fetch(`/get-lecturers/${institutionId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Received lecturers data:', data);
                            if (Array.isArray(data)) {
                                                            data.forEach(lecturer => {
                                let option = document.createElement("option");
                                option.value = lecturer.id;
                                
                                // Use the specific institution data
                                let institutionText = lecturer.institution ? 
                                    lecturer.institution.name + (lecturer.institution.position ? ' (' + lecturer.institution.position + ')' : '') : 
                                    'No Institution';
                                
                                option.textContent = lecturer.name + ' - ' + institutionText;
                                
                                // Select the old lecturer if it matches
                                if (oldLecturerId && lecturer.id == oldLecturerId) {
                                    option.selected = true;
                                }
                                
                                lecturerDropdown.appendChild(option);
                            });
                            } else {
                                console.error('Invalid data format received:', data);
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching lecturers:', error);
                        });
                }
            });

            // If there's an old institution selected, trigger the change event to load lecturers
            if (oldInstitutionId) {
                institutionDropdown.value = oldInstitutionId;
                institutionDropdown.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
