@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div id="success-message" class="bg-green-500 text-white p-4 rounded-md shadow-md mb-4">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('success-message').style.display = 'none';
                    }, 5000);
                </script>
            @endif

            @if(session('error'))
                <div id="error-message" class="bg-red-500 text-white p-4 rounded-md shadow-md mb-4">
                    {{ session('error') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('error-message').style.display = 'none';
                    }, 5000);
                </script>
            @endif

            <!-- Page Header -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Add Institution</h1>
                        <p class="text-gray-600 mt-1">Add a new institution you've attended or are currently attending</p>
                    </div>
                    <a href="{{ route('institution.attended.list') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        View My Institutions
                    </a>
                </div>
            </div>

            <!-- Institution Form -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <form method="POST" action="{{ route('institution.attended.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- State Selection -->
                        <div>
                            <label for="state" class="block text-sm font-medium text-gray-700">State</label>
                            <select id="state" name="state" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Institution Selection -->
                        <div>
                            <label for="institution" class="block text-sm font-medium text-gray-700">Institution</label>
                            <select id="institution" name="institution" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select Institution</option>
                            </select>
                            @error('institution')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type Selection -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                                <option value="">Select Type</option>
                                @can('request-for-reference')
                                    <option value="undergraduate" {{ old('type') == 'undergraduate' ? 'selected' : '' }}>Undergraduate</option>
                                    <option value="postgraduate" {{ old('type') == 'postgraduate' ? 'selected' : '' }}>Postgraduate</option>
                                @endcan
                                @can('provide-a-reference')
                                    <option value="lecturer" {{ old('type') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                                @endcan
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Field of Study (for students) -->
                        @can('request-for-reference')
                        <div>
                            <label for="field_of_study" class="block text-sm font-medium text-gray-700">Field of Study</label>
                            <input type="text" 
                                   id="field_of_study" 
                                   name="field_of_study" 
                                   value="{{ old('field_of_study') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="e.g., Computer Science">
                            @error('field_of_study')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endcan

                        <!-- Position (for lecturers) -->
                        @can('provide-a-reference')
                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                            <select id="position" name="position" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Select Position</option>
                                <optgroup label="Professorship Ranks">
                                    <option value="Assistant Lecturer" {{ old('position') == 'Assistant Lecturer' ? 'selected' : '' }}>Assistant Lecturer</option>
                                    <option value="Lecturer I" {{ old('position') == 'Lecturer I' ? 'selected' : '' }}>Lecturer I</option>
                                    <option value="Lecturer II" {{ old('position') == 'Lecturer II' ? 'selected' : '' }}>Lecturer II</option>
                                    <option value="Senior Lecturer" {{ old('position') == 'Senior Lecturer' ? 'selected' : '' }}>Senior Lecturer</option>
                                    <option value="Associate Professor" {{ old('position') == 'Associate Professor' ? 'selected' : '' }}>Associate Professor</option>
                                    <option value="Professor" {{ old('position') == 'Professor' ? 'selected' : '' }}>Professor</option>
                                </optgroup>
                                <optgroup label="Research & Administration">
                                    <option value="Research Fellow" {{ old('position') == 'Research Fellow' ? 'selected' : '' }}>Research Fellow</option>
                                    <option value="Teaching Assistant" {{ old('position') == 'Teaching Assistant' ? 'selected' : '' }}>Teaching Assistant</option>
                                    <option value="Department Head" {{ old('position') == 'Department Head' ? 'selected' : '' }}>Department Head</option>
                                    <option value="Dean" {{ old('position') == 'Dean' ? 'selected' : '' }}>Dean</option>
                                </optgroup>
                                <optgroup label="Specialized Roles">
                                    <option value="Adjunct Lecturer" {{ old('position') == 'Adjunct Lecturer' ? 'selected' : '' }}>Adjunct Lecturer</option>
                                    <option value="Visiting Lecturer" {{ old('position') == 'Visiting Lecturer' ? 'selected' : '' }}>Visiting Lecturer</option>
                                    <option value="Clinical Instructor" {{ old('position') == 'Clinical Instructor' ? 'selected' : '' }}>Clinical Instructor</option>
                                </optgroup>
                            </select>
                            @error('position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @endcan
                    </div>

                    <!-- School Email for Lecturers -->
                    @can('provide-a-reference')
                    <div>
                        <label for="school_email" class="block text-sm font-medium text-gray-700">School Email Address</label>
                        <input type="email" 
                               id="school_email" 
                               name="school_email" 
                               value="{{ old('school_email') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" 
                               placeholder="your.email@university.edu"
                               pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.(edu|edu\.ng)$"
                               title="Please enter a valid .edu or .edu.ng email address">
                        <p class="text-sm text-gray-500 mt-1">Please use your official school email address (.edu or .edu.ng domain)</p>
                        @error('school_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endcan

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" 
                                   id="start_date" 
                                   name="start_date" 
                                   value="{{ old('start_date') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date (Optional)</label>
                            <input type="date" 
                                   id="end_date" 
                                   name="end_date" 
                                   value="{{ old('end_date') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Leave empty if currently attending</p>
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>



                     <!-- Document Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Enrolment</label>
                        <div class="relative">
                            <input type="file" 
                                   id="document-upload" 
                                   multiple 
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600">Click to upload or drag and drop</p>
                                    <p class="text-xs text-gray-500">PDF, JPG, PNG, DOC up to 2MB each</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Selected Files Display -->
                        <div id="selected-files" class="mt-3 hidden">
                            <p class="text-sm font-medium text-gray-700 mb-2">Selected files:</p>
                            <div id="file-list" class="space-y-2"></div>
                            <button type="button" 
                                    id="add-more-files" 
                                    class="mt-2 text-sm text-blue-600 hover:text-blue-800 font-medium">
                                + Add more files
                            </button>
                        </div>
                        
                        <!-- Hidden file inputs for form submission -->
                        <div id="hidden-file-inputs"></div>
                        
                        @error('documents.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('institution.attended.index') }}" 
                           class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                            Add Institution
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
            const fileInput = document.getElementById('document-upload');
            const selectedFilesDiv = document.getElementById('selected-files');
            const fileListDiv = document.getElementById('file-list');

            // Handle state change to load institutions
            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                institutionSelect.innerHTML = '<option value="">Select Institution</option>';
                
                if (stateId) {
                    fetch(`/institutions-by-state/${stateId}`)
                        .then(response => response.json())
                        .then(institutions => {
                            institutions.forEach(institution => {
                                const option = document.createElement('option');
                                option.value = institution.id;
                                option.textContent = institution.name;
                                institutionSelect.appendChild(option);
                            });
                        })
                        .catch(error => console.error('Error loading institutions:', error));
                }
            });

            // File management system
            let selectedFiles = [];
            let fileCounter = 0;

            // Handle file upload
            fileInput.addEventListener('change', function() {
                addFiles(this.files);
                this.value = ''; // Reset input to allow selecting same files again
            });

            // Handle "Add more files" button
            document.getElementById('add-more-files').addEventListener('click', function() {
                fileInput.click();
            });

            function addFiles(files) {
                Array.from(files).forEach(file => {
                    // Check file size (2MB limit)
                    if (file.size > 2 * 1024 * 1024) {
                        alert(`File "${file.name}" is too large. Maximum size is 2MB.`);
                        return;
                    }

                    // Check if file already exists
                    if (selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
                        alert(`File "${file.name}" is already selected.`);
                        return;
                    }

                    // Add file to array
                    const fileObj = {
                        file: file,
                        id: fileCounter++,
                        name: file.name,
                        size: file.size
                    };
                    selectedFiles.push(fileObj);
                });

                updateFileDisplay();
                updateHiddenInputs();
            }

            function removeFile(fileId) {
                selectedFiles = selectedFiles.filter(f => f.id !== fileId);
                updateFileDisplay();
                updateHiddenInputs();
            }

            function updateFileDisplay() {
                const fileListDiv = document.getElementById('file-list');
                const selectedFilesDiv = document.getElementById('selected-files');

                if (selectedFiles.length === 0) {
                    selectedFilesDiv.classList.add('hidden');
                    return;
                }

                selectedFilesDiv.classList.remove('hidden');
                fileListDiv.innerHTML = '';

                selectedFiles.forEach(fileObj => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between bg-gray-50 p-3 rounded-lg border';
                    fileItem.innerHTML = `
                        <div class="flex items-center space-x-3">
                            <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-gray-700">${fileObj.name}</p>
                                <p class="text-xs text-gray-500">${(fileObj.size / 1024 / 1024).toFixed(2)} MB</p>
                            </div>
                        </div>
                        <button type="button" 
                                onclick="removeFile(${fileObj.id})" 
                                class="text-red-600 hover:text-red-800 p-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    `;
                    fileListDiv.appendChild(fileItem);
                });
            }

            function updateHiddenInputs() {
                const hiddenInputsDiv = document.getElementById('hidden-file-inputs');
                hiddenInputsDiv.innerHTML = '';

                selectedFiles.forEach((fileObj, index) => {
                    const input = document.createElement('input');
                    input.type = 'file';
                    input.name = `documents[${index}]`;
                    input.style.display = 'none';
                    
                    // Create a new FileList with just this file
                    const dt = new DataTransfer();
                    dt.items.add(fileObj.file);
                    input.files = dt.files;
                    
                    hiddenInputsDiv.appendChild(input);
                });
            }

            // Make removeFile function global
            window.removeFile = removeFile;

            // Trigger type change on page load if there's an old value
            if (typeSelect.value) {
                typeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endsection
