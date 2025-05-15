<div class="border border-gray-200 rounded-lg p-4 shadow-sm bg-white">
    <div class="flex justify-between items-center mb-2">
        <h3 class="text-lg font-semibold">{{ $user->name }}</h3>
        <span class="px-2 py-1 text-sm font-medium rounded bg-gray-300 text-gray-800">
            {{ ucfirst($user->role) }}
        </span>
    </div>
    <p class="text-gray-600"><strong>Email:</strong> {{ $user->email }}</p>
    <p class="text-gray-600"><strong>Joined:</strong> {{ $user->created_at->format('M d, Y') }}</p>

    <div class="mt-3 flex space-x-3">
        <a href="{{ route('admin.user.show', $user->id) }}" class="text-blue-500 hover:underline">View Profile</a>
        @if($user->status == 'pending')
            <a href="{{ route('admin.user.approve', $user->id) }}" class="text-red-500 hover:underline">Approve User</a>
        @endif

    </div>
</div>
