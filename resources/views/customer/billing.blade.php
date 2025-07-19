<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Billing & Payments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Billing Overview</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Current Balance</h4>
                            <p class="text-2xl font-bold text-green-900 mt-2">$0.00</p>
                        </div>
                        
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">Monthly Charges</h4>
                            <p class="text-2xl font-bold text-blue-900 mt-2">$0.00</p>
                        </div>
                        
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-purple-800">Next Payment</h4>
                            <p class="text-lg font-bold text-purple-900 mt-2">N/A</p>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-yellow-800">
                            <strong>Coming Soon:</strong> Full billing system with payment processing, 
                            invoices, and transaction history will be implemented in upcoming phases.
                        </p>
                    </div>

                    <div class="text-center text-gray-500 py-8">
                        <p>No billing activity yet. Billing features coming soon!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
