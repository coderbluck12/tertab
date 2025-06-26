@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- Left Column: Institution List -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <p class="text-sm text-green-500 mb-4">Note: You can add more than one institution</p>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Uploaded Institutions</h3>

                    @if($institutions->isEmpty())
                        <p class="text-gray-500">No institutions added yet.</p>
                    @else
{{--                        <ul class="space-y-4">--}}
{{--                            @foreach($institutions as $institution)--}}
{{--                                <li class="bg-gray-100 p-4 rounded-lg shadow">--}}
{{--                                    <div class="flex justify-between items-center">--}}
{{--                                        <div>--}}
{{--                                            <p class="text-sm font-medium text-gray-700">{{ $institution->name }}</p>--}}
{{--                                            <p class="text-xs text-gray-500">{{ $institution->type }}</p>--}}
{{--                                        </div>--}}
{{--                                        <a href="#" class="text-indigo-600 text-sm hover:underline">View</a>--}}
{{--                                    </div>--}}

{{--                                    <!-- Supporting Documents -->--}}
{{--                                    @if($institution->documents->isNotEmpty())--}}
{{--                                        <div class="mt-2">--}}
{{--                                            <p class="text-sm font-semibold">Supporting Documents:</p>--}}
{{--                                            <ul class="mt-1 space-y-2">--}}
{{--                                                @foreach($institution->documents as $document)--}}
{{--                                                    <li>--}}
{{--                                                        <a href="{{ asset('storage/' . $document->path) }}" target="_blank" class="text-blue-600 hover:underline">--}}
{{--                                                            {{ $document->name }}--}}
{{--                                                        </a>--}}
{{--                                                    </li>--}}
{{--                                                @endforeach--}}
{{--                                            </ul>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                </li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}
                        <div x-data="{ open: false, institution: {} }">
                            <ul class="space-y-4">
                                @forelse($institutions as $institution)
                                    <li class="bg-white p-4 rounded-lg shadow-md border border-gray-200">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <p class="text-lg font-semibold text-gray-800">{{ $institution->institution->name }}</p>
                                                @can('request-for-reference')
                                                    <p class="text-sm text-gray-600">{{ ucfirst($institution->type) }} - {{ ucfirst($institution->field_of_study) }}</p>
                                                @endcan
                                                @can('provide-a-reference')
                                                    <p class="text-sm text-gray-600">{{ ucfirst($institution->position) }}</p>
                                                @endcan
                                            </div>

                                            <!-- View Button -->
{{--                                            <button onclick="showInstitutionDetails({{ $institution->id }})"--}}
{{--                                                    class="text-indigo-600 text-sm font-medium hover:underline">--}}
{{--                                                View--}}
{{--                                            </button>--}}

                                            <!-- View Button -->
{{--                                            <button @click="institution = {{ $institution->toJson() }}; open = true"--}}
{{--                                                    class="text-indigo-600 text-sm font-medium hover:underline">--}}
{{--                                                View--}}
{{--                                            </button>--}}
                                        </div>

                                        <!-- Supporting Documents -->
                                        @if($institution->documents->isNotEmpty())
                                            <div class="mt-3">
                                                <p class="text-sm font-semibold text-gray-700">Supporting Documents:</p>
                                                <ul class="mt-1 space-y-1">
                                                    @foreach($institution->documents as $document)
                                                        <li class="flex items-center gap-2">

                                                            <a href="{{ asset('storage/' . $document->path) }}" target="_blank"
                                                               class="text-blue-600 text-sm hover:underline">
                                                                {{ $document->name }}
                                                                <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor"
                                                                     stroke-width="2" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                          d="M14 4v6h6m-2-2L10 16m4-8H4v14h16V10l-6-6z" />
                                                                </svg>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <p class="text-sm text-gray-500 mt-2">No supporting documents uploaded.</p>
                                        @endif
                                    </li>
                                @empty
                                    <li class="text-center text-gray-500 py-6">
                                        <p>No institutions added yet.</p>
                                    </li>
                                @endforelse
                            </ul>

                            <!-- Modal -->
                            <div x-show="open" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4">
                                <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full relative">
                                    <!-- Close Button -->
                                    <button @click="open = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                                        &times;
                                    </button>

                                    <h2 class="text-xl font-semibold text-gray-800 mb-4" x-text="institution.name"></h2>
                                    <p class="text-sm text-gray-600">Type: <span x-text="institution.type"></span></p>

                                    <!-- Supporting Documents -->
                                    <template x-if="institution.documents && institution.documents.length">
                                        <div class="mt-4">
                                            <p class="text-sm font-semibold text-gray-700">Supporting Documents:</p>
                                            <ul class="mt-2 space-y-2">
                                                <template x-for="doc in institution.documents">
                                                    <li class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-gray-800" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 4v6h6m-2-2L10 16m4-8H4v14h16V10l-6-6z" />
                                                        </svg>
                                                        <a :href="'/storage/' + doc.path" target="_blank" class="text-gray-800 text-sm hover:underline" x-text="doc.name"></a>
                                                    </li>
                                                </template>
                                            </ul>
                                        </div>
                                    </template>

                                    <!-- No Documents -->
                                    <p x-show="!institution.documents || institution.documents.length === 0" class="text-sm text-gray-500 mt-2">
                                        No supporting documents uploaded.
                                    </p>

                                    <!-- Close Modal Button -->
                                    <div class="mt-6 flex justify-end">
                                        <button @click="open = false" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- JavaScript for View Button -->
{{--                        <script>--}}
{{--                            function showInstitutionDetails(id) {--}}
{{--                                alert("Show details for institution ID: " + id);--}}
{{--                                // Replace this with a modal or dynamic content loading--}}
{{--                            }--}}
{{--                        </script>--}}

                    @endif
                </div>

                <!-- Right Column: Institution Form -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Institution</h3>
                    <form method="POST" action="{{ route('institution.attended.store') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('post')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- State Selection -->
                            <div>
                                <x-input-label for="state" :value="__('State of Institution')" />
                                <select id="state_id" name="state" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">{{ __('Select State') }}</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Institution Selection -->
                            <div>
                                <x-input-label for="institution" :value="__('Institution Name')" />
                                <select id="institution_id" name="institution" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-indigo-500" required>
                                    <option value="">{{ __('Select Institution') }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Institution Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Type</label>
                                <select name="type" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-indigo-500" required>
                                    @can('request-for-reference')
                                        <option value="">Select Institution Type</option>
                                        <option value="undergraduate">Undergraduate</option>
                                        <option value="postgraduate">Postgraduate</option>
                                    @endcan
                                    @can('provide-a-reference')
                                        <option value="lecturer" selected>Lecturer</option>
                                    @endcan
{{--                                    <option value="course">Course</option>--}}

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
                                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
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
                                <input type="date" name="start_date" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">End Date (optional)</label>
                                <input type="date" name="end_date" class="w-full p-2 border rounded-md focus:ring-2 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Supporting Documents -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Proof of Enrolment/Employment</label>
                            
                            <!-- File Upload Area -->
                            <div class="relative">
                                <input type="file" name="documents[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" 
                                       id="document-upload" 
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-gray-400 transition-colors">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700">
                                            <span class="text-blue-600 hover:text-blue-500">Click to upload</span> or drag and drop
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            PDF, JPG, JPEG, PNG, DOC, DOCX (max 2MB each)
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selected Files Display -->
                            <div id="selected-files" class="mt-3 space-y-2 hidden">
                                <p class="text-sm font-medium text-gray-700">Selected Files:</p>
                                <div id="file-list" class="space-y-2"></div>
                            </div>
                            
                            <p class="mt-2 text-sm text-gray-500">
                                You can upload multiple documents. Supported formats: PDF, JPG, JPEG, PNG, DOC, DOCX. 
                                Maximum file size: 2MB per file.
                            </p>
                        </div>

                        <!-- Submit Button -->
                        <div>
                            <x-primary-button>Add Institution</x-primary-button>
                        </div>
{{--                        <div>--}}
{{--                            <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md shadow hover:bg-indigo-700">--}}
{{--                                Add Institution--}}
{{--                            </button>--}}
{{--                        </div>--}}
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Dynamic Institution Fetching -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stateSelect = document.getElementById('state_id');
            const institutionSelect = document.getElementById('institution_id');
            
            if (!stateSelect || !institutionSelect) {
                console.error('Could not find state or institution select elements');
                return;
            }
            
            function loadInstitutions(stateId = null) {
                // Reset institution dropdown
                institutionSelect.innerHTML = '<option value="">Select Institution</option>';
                
                // Construct URL with correct path
                const baseUrl = '/institutions-by-state';
                const url = stateId ? `${baseUrl}/${stateId}` : baseUrl;
                
                console.log('Fetching institutions from:', url);
                
                fetch(url)
                    .then(response => {
                        console.log('Response status:', response.status);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Received data:', data);
                        if (Array.isArray(data)) {
                            data.forEach(institution => {
                                const option = document.createElement('option');
                                option.value = institution.id;
                                option.textContent = institution.name;
                                institutionSelect.appendChild(option);
                            });
                        } else {
                            console.error('Invalid data format received:', data);
                            institutionSelect.innerHTML = '<option value="">Error: Invalid data format</option>';
                        }
                    })
                    .catch(error => {
                        console.error('Error loading institutions:', error);
                        institutionSelect.innerHTML = '<option value="">Error loading institutions</option>';
                    });
            }
            
            // Load all institutions on page load
            loadInstitutions();
            
            // Load institutions when state changes
            stateSelect.addEventListener('change', function() {
                const stateId = this.value;
                loadInstitutions(stateId);
            });
        });
    </script>

    <!-- JavaScript for File Upload -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('document-upload');
            const selectedFilesDiv = document.getElementById('selected-files');
            const fileListDiv = document.getElementById('file-list');
            const uploadArea = fileInput.parentElement.querySelector('.border-dashed');
            
            if (!fileInput) return;
            
            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                displaySelectedFiles(e.target.files);
            });
            
            // Drag and drop functionality
            uploadArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                uploadArea.classList.add('border-blue-400', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
            });
            
            uploadArea.addEventListener('drop', function(e) {
                e.preventDefault();
                uploadArea.classList.remove('border-blue-400', 'bg-blue-50');
                
                const files = e.dataTransfer.files;
                fileInput.files = files;
                displaySelectedFiles(files);
            });
            
            function displaySelectedFiles(files) {
                if (files.length === 0) {
                    selectedFilesDiv.classList.add('hidden');
                    return;
                }
                
                fileListDiv.innerHTML = '';
                selectedFilesDiv.classList.remove('hidden');
                
                Array.from(files).forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border';
                    
                    const fileInfo = document.createElement('div');
                    fileInfo.className = 'flex items-center space-x-3';
                    
                    // File icon based on type
                    const icon = document.createElement('div');
                    icon.className = 'flex-shrink-0';
                    icon.innerHTML = getFileIcon(file.type);
                    
                    const fileDetails = document.createElement('div');
                    fileDetails.className = 'flex-1 min-w-0';
                    
                    const fileName = document.createElement('p');
                    fileName.className = 'text-sm font-medium text-gray-700 truncate';
                    fileName.textContent = file.name;
                    
                    const fileSize = document.createElement('p');
                    fileSize.className = 'text-xs text-gray-500';
                    fileSize.textContent = formatFileSize(file.size);
                    
                    fileDetails.appendChild(fileName);
                    fileDetails.appendChild(fileSize);
                    
                    fileInfo.appendChild(icon);
                    fileInfo.appendChild(fileDetails);
                    
                    // Remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.type = 'button';
                    removeBtn.className = 'text-red-500 hover:text-red-700 text-sm font-medium';
                    removeBtn.textContent = 'Remove';
                    removeBtn.onclick = function() {
                        removeFile(index);
                    };
                    
                    fileItem.appendChild(fileInfo);
                    fileItem.appendChild(removeBtn);
                    fileListDiv.appendChild(fileItem);
                });
            }
            
            function removeFile(index) {
                const dt = new DataTransfer();
                const files = fileInput.files;
                
                for (let i = 0; i < files.length; i++) {
                    if (i !== index) {
                        dt.items.add(files[i]);
                    }
                }
                
                fileInput.files = dt.files;
                displaySelectedFiles(dt.files);
            }
            
            function getFileIcon(mimeType) {
                if (mimeType.includes('pdf')) {
                    return '<svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12V6l-4-4H4v16zm2-2V4h6v2H6v12z"/></svg>';
                } else if (mimeType.includes('image')) {
                    return '<svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/></svg>';
                } else if (mimeType.includes('word') || mimeType.includes('document')) {
                    return '<svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12V6l-4-4H4v16zm2-2V4h6v2H6v12z"/></svg>';
                } else {
                    return '<svg class="w-8 h-8 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path d="M4 18h12V6l-4-4H4v16zm2-2V4h6v2H6v12z"/></svg>';
                }
            }
            
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }
        });
    </script>
@endsection
