@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <!-- Header with Export Button -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">All Students ({{ $students->total() }})</h3>
                    <div class="flex space-x-3">
                        <button onclick="exportToCSV('students')" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Export CSV
                        </button>
                        <a href="{{ route('admin.students.export') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"></path>
                            </svg>
                            Download Excel
                        </a>
                    </div>
                </div>

                <!-- Students Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200" id="studentsTable">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <input type="checkbox" id="selectAll" class="rounded border-gray-300">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($students as $student)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <input type="checkbox" class="student-checkbox rounded border-gray-300" value="{{ $student->id }}">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                                    <span class="text-white font-medium">{{ strtoupper(substr($student->name, 0, 2)) }}</span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $student->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $student->email }}</div>
                                        <div class="text-sm text-gray-500">{{ $student->email_verified_at ? 'Verified' : 'Unverified' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $student->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ ucfirst($student->status ?? 'pending') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $student->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.user.show', $student->id) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                            @if($student->status == 'pending')
                                                <a href="{{ route('admin.user.approve', $student->id) }}" class="text-green-600 hover:text-green-900">Approve</a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="toggleDetails({{ $student->id }})" class="text-blue-600 hover:text-blue-900 flex items-center">
                                            <svg class="w-4 h-4 mr-1 transform transition-transform" id="arrow-{{ $student->id }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                            </svg>
                                            Details
                                        </button>
                                    </td>
                                </tr>
                                <!-- Expandable Details Row -->
                                <tr id="details-{{ $student->id }}" class="hidden bg-gray-50">
                                    <td colspan="7" class="px-6 py-4">
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <div>
                                                <h4 class="font-semibold text-gray-800 mb-2">Personal Information</h4>
                                                <p class="text-sm text-gray-600"><strong>Full Name:</strong> {{ $student->name }}</p>
                                                <p class="text-sm text-gray-600"><strong>Email:</strong> {{ $student->email }}</p>
                                                <p class="text-sm text-gray-600"><strong>Role:</strong> {{ ucfirst($student->role) }}</p>
                                                <p class="text-sm text-gray-600"><strong>Status:</strong> {{ ucfirst($student->status ?? 'pending') }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800 mb-2">Account Details</h4>
                                                <p class="text-sm text-gray-600"><strong>User ID:</strong> {{ $student->id }}</p>
                                                <p class="text-sm text-gray-600"><strong>Joined:</strong> {{ $student->created_at->format('F d, Y \a\t g:i A') }}</p>
                                                <p class="text-sm text-gray-600"><strong>Last Updated:</strong> {{ $student->updated_at->format('F d, Y \a\t g:i A') }}</p>
                                                <p class="text-sm text-gray-600"><strong>Email Verified:</strong> {{ $student->email_verified_at ? 'Yes (' . $student->email_verified_at->format('M d, Y') . ')' : 'No' }}</p>
                                            </div>
                                            <div>
                                                <h4 class="font-semibold text-gray-800 mb-2">Activity Summary</h4>
                                                <p class="text-sm text-gray-600"><strong>References:</strong> {{ $student->references_count ?? 0 }}</p>
                                                <p class="text-sm text-gray-600"><strong>Institutions:</strong> {{ $student->institutions_count ?? 0 }}</p>
                                                <p class="text-sm text-gray-600"><strong>Wallet Balance:</strong> ₦{{ number_format($student->wallet_balance ?? 0, 2) }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No students available.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $students->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Table Functionality -->
    <script>
        // Toggle expandable details
        function toggleDetails(studentId) {
            const detailsRow = document.getElementById(`details-${studentId}`);
            const arrow = document.getElementById(`arrow-${studentId}`);
            
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
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Export to CSV functionality
        function exportToCSV(type) {
            const table = document.getElementById('studentsTable');
            const rows = table.querySelectorAll('tbody tr:not([id^="details-"])');
            let csv = 'Name,Email,Status,Joined Date,User ID\n';
            
            rows.forEach(row => {
                if (row.querySelector('.student-checkbox')) {
                    const cells = row.querySelectorAll('td');
                    const name = cells[1].querySelector('.text-sm.font-medium').textContent.trim();
                    const email = cells[2].querySelector('.text-sm.text-gray-900').textContent.trim();
                    const status = cells[3].querySelector('span').textContent.trim();
                    const joined = cells[4].textContent.trim();
                    const id = cells[1].querySelector('.text-sm.text-gray-500').textContent.replace('ID: ', '').trim();
                    
                    csv += `"${name}","${email}","${status}","${joined}","${id}"\n`;
                }
            });
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `students_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }
    </script>
@endsection
