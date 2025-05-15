<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dispute Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-10 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Dispute for Reference #{{ $dispute->reference_id }}</h3>

                    <!-- Dispute Status -->
                    <p><strong>Status:</strong>
                        <span class="px-2 py-1 text-sm font-medium rounded
                            {{ $dispute->status == 'open' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ ucfirst($dispute->status) }}
                        </span>
                    </p>

                    <!-- Messages -->
                    <div class="mt-4">
                        <h4 class="text-md font-semibold">Discussion</h4>
                        <div class="border rounded p-4 bg-gray-100 mt-2">
                            @forelse($messages as $message)
                                <div class="mb-2">
                                    <strong>{{ $message->user->name }}:</strong>
                                    <p class="text-gray-700">{{ $message->message }}</p>
                                    <p class="text-sm text-gray-500">{{ $message->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <hr class="my-2">
                            @empty
                                <p class="text-gray-500">No messages yet.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Message Input -->
                    @if($dispute->status === 'open')
                        <form method="POST" action="{{ route('disputes.messages.send', $dispute->id) }}" class="mt-4">
                            @csrf
                            <input type="hidden" name="dispute_id" value="{{ $dispute->id }}">

                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700">Your Message</label>
                                <textarea id="message" name="message" class="w-full p-2 border rounded mt-1" required></textarea>
                                <x-input-error :messages="$errors->get('message')" class="mt-2" />
                            </div>

                            <x-primary-button>Send Message</x-primary-button>
                        </form>

                        <form method="POST" action="{{ route('disputes.resolve', $dispute->id) }}" class="mt-4">
                            @csrf
                            @method('PUT')
                            <x-primary-button class="bg-green-600 hover:bg-green-700">Resolve Dispute</x-primary-button>
                        </form>
                    @else
                        <p class="text-red-600 text-sm">This dispute has been resolved and is now closed.</p>
                        <p class="text-red-600 text-sm">Reopen to continue.</p>

                        <form method="POST" action="{{ route('disputes.open', $dispute->id) }}" class="mt-4">
                            @csrf
                            @method('PUT')
                            <x-primary-button class="bg-green-600 hover:bg-green-700">Reopen Dispute</x-primary-button>
                        </form>
                    @endif



{{--                        @can('manage-platform')--}}

{{--                        @endcan--}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
