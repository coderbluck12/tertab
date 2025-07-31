@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Add Institution Form -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add New Institution</h3>
                    
                    <form method="POST" action="{{ route('admin.institutions.store') }}">
                        @csrf
                        
                        <!-- Institution Name -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Institution Name</label>
                            <input type="text" name="name" id="name" 
                                   class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" 
                                   placeholder="e.g., University of Lagos"
                                   value="{{ old('name') }}" required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- State -->
                        <div class="mb-4">
                            <label for="state_id" class="block text-sm font-medium text-gray-700 mb-2">State</label>
                            <select name="state_id" id="state_id" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select State</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ownership -->
                        <div class="mb-4">
                            <label for="ownership" class="block text-sm font-medium text-gray-700 mb-2">Ownership</label>
                            <select name="ownership" id="ownership" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Select Ownership</option>
                                <option value="federal" {{ old('ownership') == 'federal' ? 'selected' : '' }}>Federal</option>
                                <option value="state" {{ old('ownership') == 'state' ? 'selected' : '' }}>State</option>
                                <option value="private" {{ old('ownership') == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                            @error('ownership')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md shadow hover:bg-blue-700">
                            Add Institution
                        </button>
                    </form>
                </div>

                <!-- Right Column: Institution List -->
                <div class="bg-white shadow-sm rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Existing Institutions</h3>
                        <div class="flex space-x-2">
                            <select id="state-filter" class="border border-gray-300 rounded-md px-3 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All States</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}">{{ $state->name }}</option>
                                @endforeach
                            </select>
                            <button onclick="loadInstitutions()" class="bg-blue-600 text-white px-3 py-1 rounded-md text-sm hover:bg-blue-700">
                                Refresh
                            </button>
                        </div>
                    </div>
                    
                    <div class="max-h-96 overflow-y-auto">
                        <div id="institutions-loading" class="text-center py-4">
                            <p class="text-gray-500">Loading institutions...</p>
                        </div>
                        <div id="institutions-container" class="space-y-3" style="display: none;">
                            <!-- Institutions will be loaded here via AJAX -->
                        </div>
                        <div id="institutions-empty" class="text-center py-4" style="display: none;">
                            <p class="text-gray-500">No institutions found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadInstitutions() {
            const stateId = document.getElementById('state-filter').value;
            const institutionsContainer = document.getElementById('institutions-container');
            const institutionsLoading = document.getElementById('institutions-loading');
            const institutionsEmpty = document.getElementById('institutions-empty');

            institutionsContainer.style.display = 'none';
            institutionsLoading.style.display = 'block';
            institutionsEmpty.style.display = 'none';

            const url = stateId ? `/institutions-by-state/${stateId}` : '/institutions-by-state';
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    institutionsContainer.innerHTML = '';
                    if (data.length === 0) {
                        institutionsEmpty.style.display = 'block';
                    } else {
                        data.forEach(institution => {
                            const stateName = institution.state ? institution.state.name : 'N/A';
                            const institutionHtml = `
                                <div class="bg-gray-50 p-4 rounded-lg border">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-800">${institution.name}</h4>
                                            <p class="text-sm text-gray-600">${stateName}</p>
                                            <p class="text-xs text-gray-500 capitalize">${institution.ownership}</p>
                                        </div>
                                        <div class="flex space-x-2">
                                            <button onclick="editInstitution(${institution.id})" class="text-blue-600 hover:text-blue-800 text-sm">
                                                Edit
                                            </button>
                                            <button onclick="deleteInstitution(${institution.id}, '${institution.name}')" class="text-red-600 hover:text-red-800 text-sm">
                                                Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            `;
                            institutionsContainer.innerHTML += institutionHtml;
                        });
                        institutionsContainer.style.display = 'block';
                    }
                    institutionsLoading.style.display = 'none';
                })
                .catch(error => {
                    console.error('Error loading institutions:', error);
                    institutionsLoading.style.display = 'none';
                    institutionsEmpty.innerHTML = '<p class="text-red-500">Error loading institutions. Please try again.</p>';
                    institutionsEmpty.style.display = 'block';
                });
        }

        function editInstitution(id) {
            // Placeholder for edit functionality
            alert('Edit functionality will be implemented soon.');
        }

        function deleteInstitution(id, name) {
            if (confirm(`Are you sure you want to delete "${name}"?`)) {
                // Create a form and submit it for deletion
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/institutions/${id}`;
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                form.appendChild(csrfInput);
                form.appendChild(methodInput);
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Load institutions when page loads
        document.addEventListener('DOMContentLoaded', function() {
            loadInstitutions();
            
            // Add event listener for state filter change
            document.getElementById('state-filter').addEventListener('change', loadInstitutions);
            
            // Reload institutions after form submission
            const addForm = document.querySelector('form[action="{{ route('admin.institutions.store') }}"]');
            if (addForm) {
                addForm.addEventListener('submit', function() {
                    setTimeout(() => {
                        loadInstitutions();
                    }, 1000); // Delay to allow for form processing
                });
            }
        });

        // Auto-hide success/error messages
        setTimeout(() => {
            const successMsg = document.getElementById('success-message');
            const errorMsg = document.getElementById('error-message');
            if (successMsg) successMsg.style.display = 'none';
            if (errorMsg) errorMsg.style.display = 'none';
        }, 5000);
    </script>
@endsection
