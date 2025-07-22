<x-modern-layout title="Dropdown Test">
    <div class="modern-card">
        <div class="modern-card-header">
            <h2>Profile Dropdown Test</h2>
        </div>
        <div class="modern-card-body">
            <p>This page is used to test the profile dropdown functionality.</p>
            <p>Check the top-right corner for the user avatar dropdown.</p>
            
            <!-- Additional test dropdown -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4">Additional Alpine.js Test</h3>
                <div x-data="{ testOpen: false }" class="relative inline-block">
                    <button @click="testOpen = !testOpen" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                        Test Dropdown
                    </button>
                    <div x-show="testOpen" @click.away="testOpen = false" class="absolute top-full left-0 mt-2 w-48 bg-white shadow-lg rounded-lg p-4 z-50">
                        <p>This dropdown works if Alpine.js is functioning properly!</p>
                        <p>State: <span x-text="testOpen ? 'Open' : 'Closed'"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-modern-layout>
