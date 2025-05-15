<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Lecturer List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">All Lecturers</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($lecturers as $lecturer)
                        <x-user-card :user="$lecturer" />
                    @empty
                        <p class="text-center text-gray-500">No lecturers available.</p>
                    @endforelse
                </div>
                <div class="mt-6">
                    {{ $lecturers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
