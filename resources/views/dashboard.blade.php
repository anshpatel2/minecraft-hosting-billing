<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Welcome, {{ auth()->user()->name }}!</h3>
                    <p class="mb-6">You're logged in as: <span class="font-semibold">{{ auth()->user()->getPrimaryRole() }}</span></p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if(auth()->user()->canAccessAdminPanel())
                        <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                            <h4 class="font-semibold text-red-800 mb-2">Admin Panel</h4>
                            <p class="text-sm text-red-600 mb-3">Manage users, servers, and system settings</p>
                            <a href="{{ route('admin.dashboard') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Access Admin Panel
                            </a>
                        </div>
                        @endif

                        @if(auth()->user()->canAccessResellerPanel())
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                            <h4 class="font-semibold text-blue-800 mb-2">Reseller Panel</h4>
                            <p class="text-sm text-blue-600 mb-3">Manage customers and their services</p>
                            <a href="{{ route('reseller.dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-sm">
                                Access Reseller Panel
                            </a>
                        </div>
                        @endif

                        <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                            <h4 class="font-semibold text-green-800 mb-2">My Servers</h4>
                            <p class="text-sm text-green-600 mb-3">Manage your Minecraft servers</p>
                            <a href="{{ route('customer.servers') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-sm">
                                View Servers
                            </a>
                        </div>

                        <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                            <h4 class="font-semibold text-purple-800 mb-2">Billing</h4>
                            <p class="text-sm text-purple-600 mb-3">View billing information and payments</p>
                            <a href="{{ route('customer.billing') }}" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded text-sm">
                                View Billing
                            </a>
                        </div>
                    </div>

                    <div class="mt-8 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-semibold mb-2">Your Permissions:</h4>
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
