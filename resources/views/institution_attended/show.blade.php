@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Your Attended Institutions</h3>
            
            <div class="space-y-6">
                @forelse($institutions as $institution)
                    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-6">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="w-full">
                                <h4 class="text-lg font-semibold text-gray-800">{{ $institution->institution->name }}</h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $institution->state->name ?? 'No State' }}
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Type: {{ ucfirst($institution->type) }}
                                    @if($institution->field_of_study)
                                        - {{ $institution->field_of_study }}
                                    @endif
                                </p>
                                @if($institution->position)
                                    <p class="text-sm text-gray-600 mt-1">
                                        Position: {{ $institution->position }}
                                    </p>
                                @endif
                                @if($institution->start_date)
                                    <p class="text-sm text-gray-600 mt-1">
                                        Duration: {{ $institution->start_date ? $institution->start_date->format('M Y') : 'N/A' }} 
                                        @if($institution->end_date)
                                            - {{ $institution->end_date->format('M Y') }}
                                        @else
                                            - Present
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>

                        @if($institution->documents->isNotEmpty())
                            <div class="mt-4">
                                <h5 class="text-sm font-semibold text-gray-700 mb-2">Supporting Documents</h5>
                                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($institution->documents as $document)
                                        <div class="bg-gray-50 p-3 rounded-lg">
                                            <a href="{{ asset('storage/' . $document->path) }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View Document
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-500">No institutions found.</p>
                        <a href="{{ route('institution.attended.index') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                            Add Institution
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection 