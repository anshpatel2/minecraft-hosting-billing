<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Users Management') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-blue-500 dark:hover:bg-blue-600">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create User
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <!-- Users Table -->
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table id="users-table" class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Name
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Role
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Created
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $index => $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-sm font-medium text-white">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    {{ $user->name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                        @if($user->email_verified_at)
                                            <div class="text-sm text-green-600 dark:text-green-400">Verified</div>
                                        @else
                                            <div class="text-sm text-red-600 dark:text-red-400">Unverified</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ isset($user->role) && $user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : (isset($user->role) && $user->role === 'moderator' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200') }}">
                                            {{ ucfirst($user->role ?? 'user') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ isset($user->is_active) && $user->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ isset($user->is_active) && $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200"
                                               title="View User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 transition-colors duration-200"
                                               title="Edit User">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" 
                                                  method="POST" 
                                                  class="inline-block"
                                                  onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200"
                                                        title="Delete User">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No users found</p>
                                            <p class="text-gray-500 dark:text-gray-400">Get started by creating a new user.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable with basic features
            $('#users-table').DataTable({
                responsive: true,
                pageLength: 25,
                order: [[0, 'asc']], // Order by index ascending
                columnDefs: [
                    {
                        targets: [6], // Actions column
                        orderable: false,
                        searchable: false
                    }
                ],
                initComplete: function() {
                    // Apply styling after DataTable is initialized
                    setTimeout(() => {
                        applyThemeToDataTable();
                    }, 100);
                }
            });
            
            // Function to apply theme styling
            function applyThemeToDataTable() {
                // Better theme detection - check multiple sources
                const htmlElement = document.documentElement;
                const bodyElement = document.body;
                const isDarkMode = htmlElement.classList.contains('dark') || 
                                 bodyElement.classList.contains('dark') || 
                                 htmlElement.getAttribute('data-theme') === 'dark' ||
                                 localStorage.getItem('theme') === 'dark';
                
                console.log('Theme detection:', {
                    htmlHasDark: htmlElement.classList.contains('dark'),
                    bodyHasDark: bodyElement.classList.contains('dark'),
                    dataTheme: htmlElement.getAttribute('data-theme'),
                    localStorage: localStorage.getItem('theme'),
                    finalIsDarkMode: isDarkMode
                });
                
                // Remove all previous styles completely
                $('.dataTables_wrapper *').removeAttr('style');
                $('.dataTables_wrapper, .dataTables_filter input, .dataTables_length select, .dataTables_info, .dataTables_paginate .paginate_button, .dataTables_length label, .dataTables_filter label')
                    .removeClass('dark-theme light-theme')
                    .removeAttr('style');
                
                if (!isDarkMode) {
                    // LIGHT MODE STYLING
                    $('.dataTables_wrapper').css({
                        'color': '#1f2937',
                        'background-color': 'transparent'
                    });
                    
                    $('.dataTables_filter input').css({
                        'background-color': '#ffffff',
                        'border': '1px solid #d1d5db',
                        'color': '#1f2937',
                        'border-radius': '0.375rem',
                        'padding': '0.5rem 0.75rem'
                    });
                    
                    $('.dataTables_length select').css({
                        'background-color': '#ffffff',
                        'border': '1px solid #d1d5db',
                        'color': '#1f2937',
                        'border-radius': '0.375rem',
                        'padding': '0.25rem 0.5rem'
                    });
                    
                    $('.dataTables_info').css({
                        'color': '#6b7280'
                    });
                    
                    $('.dataTables_length label, .dataTables_filter label').css({
                        'color': '#1f2937'
                    });
                    
                    // Style pagination buttons for LIGHT mode
                    $('.dataTables_paginate .paginate_button').each(function() {
                        if ($(this).hasClass('current')) {
                            $(this).css({
                                'background': '#2563eb !important',
                                'border': '1px solid #2563eb !important',
                                'color': '#ffffff !important',
                                'border-radius': '0.375rem',
                                'padding': '0.5rem 0.75rem',
                                'margin-right': '0.25rem'
                            });
                        } else if ($(this).hasClass('disabled')) {
                            $(this).css({
                                'background': '#f9fafb !important',
                                'border': '1px solid #d1d5db !important',
                                'color': '#9ca3af !important',
                                'border-radius': '0.375rem',
                                'padding': '0.5rem 0.75rem',
                                'margin-right': '0.25rem',
                                'cursor': 'not-allowed'
                            });
                        } else {
                            $(this).css({
                                'background': '#ffffff !important',
                                'border': '1px solid #d1d5db !important',
                                'color': '#1f2937 !important',
                                'border-radius': '0.375rem',
                                'padding': '0.5rem 0.75rem',
                                'margin-right': '0.25rem'
                            });
                        }
                    });
                    
                    // Light mode hover effects
                    $('.dataTables_paginate .paginate_button').not('.current, .disabled').off('hover').hover(
                        function() {
                            $(this).css('background', '#f3f4f6 !important');
                        },
                        function() {
                            $(this).css('background', '#ffffff !important');
                        }
                    );
                    
                } else {
                    // DARK MODE STYLING
                    $('.dataTables_wrapper').css({
                        'color': '#e5e7eb',
                        'background-color': 'transparent'
                    });
                    
                    $('.dataTables_filter input').css({
                        'background-color': '#374151',
                        'border': '1px solid #4b5563',
                        'color': '#e5e7eb',
                        'border-radius': '0.375rem',
                        'padding': '0.5rem 0.75rem'
                    });
                    
                    $('.dataTables_length select').css({
                        'background-color': '#374151',
                        'border': '1px solid #4b5563',
                        'color': '#e5e7eb',
                        'border-radius': '0.375rem',
                        'padding': '0.25rem 0.5rem'
                    });
                    
                    $('.dataTables_info').css({
                        'color': '#9ca3af'
                    });
                    
                    $('.dataTables_length label, .dataTables_filter label').css({
                        'color': '#e5e7eb'
                    });
                    
                    // Style pagination buttons for DARK mode
                    $('.dataTables_paginate .paginate_button').each(function() {
                        if ($(this).hasClass('current')) {
                            $(this).css({
                                'background': '#2563eb !important',
                                'border': '1px solid #2563eb !important',
                                'color': '#ffffff !important',
                                'border-radius': '0.375rem',
                                'padding': '0.5rem 0.75rem',
                                'margin-right': '0.25rem'
                            });
                        } else if ($(this).hasClass('disabled')) {
                            $(this).css({
                                'background': '#374151 !important',
                                'border': '1px solid #4b5563 !important',
                                'color': '#6b7280 !important',
                                'border-radius': '0.375rem',
                                'padding': '0.5rem 0.75rem',
                                'margin-right': '0.25rem',
                                'cursor': 'not-allowed'
                            });
                        } else {
                            $(this).css({
                                'background': '#374151 !important',
                                'border': '1px solid #4b5563 !important',
                                'color': '#e5e7eb !important',
                                'border-radius': '0.375rem',
                                'padding': '0.5rem 0.75rem',
                                'margin-right': '0.25rem'
                            });
                        }
                    });
                    
                    // Dark mode hover effects
                    $('.dataTables_paginate .paginate_button').not('.current, .disabled').off('hover').hover(
                        function() {
                            $(this).css('background', '#4b5563 !important');
                        },
                        function() {
                            $(this).css('background', '#374151 !important');
                        }
                    );
                }
            }
            
            // Listen for theme changes using MutationObserver
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        setTimeout(() => {
                            applyThemeToDataTable();
                        }, 50);
                    }
                });
            });
            
            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
            
            // Also listen for pagination clicks to reapply styles
            $(document).on('click', '.dataTables_paginate .paginate_button', function() {
                setTimeout(() => {
                    applyThemeToDataTable();
                }, 100);
            });
        });
    </script>
    
    <style>
        /* Ensure proper styling override */
        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            outline: none !important;
            box-shadow: none !important;
        }
        
        .dataTables_wrapper .dataTables_filter input:focus,
        .dataTables_wrapper .dataTables_length select:focus {
            outline: 2px solid #3b82f6 !important;
            outline-offset: 2px !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            text-decoration: none !important;
            transition: all 0.2s ease-in-out !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:focus {
            outline: 2px solid #3b82f6 !important;
            outline-offset: 2px !important;
        }
        
        /* Remove default DataTables styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: none !important;
            border: none !important;
        }
        
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: none !important;
            border: none !important;
        }
    </style>
    @endpush

</x-admin-layout>
