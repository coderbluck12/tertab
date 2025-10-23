@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Affiliate Applications</h1>
            <p class="text-gray-600 mt-2">Manage affiliate program applications</p>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filter Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="?status=all" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('status', 'all') === 'all' ? 'border-blue-500 text-blue-600' : '' }}">
                    All Applications
                </a>
                <a href="?status=pending" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'pending' ? 'border-blue-500 text-blue-600' : '' }}">
                    Pending
                </a>
                <a href="?status=approved" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'approved' ? 'border-blue-500 text-blue-600' : '' }}">
                    Approved
                </a>
                <a href="?status=rejected" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('status') === 'rejected' ? 'border-blue-500 text-blue-600' : '' }}">
                    Rejected
                </a>
            </nav>
        </div>

        <!-- Applications Table -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            @if($applications->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applied Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($applications as $application)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $application->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $application->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $application->phone ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($application->status === 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($application->status === 'approved')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Approved
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $application->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="viewApplication({{ $application->id }})" class="text-blue-600 hover:text-blue-900 mr-3">View</button>
                                        
                                        @if($application->status === 'pending')
                                            <button onclick="approveApplication({{ $application->id }})" class="text-green-600 hover:text-green-900 mr-3">Approve</button>
                                            <button onclick="rejectApplication({{ $application->id }})" class="text-red-600 hover:text-red-900">Reject</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $applications->links() }}
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <p class="text-gray-500">No affiliate applications found.</p>
                </div>
            @endif
        </div>

    </div>
</div>

<!-- View Application Modal -->
<div id="viewModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold">Application Details</h3>
            <button onclick="closeModal('viewModal')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div id="applicationDetails"></div>
    </div>
</div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <h3 class="text-xl font-bold mb-4">Approve Application</h3>
        <form id="approveForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Admin Notes (Optional)</label>
                <textarea name="admin_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('approveModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <h3 class="text-xl font-bold mb-4">Reject Application</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Reason for Rejection (Optional)</label>
                <textarea name="admin_notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeModal('rejectModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function viewApplication(id) {
    fetch(`/admin/affiliates/${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('applicationDetails').innerHTML = `
                <div class="space-y-3">
                    <div><strong>Name:</strong> ${data.name}</div>
                    <div><strong>Email:</strong> ${data.email}</div>
                    <div><strong>Phone:</strong> ${data.phone || 'N/A'}</div>
                    <div><strong>Status:</strong> <span class="px-2 py-1 rounded text-sm ${data.status === 'pending' ? 'bg-yellow-100 text-yellow-800' : data.status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${data.status}</span></div>
                    <div><strong>Applied Date:</strong> ${new Date(data.created_at).toLocaleDateString()}</div>
                    <div><strong>Reason:</strong><br><p class="mt-2 p-3 bg-gray-50 rounded">${data.reason}</p></div>
                    ${data.admin_notes ? `<div><strong>Admin Notes:</strong><br><p class="mt-2 p-3 bg-gray-50 rounded">${data.admin_notes}</p></div>` : ''}
                </div>
            `;
            document.getElementById('viewModal').classList.remove('hidden');
        });
}

function approveApplication(id) {
    document.getElementById('approveForm').action = `/admin/affiliates/${id}/approve`;
    document.getElementById('approveModal').classList.remove('hidden');
}

function rejectApplication(id) {
    document.getElementById('rejectForm').action = `/admin/affiliates/${id}/reject`;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>
@endsection
