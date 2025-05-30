@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h2 class="text-2xl font-bold mb-4">Your Wallet</h2>
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="text-gray-600 mb-2">Available Balance</div>
                <div class="text-3xl font-bold text-green-600">₦{{ number_format($wallet->balance, 2) }}</div>
            </div>

            <form action="{{ route('wallet.fund') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount to Fund (₦)</label>
                    <input type="number" name="amount" id="amount" min="100" step="100" required
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Fund Wallet
                </button>
            </form>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
        @endif
    </div>
</div>
@endsection 