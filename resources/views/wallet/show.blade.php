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

            @can('request-for-reference')
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
            @endcan

            @can('provide-a-reference')
                <div class="space-y-4">
                    <!-- Withdrawal Button -->
                    <a href="{{ route('lecturer.withdrawal.create') }}" 
                       class="w-full bg-green-600 text-white py-3 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors flex items-center justify-center font-medium">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                        </svg>
                        Withdraw Funds
                    </a>
                    
                    <!-- How it works info -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">How it works</h3>
                        <p class="text-blue-600">
                            Your wallet balance increases automatically when students successfully request references from you and the reference is both completed and approved.
                            The platform will credit your wallet with the reference request amount after each successful transaction.
                        </p>
                    </div>
                </div>
            @endcan
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            @if(session()->has('pending_reference_request'))
                <div class="mt-2">
                    <a href="{{ route('student.reference') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                        Continue with Reference Request
                    </a>
                </div>
            @endif
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