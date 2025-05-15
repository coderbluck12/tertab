@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow p-4 rounded-lg">
        <h2 class="text-xl font-bold">Dispute Discussion</h2>

        <div class="border p-2 my-2">
            <strong>Status:</strong> {{ $dispute->status }}
        </div>

        <div class="space-y-2">
            @foreach ($messages as $message)
                <div class="p-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-blue-100' : 'bg-gray-100' }}">
                    <strong>{{ $message->user->name }}:</strong> {{ $message->message }}
                </div>
            @endforeach
        </div>

        <form action="{{ route('dispute.messages.store', $dispute->id) }}" method="POST" class="mt-4">
            @csrf
            <textarea name="message" class="w-full p-2 border rounded" required></textarea>
            <button type="submit" class="mt-2 bg-blue-500 text-white p-2 rounded">Send</button>
        </form>
    </div>
@endsection
