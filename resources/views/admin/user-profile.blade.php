<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('User Profile') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <!-- User Details Card -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">{{ $user->name }}</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">User profile details</p>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->email }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Role</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($user->role) }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Joined</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->created_at->format('M d, Y') }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->phone }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Residential</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $user->address }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Attended Institutions -->
        <div class="max-w-4xl mx-auto mt-10 px-4 sm:px-6 lg:px-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Attended Institutions</h3>
            <ul class="space-y-6">
                @forelse($institutions as $institution)
                    <li class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
                            <div class="w-full">
                                <p class="text-lg font-semibold text-gray-800">{{ $institution->institution->name }}</p>
                                @can('request-for-reference')
                                    <p class="mt-1 text-sm text-gray-600">
                                        {{ ucfirst($institution->type) }} - {{ ucfirst($institution->field_of_study) }}
                                    </p>
                                @endcan
                                @can('provide-a-reference')
                                    <p class="mt-1 text-sm text-gray-600">{{ ucfirst($institution->position) }}</p>
                                @endcan
                            </div>
                            <!-- Optional view button -->
                            {{-- <button class="mt-2 sm:mt-0 text-indigo-600 text-sm font-medium hover:underline">
                                View
                            </button> --}}
                        </div>

                        <!-- Supporting Documents -->
                        @if($institution->documents->isNotEmpty())
                            <div class="mt-4">
                                <p class="text-sm font-semibold text-gray-700">Supporting Documents:</p>
                                <ul class="mt-2 space-y-1">
                                    @foreach($institution->documents as $document)
                                        <li class="flex items-center gap-2">
                                            <a href="{{ asset('storage/' . $document->path) }}" target="_blank"
                                               class="text-blue-600 text-sm hover:underline flex items-center">
                                                {{ $document->name }}
                                                <svg class="w-5 h-5 ml-1 text-gray-800" fill="none" stroke="currentColor"
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
                            <p class="text-sm text-gray-500 mt-4">No supporting documents uploaded.</p>
                        @endif
                    </li>
                @empty
                    <li class="text-center text-gray-500 py-6">
                        <p>No institutions added yet.</p>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>
