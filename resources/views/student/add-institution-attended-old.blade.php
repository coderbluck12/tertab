<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Institution') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <!-- Institution Form -->
                <div class="bg-white p-6 border border-gray-300 rounded-lg shadow-sm">
                    <form method="POST" action="{{ route('student.institution.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- State Selection -->
                            <div class="mt-4">
                                <x-input-label for="state" :value="__('State of Institution')" />
                                <select id="state" name="state" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">{{ __('Select State') }}</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('state')" class="mt-2" />
                            </div>

                            <!-- Institution Selection -->
                            <div class="mt-4">
                                <x-input-label for="institution" :value="__('Institution Name')" />
                                <select id="institution" name="institution" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">{{ __('Select Institution') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('institution')" class="mt-2" />
                            </div>
                        </div>


                        <!-- Institution Name -->
                        {{--                        <div>--}}
                        {{--                            <label class="block text-sm font-medium text-gray-700">Institution Name</label>--}}
                        {{--                            <input type="text" name="name" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" required>--}}
                        {{--                        </div>--}}

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Institution Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500" required>
                                    <option value="">Select Institution Type</option>
                                    <option value="undergraduate">Undergraduate</option>
                                    <option value="postgraduate">Postgraduate</option>
                                    <option value="course">Course</option>
                                    <option value="lecturer">Lecturer</option>
                                </select>
                            </div>

                            <!-- Course or Position -->
                            <div>
                                @can('request-for-reference')
                                    <label for="field_of_study" class="block text-sm font-medium text-gray-700">Field of Study</label>
                                    <select id="field_of_study" name="field_of_study" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Field of Study</option>
                                        <optgroup label="Science & Technology">
                                            <option value="Computer Science">Computer Science</option>
                                            <option value="Data Science">Data Science</option>
                                            <option value="Cybersecurity">Cybersecurity</option>
                                            <option value="Mechanical Engineering">Mechanical Engineering</option>
                                            <option value="Civil Engineering">Civil Engineering</option>
                                        </optgroup>
                                        <optgroup label="Business & Economics">
                                            <option value="Business Administration">Business Administration</option>
                                            <option value="Marketing">Marketing</option>
                                            <option value="Finance">Finance</option>
                                            <option value="Economics">Economics</option>
                                        </optgroup>
                                        <optgroup label="Medical & Health Sciences">
                                            <option value="Medicine & Surgery">Medicine & Surgery</option>
                                            <option value="Nursing">Nursing</option>
                                            <option value="Pharmacy">Pharmacy</option>
                                        </optgroup>
                                        <optgroup label="Arts & Humanities">
                                            <option value="English Literature">English Literature</option>
                                            <option value="Philosophy">Philosophy</option>
                                            <option value="Journalism & Media Studies">Journalism & Media Studies</option>
                                        </optgroup>
                                    </select>
                                @endcan

                                @can('provide-a-reference')
                                    <label for="position" class="block text-sm font-medium text-gray-700 mt-4">Position</label>
                                    <select id="position" name="position" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Position</option>
                                        <optgroup label="Professorship Ranks">
                                            <option value="Assistant Lecturer">Assistant Lecturer</option>
                                            <option value="Lecturer I">Lecturer I</option>
                                            <option value="Lecturer II">Lecturer II</option>
                                            <option value="Senior Lecturer">Senior Lecturer</option>
                                            <option value="Associate Professor">Associate Professor</option>
                                            <option value="Professor">Professor</option>
                                        </optgroup>
                                        <optgroup label="Research & Administration">
                                            <option value="Research Fellow">Research Fellow</option>
                                            <option value="Teaching Assistant">Teaching Assistant</option>
                                            <option value="Department Head">Department Head</option>
                                            <option value="Dean">Dean</option>
                                        </optgroup>
                                        <optgroup label="Specialized Roles">
                                            <option value="Adjunct Lecturer">Adjunct Lecturer</option>
                                            <option value="Visiting Lecturer">Visiting Lecturer</option>
                                            <option value="Clinical Instructor">Clinical Instructor</option>
                                        </optgroup>
                                    </select>
                                @endcan
                            </div>

                        </div>



                        <!-- Duration -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date @can('provide-a-reference')(optional)@endcan</label>
                                <input type="date" name="end_date" class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- Supporting Documents -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Supporting Documents</label>
                            <input type="file" name="documents[]" multiple class="w-full p-2 border rounded focus:ring-2 focus:ring-blue-500">
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <x-primary-button>Add Institution</x-primary-button>
                        </div>
                        {{--                        <div>--}}
                        {{--                            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Submit</button>--}}
                        {{--                        </div>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Institution Fetching -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let stateDropdown = document.getElementById("state");
            let institutionDropdown = document.getElementById("institution");

            stateDropdown.addEventListener("change", function () {
                let stateId = this.value;
                institutionDropdown.innerHTML = '<option value="">Select Institution</option>'; // Reset

                if (stateId) {
                    fetch(`/institutions-by-state/${stateId}`)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(institution => {
                                let option = document.createElement("option");
                                option.value = institution.id;
                                option.textContent = institution.name;
                                institutionDropdown.appendChild(option);
                            });
                        })
                        .catch(error => console.error("Error fetching institutions:", error));
                }
            });
        });
    </script>
</x-app-layout>
