<x-modern-layout title="Plan Management">
    <!-- Page Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-orange-600 via-red-600 to-pink-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">Plan Management</h1>
                <p class="text-orange-100 text-lg">Manage hosting plans and pricing</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:admin.plan-manager />
        </div>
    </div>
</x-modern-layout>
