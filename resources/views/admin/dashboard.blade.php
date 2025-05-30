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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <x-stat-card title="Total Lecturers" count="{{ $adminStats['lecturers'] ?? 0 }}" color="blue" icon="fas fa-user-tie" />
                <x-stat-card title="Total Students" count="{{ $adminStats['students'] ?? 0 }}" color="green" icon="fas fa-users" />
                <x-stat-card title="Total Reference Requests" count="{{ $adminStats['total'] ?? 0 }}" color="teal" icon="fas fa-file-alt" />
                <x-stat-card title="Total Earnings" count="₦{{ number_format($adminStats['earnings'] ?? 0, 2) }}" color="black" icon="fas fa-wallet" />
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
        </div>
    </div>
@endsection
