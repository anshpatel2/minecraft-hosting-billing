<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reseller Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Welcome to Reseller Panel</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-blue-800">Customer Management</h4>
                            <p class="text-sm text-blue-600 mt-2">Manage your customers and their accounts</p>
                            <a href="{{ route('reseller.customers') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 inline-block">
                                View Customers →
                            </a>
                        </div>
                        
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-semibold text-green-800">Server Management</h4>
                            <p class="text-sm text-green-600 mt-2">Create and manage servers for customers</p>
                            <a href="{{ route('customer.servers') }}" class="text-green-600 hover:text-green-800 text-sm font-medium mt-2 inline-block">
                                Manage Servers →
                            </a>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h4 class="font-semibold mb-4">Your Role: {{ auth()->user()->getPrimaryRole() }}</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach(auth()->user()->getAllPermissions() as $permission)
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $permission->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
