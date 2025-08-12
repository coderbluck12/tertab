@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">My Institutions</h1>
                        <p class="text-gray-600 mt-1">View and manage institutions you've attended or are currently attending</p>
                    </div>
                    <a href="{{ route('institution.attended.create') }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Add New Institution
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Institutions</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $institutions->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">As Student</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $institutions->where('type', 'student')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">As Lecturer</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $institutions->where('type', 'lecturer')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Institutions List -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">All Institutions</h3>
                </div>
                
                <div class="p-6">
                    @if($institutions->isEmpty())
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No institutions added</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by adding your first institution.</p>
                            <div class="mt-6">
                                <a href="{{ route('institution.attended.create') }}" 
                                   class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Add Institution
                                </a>
                            </div>
                        </div>
                    @else
                        <div x-data="{ open: false, institution: {} }" class="space-y-6">
                            @foreach($institutions as $institution)
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start">
                                            <div class="flex-1">
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $institution->institution->name }}</h4>
                                                <div class="mt-2 space-y-1">
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $institution->state->name }}
                                                    </div>
                                                    <div class="flex items-center text-sm text-gray-600">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ ucfirst($institution->type) }}
                                                        @if($institution->type === 'student' && $institution->field_of_study)
                                                            - {{ $institution->field_of_study }}
                                                        @elseif($institution->type === 'lecturer' && $institution->position)
                                                            - {{ $institution->position }}
                                                        @endif
                                                    </div>
                                                    @if($institution->start_date)
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            {{ \Carbon\Carbon::parse($institution->start_date)->format('M Y') }} - 
                                                            {{ $institution->end_date ? \Carbon\Carbon::parse($institution->end_date)->format('M Y') : 'Present' }}
                                                        </div>
                                                    @endif
                                                    @if($institution->school_email && $institution->type === 'lecturer')
                                                        <div class="flex items-center text-sm text-gray-600">
                                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                            </svg>
                                                            {{ $institution->school_email }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex items-center space-x-2 ml-4">
                                                <button @click="open = true; institution = {{ json_encode($institution) }}" 
                                                        class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                                    View Details
                                                </button>
                                                <a href="{{ route('institution.attended.edit', $institution->id) }}" 
                                                   class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                    Edit
                                                </a>
                                                <form action="{{ route('institution.attended.destroy', $institution->id) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this institution?')" 
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Supporting Documents -->
                                        @if($institution->documents->isNotEmpty())
                                            <div class="mt-4 pt-4 border-t border-gray-200">
                                                <h5 class="text-sm font-medium text-gray-700 mb-2">Supporting Documents</h5>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($institution->documents as $document)
                                                        <a href="{{ asset('storage/'.$document->path) }}" 
                                                           target="_blank" 
                                                           class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            {{ ucfirst($document->type) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <!-- Modal for viewing institution details -->
                            <div x-show="open" 
                                 x-cloak 
                                 class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center p-4 z-50">
                                <div class="bg-white p-6 rounded-lg shadow-lg max-w-2xl w-full relative max-h-96 overflow-y-auto">
                                    <button @click="open = false" 
                                            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                    
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4" x-text="institution.institution?.name"></h3>
                                    
                                    <div class="space-y-3 text-sm">
                                        <div>
                                            <span class="font-medium text-gray-700">State:</span>
                                            <span x-text="institution.state?.name"></span>
                                        </div>
                                        <div>
                                            <span class="font-medium text-gray-700">Type:</span>
                                            <span x-text="institution.type"></span>
                                        </div>
                                        <div x-show="institution.field_of_study">
                                            <span class="font-medium text-gray-700">Field of Study:</span>
                                            <span x-text="institution.field_of_study"></span>
                                        </div>
                                        <div x-show="institution.position">
                                            <span class="font-medium text-gray-700">Position:</span>
                                            <span x-text="institution.position"></span>
                                        </div>
                                        <div x-show="institution.start_date">
                                            <span class="font-medium text-gray-700">Duration:</span>
                                            <span x-text="institution.start_date + (institution.end_date ? ' to ' + institution.end_date : ' to Present')"></span>
                                        </div>
                                        <div x-show="institution.school_email && institution.type === 'lecturer'">
                                            <span class="font-medium text-gray-700">School Email:</span>
                                            <span x-text="institution.school_email"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
