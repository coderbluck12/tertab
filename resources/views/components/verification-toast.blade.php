@props(['user'])

@if($user->status === 'pending')
    <div class="top-0 left-0 right-0 bg-red-500 text-white px-4 py-3" role="alert">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="font-medium">Please verify your ID to access all features</p>
            </div>
        </div>
    </div>
@endif 