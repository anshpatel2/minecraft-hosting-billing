<x-modern-layout title="Billing Management">
    <!-- Page Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-rose-600 via-pink-600 to-purple-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-3">Billing Management</h1>
                <p class="text-rose-100 text-lg">Manage billing, payments, and invoices</p>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="space-y-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <livewire:admin.billing-manager />
        </div>
    </div>
</x-modern-layout>
