<!-- resources/views/admin/settings.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Platform Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Settings Form -->
            <div class="bg-white shadow-lg sm:rounded-lg p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4">Configure Platform Settings</h4>

                <form action="{{ route('admin.platform.settings.update') }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <!-- Reference Request Price -->
                    <div class="mb-4">
                        <label for="reference_request_price" class="block text-gray-700 font-semibold mb-2">Reference Request Price (in NGN):</label>
                        <input type="number" step="0.01" id="reference_request_price" name="reference_request_price" value="{{ old('reference_request_price', $settings->reference_request_price) }}" class="w-full p-2 border border-gray-300 rounded-md" required>
                    </div>

                    <div class="flex justify-end mt-4">
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md shadow-sm hover:bg-blue-600">
                            Save Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
