@php
    $unreadCount = auth()->user()->notifications()->where('is_read', false)->count();
@endphp

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-1 text-gray-400 hover:text-gray-500 focus:outline-none">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>
        @if($unreadCount > 0)
            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-400 ring-2 ring-white"></span>
        @endif
    </button>

    <div x-show="open" 
         @click.away="open = false"
         class="absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50">
        <div class="py-1">
            <div class="px-4 py-2 border-b border-gray-100">
                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
            </div>
            
            <div class="max-h-96 overflow-y-auto">
                @forelse(auth()->user()->notifications()->latest()->take(10)->get() as $notification)
                    <a href="{{ $notification->link }}" 
                       class="block px-4 py-3 hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}"
                       onclick="markAsRead({{ $notification->id }})">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $notification->title }}</p>
                                <p class="text-sm text-gray-500">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                            </div>
                            @if(!$notification->is_read)
                                <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    New
                                </span>
                            @endif
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500 text-center">
                        No notifications
                    </div>
                @endforelse
            </div>

            @if($unreadCount > 0)
                <div class="px-4 py-2 border-t border-gray-100">
                    <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-800">
                        Mark all as read
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function markAsRead(notificationId) {
    fetch(`/notifications/${notificationId}/mark-as-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    });
}

function markAllAsRead() {
    fetch('/notifications/mark-all-as-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then(() => {
        window.location.reload();
    });
}
</script>
@endpush 