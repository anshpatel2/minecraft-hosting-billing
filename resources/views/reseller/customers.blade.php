<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Customers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Customer List</h3>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Add New Customer
                        </button>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <p class="text-yellow-800">
                            <strong>Note:</strong> As a reseller, you can create and manage customer accounts. 
                            This feature will be expanded in future phases.
                        </p>
                    </div>

                    <div class="text-center text-gray-500 py-8">
                        <p>No customers yet. Start by adding your first customer!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
