@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div id="success-message" class="bg-green-500 text-white p-4 rounded-md shadow-md mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div id="error-message" class="bg-red-500 text-white p-4 rounded-md shadow-md mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Institution</h3>
                    <a href="{{ route('institution.attended.show') }}" class="text-blue-600 hover:underline">
                        ← Back to Institutions
                    </a>
                </div>

                <form method="POST" action="{{ route('institution.attended.update', $institutionAttended) }}" enctype="multipart/form-data" id="institution-form">
                    @csrf
                    @method('PUT')

                    <!-- State -->
                    <div class="mb-4">
                        <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                        <select name="state" id="state" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select State</option>
                            @foreach($states as $state)
                                <option value="{{ $state->id }}" {{ $institutionAttended->state_id == $state->id ? 'selected' : '' }}>
                                    {{ $state->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('state')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Institution -->
                    <div class="mb-4">
                        <label for="institution" class="block text-sm font-medium text-gray-700 mb-2">Institution</label>
                        <select name="institution" id="institution" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Institution</option>
                            <option value="{{ $institutionAttended->institution_id }}" selected>
                                {{ $institutionAttended->institution->name }}
                            </option>
                        </select>
                        @error('institution')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                        <select name="type" id="type" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Select Type</option>
                            @can('request-for-reference')
                                <option value="student" {{ $institutionAttended->type == 'student' ? 'selected' : '' }}>Student</option>
                            @endcan
                            @can('provide-a-reference')
                                <option value="lecturer" {{ $institutionAttended->type == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                            @endcan
                        </select>
                        @error('type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Course or Position -->
                    <div class="mb-4">
                        <label for="field_of_study" class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="course-label">Course/Field of Study</span>
                        </label>
                        <select name="field_of_study" id="field_of_study" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Course</option>
                            <option value="Computer Science" {{ $institutionAttended->field_of_study == 'Computer Science' ? 'selected' : '' }}>Computer Science</option>
                            <option value="Engineering" {{ $institutionAttended->field_of_study == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                            <option value="Medicine" {{ $institutionAttended->field_of_study == 'Medicine' ? 'selected' : '' }}>Medicine</option>
                            <option value="Law" {{ $institutionAttended->field_of_study == 'Law' ? 'selected' : '' }}>Law</option>
                            <option value="Business Administration" {{ $institutionAttended->field_of_study == 'Business Administration' ? 'selected' : '' }}>Business Administration</option>
                            <option value="Economics" {{ $institutionAttended->field_of_study == 'Economics' ? 'selected' : '' }}>Economics</option>
                            <option value="Psychology" {{ $institutionAttended->field_of_study == 'Psychology' ? 'selected' : '' }}>Psychology</option>
                            <option value="Education" {{ $institutionAttended->field_of_study == 'Education' ? 'selected' : '' }}>Education</option>
                            <option value="Other" {{ !in_array($institutionAttended->field_of_study, ['Computer Science', 'Engineering', 'Medicine', 'Law', 'Business Administration', 'Economics', 'Psychology', 'Education']) && $institutionAttended->field_of_study ? 'selected' : '' }}>Other</option>
                        </select>
                        
                        <!-- Custom course input (shown when "Other" is selected) -->
                        <input type="text" id="custom_course" name="custom_field_of_study" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 mt-2 hidden" 
                               placeholder="Enter your course/field of study"
                               value="{{ !in_array($institutionAttended->field_of_study, ['Computer Science', 'Engineering', 'Medicine', 'Law', 'Business Administration', 'Economics', 'Psychology', 'Education']) ? $institutionAttended->field_of_study : '' }}">
                        
                        @error('field_of_study')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position (for lecturers) -->
                    <div class="mb-4" id="position-field" style="display: {{ $institutionAttended->type == 'lecturer' ? 'block' : 'none' }};">
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                        <input type="text" name="position" id="position" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                               placeholder="e.g., Professor, Associate Professor, Lecturer"
                               value="{{ old('position', $institutionAttended->position) }}">
                        @error('position')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- School Email for Lecturers -->
                    @can('provide-a-reference')
                        <div class="mb-4" id="school-email-field" style="display: {{ $institutionAttended->type == 'lecturer' ? 'block' : 'none' }};">
                            <label for="school_email" class="block text-sm font-medium text-gray-700 mb-2">School Email <span class="text-red-500">*</span></label>
                            <input type="email" name="school_email" id="school_email" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="your.email@university.edu"
                                   value="{{ old('school_email', $institutionAttended->school_email) }}">
                            <p class="text-sm text-gray-600 mt-1">Must be a valid .edu or .edu.ng email address</p>
                            @error('school_email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endcan

                    <!-- Start Date -->
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" id="start_date" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('start_date', $institutionAttended->start_date ? $institutionAttended->start_date->format('Y-m-d') : '') }}">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- End Date -->
                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">End Date (Optional)</label>
                        <input type="date" name="end_date" id="end_date" 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               value="{{ old('end_date', $institutionAttended->end_date ? $institutionAttended->end_date->format('Y-m-d') : '') }}">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Current Documents -->
                    @if($institutionAttended->documents->isNotEmpty())
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Supporting Documents</label>
                            <div class="space-y-2">
                                @foreach($institutionAttended->documents as $document)
                                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded-md" id="document-{{ $document->id }}">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                            </svg>
                                            <a href="{{ asset('storage/' . $document->path) }}" target="_blank" class="text-blue-600 hover:underline">
                                                {{ $document->name }}
                                            </a>
                                        </div>
                                        <button type="button" onclick="deleteDocument({{ $document->id }})" 
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            Delete
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Add New Documents -->
                    <div class="mb-4">
                        <label for="document-upload" class="block text-sm font-medium text-gray-700 mb-2">Add Supporting Documents (Optional)</label>
                        <input type="file" name="documents[]" id="document-upload" multiple 
                               class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <p class="text-sm text-gray-600 mt-1">Accepted formats: PDF, JPG, PNG, DOC, DOCX (Max: 2MB each)</p>
                        @error('documents.*')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <!-- Selected Files Display -->
                        <div id="selected-files" class="mt-2 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Selected Files:</p>
                            <div id="file-list" class="space-y-1"></div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('institution.attended.show') }}" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md shadow hover:bg-blue-700">
                            Update Institution
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stateSelect = document.getElementById('state');
            const institutionSelect = document.getElementById('institution');
            const typeSelect = document.getElementById('type');
            const positionField = document.getElementById('position-field');
            const schoolEmailField = document.getElementById('school-email-field');
            const fieldOfStudySelect = document.getElementById('field_of_study');
            const customCourseInput = document.getElementById('custom_course');
            const fileInput = document.getElementById('document-upload');
            const selectedFilesDiv = document.getElementById('selected-files');
            const fileListDiv = document.getElementById('file-list');

            // Handle state change to load institutions
            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                institutionSelect.innerHTML = '<option value="">Loading...</option>';
                
                if (stateId) {
                    fetch(`/tertab/public/institutions-by-state/${stateId}`)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            institutionSelect.innerHTML = '<option value="">Select Institution</option>';
                            if (data && data.length > 0) {
                                data.forEach(institution => {
                                    const option = document.createElement('option');
                                    option.value = institution.id;
                                    option.textContent = institution.name;
                                    institutionSelect.appendChild(option);
                                });
                            } else {
                                institutionSelect.innerHTML = '<option value="">No institutions found for this state</option>';
                            }
                        })
                        .catch(error => {
                            console.error('Error loading institutions:', error);
                            institutionSelect.innerHTML = '<option value="">Error loading institutions</option>';
                        });
                } else {
                    institutionSelect.innerHTML = '<option value="">Select Institution</option>';
                }
            });

            // Handle type change
            typeSelect.addEventListener('change', function() {
                const type = this.value;
                if (type === 'lecturer') {
                    positionField.style.display = 'block';
                    schoolEmailField.style.display = 'block';
                } else {
                    positionField.style.display = 'none';
                    schoolEmailField.style.display = 'none';
                }
            });

            // Handle course selection
            fieldOfStudySelect.addEventListener('change', function() {
                if (this.value === 'Other') {
                    customCourseInput.style.display = 'block';
                    customCourseInput.required = true;
                } else {
                    customCourseInput.style.display = 'none';
                    customCourseInput.required = false;
                    customCourseInput.value = '';
                }
            });

            // Show custom course input if "Other" is already selected
            if (fieldOfStudySelect.value === 'Other') {
                customCourseInput.style.display = 'block';
                customCourseInput.required = true;
            }

            // Handle file selection
            fileInput.addEventListener('change', function() {
                const files = Array.from(this.files);
                
                if (files.length > 0) {
                    selectedFilesDiv.classList.remove('hidden');
                    fileListDiv.innerHTML = '';
                    
                    files.forEach((file, index) => {
                        const fileDiv = document.createElement('div');
                        fileDiv.className = 'flex items-center justify-between bg-gray-100 p-2 rounded text-sm';
                        fileDiv.innerHTML = `
                            <span>${file.name}</span>
                            <button type="button" onclick="removeFile(${index})" class="text-red-600 hover:text-red-800">Remove</button>
                        `;
                        fileListDiv.appendChild(fileDiv);
                    });
                } else {
                    selectedFilesDiv.classList.add('hidden');
                }
            });

            // Form submission handler for custom course
            document.getElementById('institution-form').addEventListener('submit', function(e) {
                const fieldOfStudy = fieldOfStudySelect.value;
                const customCourse = customCourseInput.value;
                
                if (fieldOfStudy === 'Other' && customCourse) {
                    // Create a hidden input with the custom course value
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'field_of_study';
                    hiddenInput.value = customCourse;
                    this.appendChild(hiddenInput);
                    
                    // Remove the name attribute from the select to avoid conflict
                    fieldOfStudySelect.removeAttribute('name');
                }
            });
        });

        function removeFile(index) {
            const fileInput = document.getElementById('document-upload');
            const dt = new DataTransfer();
            const files = Array.from(fileInput.files);
            
            files.forEach((file, i) => {
                if (i !== index) {
                    dt.items.add(file);
                }
            });
            
            fileInput.files = dt.files;
            fileInput.dispatchEvent(new Event('change'));
        }

        function deleteDocument(documentId) {
            if (confirm('Are you sure you want to delete this document?')) {
                fetch(`{{ route('institution.attended.show') }}/${{{ $institutionAttended->id }}}/document/${documentId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`document-${documentId}`).remove();
                    } else {
                        alert('Failed to delete document: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the document.');
                });
            }
        }
    </script>
@endsection
