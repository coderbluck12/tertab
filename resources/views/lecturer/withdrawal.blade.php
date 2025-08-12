@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div id="success-message" class="bg-green-500 text-white p-4 rounded-md shadow-md mb-4">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('success-message').style.display = 'none';
                    }, 5000);
                </script>
            @endif

            <!-- Page Header -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Withdrawal Request</h1>
                        <p class="text-gray-600 mt-1">Request a withdrawal from your earnings</p>
                    </div>
                    <a href="{{ route('lecturer.withdrawal.history') }}" 
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        View History
                    </a>
                </div>
            </div>

            <!-- Wallet Balance Card -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Available Balance</h3>
                        <p class="text-3xl font-bold text-green-600 mt-2">
                            ₦{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Minimum withdrawal</p>
                        <p class="text-lg font-semibold text-gray-700">₦1,000.00</p>
                    </div>
                </div>
            </div>

            <!-- Withdrawal Form -->
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Withdrawal Details</h3>
                </div>
                
                <div class="p-6">
                    <form method="POST" action="{{ route('lecturer.withdrawal.store') }}" class="space-y-6">
                        @csrf

                        <!-- Bank Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="bank_name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                                <input type="text" 
                                       id="bank_name" 
                                       name="bank_name" 
                                       value="{{ old('bank_name') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="e.g., First Bank of Nigeria"
                                       required>
                                @error('bank_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="account_number" class="block text-sm font-medium text-gray-700">Account Number</label>
                                <input type="text" 
                                       id="account_number" 
                                       name="account_number" 
                                       value="{{ old('account_number') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="1234567890"
                                       required>
                                @error('account_number')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="account_name" class="block text-sm font-medium text-gray-700">Account Name</label>
                            <input type="text" 
                                   id="account_name" 
                                   name="account_name" 
                                   value="{{ old('account_name') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Full name as it appears on your bank account"
                                   required>
                            @error('account_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Withdrawal Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Withdrawal Amount (₦)</label>
                            <input type="number" 
                                   id="amount" 
                                   name="amount" 
                                   value="{{ old('amount') }}"
                                   min="1000"
                                   max="{{ auth()->user()->wallet_balance ?? 0 }}"
                                   step="0.01"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter amount to withdraw"
                                   required>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Minimum: ₦1,000.00 | Available: ₦{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}
                            </p>
                        </div>

                        <!-- Withdrawal Reason (Optional) -->
                        <div>
                            <label for="withdrawal_reason" class="block text-sm font-medium text-gray-700">Reason for Withdrawal (Optional)</label>
                            <textarea id="withdrawal_reason" 
                                      name="withdrawal_reason" 
                                      rows="3"
                                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                                      placeholder="Brief description of why you're making this withdrawal">{{ old('withdrawal_reason') }}</textarea>
                            @error('withdrawal_reason')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Important Information -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Important Information</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Withdrawal requests are processed within 1-3 business days</li>
                                            <li>Ensure your bank details are correct to avoid delays</li>
                                            <li>You will receive a notification once your withdrawal is processed</li>
                                            <li>Contact support if you don't receive your funds within 5 business days</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                Submit Withdrawal Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
