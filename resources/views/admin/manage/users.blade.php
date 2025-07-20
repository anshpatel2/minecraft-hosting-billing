<x-modern-layout title="User Management">
    <!-- Page Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-violet-600 via-purple-600 to-indigo-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">User Management</h1>
                <p class="text-violet-100 text-lg">Advanced user management and analytics</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:admin.user-manager />
        </div>
    </div>
</x-modern-layout>
