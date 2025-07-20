@php
    $pageTitle = 'Send Notification';
@endphp

<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Send Notification') }}
            </h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.notifications.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    View All
                </a>
                <a href="{{ route('admin.notifications.overview') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Overview
                </a>
            </div>
        </div>
    </x-slot>

<div x-data="notificationForm()" class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Send Notification</h1>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Send targeted notifications to users based on their roles or send to specific individuals.</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="getRecipientCount()"></span> recipients will be notified
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700/50 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400 dark:text-green-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700/50 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400 dark:text-red-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">There were some errors:</h3>
                        <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('admin.notifications.send') }}" method="POST" class="space-y-8">
            @csrf
            
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Main Form Section -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Target Selection Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Target Audience</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Select who should receive this notification</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Targeting Method</label>
                                    <div class="mt-2 space-y-3">
                                        <label class="flex items-center">
                                            <input type="radio" name="recipient_type" value="all" x-model="targetType" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700" checked>
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">All Users</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="recipient_type" value="role" x-model="targetType" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">By Role</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="recipient_type" value="specific" x-model="targetType" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700">
                                            <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">Specific Users</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Role Selection -->
                                <div x-show="targetType === 'role'" x-transition>
                                    <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Role</label>
                                    <select name="role_name" id="role" x-model="selectedRole" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">Choose a role...</option>
                                        @if(isset($userRoles))
                                            @foreach($userRoles as $role => $count)
                                                <option value="{{ $role }}">{{ ucfirst($role) }} ({{ $count }} users)</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                <!-- User Selection -->
                                <div x-show="targetType === 'specific'" x-transition>
                                    <label for="users" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Users</label>
                                    <div class="mt-1">
                                        <select name="user_id[]" multiple size="6" class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }} ({{ $user->email }})
                                                    @if($user->roles->isNotEmpty())
                                                        - {{ $user->roles->pluck('name')->join(', ') }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Hold Ctrl (or Cmd) to select multiple users</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Content Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Message Content</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Compose your notification message</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title <span class="text-red-500">*</span></label>
                                    <input type="text" 
                                           name="title" 
                                           id="title" 
                                           x-model="title"
                                           value="{{ old('title') }}"
                                           placeholder="Enter notification title"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                           required>
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Message <span class="text-red-500">*</span></label>
                                    <textarea name="message" 
                                              id="message" 
                                              rows="4" 
                                              x-model="message"
                                              placeholder="Enter your notification message"
                                              class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"
                                              required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Action URL (Optional)</label>
                                    <input type="url" 
                                           name="url" 
                                           id="url" 
                                           x-model="actionUrl"
                                           value="{{ old('url') }}"
                                           placeholder="https://example.com/action"
                                           class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Users can click to visit this URL</p>
                                    @error('url')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Send Options Card -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Send Options</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Review and send your notification</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Send Notification
                                    </button>
                                    <a href="{{ route('admin.notifications.overview') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        Cancel
                                    </a>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    <span x-text="getRecipientCount()"></span> recipients
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Section -->
                <div class="xl:col-span-1">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 sticky top-6">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Live Preview</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">See how your notification will look</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <!-- Notification Preview -->
                            <div class="border border-gray-200 dark:border-gray-600 rounded-lg p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-md">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a3 3 0 10-6 0v3l-5 5h5a3 3 0 106 0z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-1" x-text="title || 'Notification Title'"></div>
                                        <div class="text-sm text-gray-700 dark:text-gray-300 mb-3" x-text="message || 'Your notification message will appear here...'"></div>
                                        <div x-show="actionUrl" class="mb-3">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 border border-blue-200 dark:border-blue-700">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                View Details
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ now()->format('M j, Y g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Target Summary -->
                            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700/50">
                                <div class="flex items-center mb-3">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <div class="text-sm font-semibold text-blue-900 dark:text-blue-200">Target Summary</div>
                                </div>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <div x-show="targetType === 'all'" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        All users will receive this notification
                                    </div>
                                    <div x-show="targetType === 'role'" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span x-show="selectedRole">Users with role: <strong x-text="selectedRole"></strong></span>
                                        <span x-show="!selectedRole" class="text-blue-600 dark:text-blue-400">No role selected</span>
                                    </div>
                                    <div x-show="targetType === 'specific'" class="flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        <span x-show="selectedUsers.length > 0"><strong x-text="selectedUsers.length"></strong> specific user(s) selected</span>
                                        <span x-show="selectedUsers.length === 0" class="text-blue-600 dark:text-blue-400">No users selected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function notificationForm() {
    return {
        targetType: 'all',
        selectedRole: '',
        selectedUsers: [],
        userSearch: '',
        showUserSearch: false,
        filteredUsers: [],
        allUsers: @json($users),
        title: '{{ old('title') }}',
        message: '{{ old('message') }}',
        actionUrl: '{{ old('url') }}',
        
        searchUsers() {
            if (this.userSearch.length > 1) {
                this.filteredUsers = this.allUsers.filter(user => 
                    user.name.toLowerCase().includes(this.userSearch.toLowerCase()) ||
                    user.email.toLowerCase().includes(this.userSearch.toLowerCase())
                ).filter(user => !this.selectedUsers.find(selected => selected.id === user.id));
                this.showUserSearch = true;
            } else {
                this.showUserSearch = false;
            }
        },
        
        addUser(user) {
            this.selectedUsers.push(user);
            this.userSearch = '';
            this.showUserSearch = false;
        },
        
        removeUser(userId) {
            this.selectedUsers = this.selectedUsers.filter(user => user.id !== userId);
        },
        
        getRecipientCount() {
            if (this.targetType === 'all') {
                return this.allUsers.length;
            } else if (this.targetType === 'role' && this.selectedRole) {
                @if(isset($userRoles))
                    const roleCounts = @json($userRoles);
                    return roleCounts[this.selectedRole] || 0;
                @else
                    return 0;
                @endif
            } else if (this.targetType === 'specific') {
                return this.selectedUsers.length;
            }
            return 0;
        }
    }
}
</script>
</x-admin-layout>
