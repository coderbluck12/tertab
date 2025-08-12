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

            <!-- Page Header -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">All Reference Requests</h1>
                        <p class="text-gray-600 mt-1">View and manage all reference requests in the system</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Total Requests</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $requests->total() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Pending</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $requests->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Approved</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $requests->where('status', 'lecturer approved')->count() }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Completed</p>
                            <p class="text-2xl font-semibold text-gray-900">{{ $requests->where('status', 'lecturer completed')->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reference Requests Table -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <!-- Header with Export Button -->
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">All Reference Requests ({{ $requests->total() }})</h3>
                    <div class="flex space-x-3">
                        <button onclick="exportToCSV('references')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Export CSV
                        </button>
                        <a href="{{ route('admin.references.export') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path>
                            </svg>
                            Download Excel
                        </a>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="referencesTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lecturer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($requests as $request)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="reference-checkbox rounded border-gray-300" value="{{ $request->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                    <span class="text-white font-medium">{{ strtoupper(substr($request->student->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->student->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->student->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center">
                                                    <span class="text-white font-medium">{{ strtoupper(substr($request->lecturer->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $request->lecturer->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $request->lecturer->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ ucfirst($request->request_type) }}</div>
                                        <div class="text-sm text-gray-500">{{ ucfirst($request->reference_type) }}</div>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="toggleDetails({{ $request->id }})" class="text-blue-600 hover:text-blue-900 flex items-center">
                                            <svg class="w-4 h-4 mr-1 transform transition-transform" id="arrow-{{ $request->id }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Details
                                        </button>
                                    </td>
                                </tr>
                                <!-- Expandable Details Row -->
                                <tr id="details-{{ $request->id }}" class="hidden bg-gray-50">
                                    <td colspan="8" class="px-6 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <div>
                                                <h4 class="font-semibold text-gray-800 mb-2">Request Information</h4>
                                                <p class="text-sm text-gray-600"><strong>Request ID:</strong> {{ $request->id }}</p>
                                                <p class="text-sm text-gray-600"><strong>Request Type:</strong> {{ ucfirst($request->request_type) }}</p>
                                                <p class="text-sm text-gray-600"><strong>Reference Type:</strong> {{ ucfirst($request->reference_type) }}</p>
                                                <p class="text-sm text-gray-600"><strong>Status:</strong> {{ ucfirst($request->status) }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800 mb-2">Timeline</h4>
                                                <p class="text-sm text-gray-600"><strong>Requested:</strong> {{ $request->created_at->format('F d, Y \a\t g:i A') }}</p>
                                                <p class="text-sm text-gray-600"><strong>Last Updated:</strong> {{ $request->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                                <p class="text-sm text-gray-600"><strong>Amount:</strong> ₦{{ number_format($request->amount ?? 0, 2) }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800 mb-2">Description</h4>
                                                <p class="text-sm text-gray-600">{{ $request->reference_description ?? 'No description provided' }}</p>
                                                @if($request->documents && $request->documents->count() > 0)
                                                    <p class="text-sm text-gray-600 mt-2"><strong>Documents:</strong> {{ $request->documents->count() }} file(s)</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
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

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $requests->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Table Functionality -->
    <script>
        // Toggle expandable details
        function toggleDetails(requestId) {
            const detailsRow = document.getElementById(`details-${requestId}`);
            const arrow = document.getElementById(`arrow-${requestId}`);
            
            if (detailsRow.classList.contains('hidden')) {
                detailsRow.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                detailsRow.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        // Select all functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.reference-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Export to CSV functionality
        function exportToCSV(type) {
            const table = document.getElementById('referencesTable');
            const rows = table.querySelectorAll('tbody tr:not([id^="details-"])');
            let csv = 'Request ID,Student Name,Student Email,Lecturer Name,Lecturer Email,Request Type,Reference Type,Status,Amount,Date\n';
            
            rows.forEach(row => {
                if (row.querySelector('.reference-checkbox')) {
                    const cells = row.querySelectorAll('td');
                    const requestId = cells[0].querySelector('.reference-checkbox').value;
                    const studentName = cells[1].querySelector('.text-sm.font-medium').textContent.trim();
                    const studentEmail = cells[1].querySelector('.text-sm.text-gray-500').textContent.trim();
                    const lecturerName = cells[2].querySelector('.text-sm.font-medium').textContent.trim();
                    const lecturerEmail = cells[2].querySelector('.text-sm.text-gray-500').textContent.trim();
                    const requestType = cells[3].querySelector('.text-sm.text-gray-900').textContent.trim();
                    const referenceType = cells[3].querySelector('.text-sm.text-gray-500').textContent.trim();
                    const status = cells[4].querySelector('span').textContent.trim();
                    const date = cells[5].textContent.trim().replace(/\s+/g, ' ');
                    
                    csv += `"${requestId}","${studentName}","${studentEmail}","${lecturerName}","${lecturerEmail}","${requestType}","${referenceType}","${status}","N/A","${date}"\n`;
                }
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `reference_requests_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
@endsection
