<div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-white">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-semibold">{{ $request->student->name }}</h3>
        <span class="px-2 py-1 text-sm font-medium rounded
        {{ $request->reference_status == 'pending' ? 'bg-blue-800 text-white' :
           ($request->reference_status == 'lecturer approved' ? 'bg-green-800 text-white' : 'bg-gray-800 text-white') }}">
        {{ ucfirst($request->reference_status) }}
        </span>
    </div>
    <p class="text-gray-600"><strong>Reference Type:</strong> {{ ucfirst($request->reference_type) }}</p>
    <p class="text-gray-600"><strong>Request Type:</strong> {{ ucfirst($request->request_type) }}</p>
    <p class="text-gray-600"><strong>Description:</strong> {{ $request->reference_description }}</p>
    <p class="text-gray-500 text-sm"><strong>Date Requested:</strong> {{ $request->created_at->format('M d, Y') }}</p>

    <div class="mt-3 flex space-x-3">
        <a href="{{ route('admin.reference.show', $request->id) }}" class="text-blue-500 hover:underline">View</a>

        @if($request->status !== 'pending' && auth()->user()->status === 'verified')
            @if($request->dispute)
                <a href="{{ route('disputes.show', $request->dispute->id) }}" class="text-blue-500 hover:underline">
                    Manage Dispute
                </a>
            @else
                <a href="{{ route('disputes.create', ['reference_id' => $request->id]) }}" class="text-red-500 hover:underline">
                    Create Dispute
                </a>
            @endif
        @endif

        @if($request->reference_status == 'pending')
            <!-- Approve Request -->
            <form action="{{ route('admin.reference.approve', $request->id) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-green-500 hover:underline">Approve</button>
            </form>

            <!-- Reject Request -->
            <form action="{{ route('admin.reference.reject', $request->id) }}" method="POST" class="inline-block">
                @csrf
                @method('PATCH')
                <button type="submit" class="text-red-500 hover:underline">Reject</button>
            </form>
        @endif
    </div>
</div>
