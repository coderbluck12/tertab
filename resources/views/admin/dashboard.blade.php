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

            <!-- Admin Navigation Links -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Admin Management</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                    <a href="{{ route('admin.institutions.index') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-blue-700 transition-colors">
                        <i class="fas fa-university text-2xl mb-2"></i>
                        <p class="font-medium">Manage Institutions</p>
                    </a>
                    <a href="{{ route('admin.platform.settings') }}" class="bg-green-600 text-white p-4 rounded-lg text-center hover:bg-green-700 transition-colors">
                        <i class="fas fa-cogs text-2xl mb-2"></i>
                        <p class="font-medium">Platform Settings</p>
                    </a>
                    <a href="{{ route('admin.students') }}" class="bg-blue-600 text-white p-4 rounded-lg text-center hover:bg-yellow-700 transition-colors">
                        <i class="fas fa-users text-2xl mb-2"></i>
                        <p class="font-medium">Manage Students</p>
                    </a>
                    <a href="{{ route('admin.lecturers') }}" class="bg-red-600 text-white p-4 rounded-lg text-center hover:bg-orange-700 transition-colors">
                        <i class="fas fa-user-tie text-2xl mb-2"></i>
                        <p class="font-medium">Manage Lecturers</p>
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <x-stat-card title="Total Lecturers" count="{{ $adminStats['lecturers'] ?? 0 }}" color="red" icon="fas fa-user-tie" />
                <x-stat-card title="Total Students" count="{{ $adminStats['students'] ?? 0 }}" color="blue" icon="fas fa-users" />
                <x-stat-card title="Total Reference Requests" count="{{ $adminStats['total'] ?? 0 }}" color="green" icon="fas fa-file-alt" />
                <x-stat-card title="Total Earnings" count="₦{{ number_format($adminStats['earnings'] ?? 0, 2) }}" color="yellow" icon="fas fa-wallet" />
            </div>

            <!-- Requests Management -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Reference Requests ({{ $requests->total() }})</h3>
                    <a href="{{ route('admin.references.all') }}" class="text-blue-600 hover:text-blue-800 font-medium">View All</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lecturer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center">
                                                    <span class="text-white text-xs font-medium">{{ strtoupper(substr($request->student->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->student->name }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($request->student->email, 20) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-purple-500 flex items-center justify-center">
                                                    <span class="text-white text-xs font-medium">{{ strtoupper(substr($request->lecturer->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->lecturer->name }}</div>
                                                <div class="text-xs text-gray-500">{{ Str::limit($request->lecturer->email, 20) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ ucfirst($request->request_type) }}</div>
                                        <div class="text-xs text-gray-500">{{ ucfirst($request->reference_type) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $request->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($request->status == 'lecturer approved' ? 'bg-green-100 text-green-800' : 
                                               ($request->status == 'lecturer completed' ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800')) }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $request->created_at->format('M d, Y') }}
                                        <div class="text-xs text-gray-400">{{ $request->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.reference.show', $request->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            @if($request->status == 'pending')
                                                <a href="{{ route('admin.reference.approve', $request->id) }}" class="text-green-600 hover:text-green-900">Approve</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <h3 class="mt-2 text-sm font-medium text-gray-900">No reference requests</h3>
                                        <p class="mt-1 text-sm text-gray-500">No reference requests have been submitted yet.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Links -->
                <div class="mt-4 border-t border-gray-200 pt-4">
                    {{ $requests->links() }}
                </div>
            </div>

            <!-- Verification Requests -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Verification Requests</h3>
                    <a href="{{ route('admin.verification.requests') }}" class="text-blue-600 hover:text-blue-800">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID Card Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($verificationRequests as $request)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $request->user ? $request->user->name : 'Unknown User' }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $request->user ? $request->user->email : '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ ucfirst($request->user->role) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            @switch($request->document_type)
                                                @case('national_id')
                                                    National ID Card
                                                    @break
                                                @case('passport')
                                                    Passport
                                                    @break
                                                @case('drivers_license')
                                                    Driver's License
                                                    @break
                                                @default
                                                    {{ ucfirst($request->document_type) }}
                                            @endswitch
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               ($request->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $request->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.user.show', $request->user_id) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View Details
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No verification requests found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination Links -->
                <div class="mt-4">
                    {{ $verificationRequests->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
