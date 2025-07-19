<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Servers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium">Server Management</h3>
                        @can('create-servers')
                        <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Create New Server
                        </button>
                        @endcan
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <p class="text-blue-800">
                            <strong>Info:</strong> Server management functionality will be implemented in upcoming phases. 
                            This area will allow you to create, configure, and manage Minecraft servers.
                        </p>
                    </div>

                    <div class="text-center text-gray-500 py-8">
                        <p>No servers configured yet. Server management coming soon!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
