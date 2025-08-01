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
                    <a href="{{ route('admin.students') }}" class="bg-yellow-600 text-white p-4 rounded-lg text-center hover:bg-yellow-700 transition-colors">
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
                <h3 class="text-lg font-semibold text-gray-800 mb-4">All Reference Requests</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($requests as $request)
                        <x-request-card :request="$request" />
                    @empty
                        <p class="text-center text-gray-500">No reference requests available.</p>
                    @endforelse
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
                            @forelse($verificationRequests->take(5) as $request)
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
            </div>
        </div>
    </div>
@endsection
