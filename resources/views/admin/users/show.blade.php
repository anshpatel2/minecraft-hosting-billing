<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('User Details') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn admin-btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit User
                </a>
                <a href="{{ route('admin.users') }}" class="admin-btn admin-btn-secondary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- User Profile Header -->
            <div class="admin-card mb-8">
                <div class="flex items-center space-x-6">
                    <div class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                        <span class="text-2xl font-bold text-white">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </span>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $user->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                        <div class="flex items-center space-x-3 mt-2">
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                    Unverified
                                </span>
                            @endif
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                </svg>
                                Member since {{ $user->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Basic Information Card -->
                <div class="admin-form">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Basic Information</h4>
                    </div>

                    <div class="space-y-4">
                        <div class="admin-info-item">
                            <label class="admin-info-label">Full Name</label>
                            <p class="admin-info-value">{{ $user->name }}</p>
                        </div>

                        <div class="admin-info-item">
                            <label class="admin-info-label">Email Address</label>
                            <p class="admin-info-value">{{ $user->email }}</p>
                        </div>

                        <div class="admin-info-item">
                            <label class="admin-info-label">Email Verification</label>
                            @if($user->email_verified_at)
                                <p class="admin-info-value text-green-600 dark:text-green-400">
                                    Verified on {{ $user->email_verified_at->format('M d, Y \a\t H:i') }}
                                </p>
                            @else
                                <p class="admin-info-value text-red-600 dark:text-red-400">
                                    Not verified
                                </p>
                            @endif
                        </div>

                        <div class="admin-info-item">
                            <label class="admin-info-label">Registration Date</label>
                            <p class="admin-info-value">{{ $user->created_at->format('M d, Y \a\t H:i') }}</p>
                        </div>

                        <div class="admin-info-item">
                            <label class="admin-info-label">Last Updated</label>
                            <p class="admin-info-value">{{ $user->updated_at->format('M d, Y \a\t H:i') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Roles & Permissions Card -->
                <div class="admin-form">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Roles & Permissions</h4>
                    </div>

                    <div class="space-y-4">
                        <div class="admin-info-item">
                            <label class="admin-info-label">Assigned Roles</label>
                            <div class="mt-2">
                                @if($user->roles && $user->roles->count() > 0)
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($user->roles as $role)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"></path>
                                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"></path>
                                                </svg>
                                                {{ $role->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-gray-500 dark:text-gray-400 italic">No roles assigned</p>
                                @endif
                            </div>
                        </div>

                        @if($user->hasAnyRole() && $user->getAllPermissions()->count() > 0)
                        <div class="admin-info-item">
                            <label class="admin-info-label">Direct Permissions</label>
                            <div class="mt-2 max-h-40 overflow-y-auto">
                                <div class="grid grid-cols-1 gap-1">
                                    @foreach($user->getAllPermissions() as $permission)
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $permission->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="admin-info-item">
                            <label class="admin-info-label">Direct Permissions</label>
                            <p class="text-gray-500 dark:text-gray-400 italic mt-2">No direct permissions assigned</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Email Verification Alert -->
            @if(!$user->email_verified_at)
            <div class="admin-alert admin-alert-warning mt-8">
                <div class="flex items-start">
                    <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.95-.833-2.72 0L4.094 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                    <div class="flex-1">
                        <h4 class="font-semibold mb-2">Email Verification Required</h4>
                        <p class="mb-4">This user has not verified their email address yet. You can manually verify their email or resend the verification link.</p>
                        <div class="flex space-x-3">
                            <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="inline">
                                @csrf
                                <button type="submit" class="admin-btn admin-btn-primary" onclick="return confirm('Manually verify this user\'s email?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Manually Verify Email
                                </button>
                            </form>
                            <button type="button" class="admin-btn admin-btn-secondary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Resend Verification Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="admin-card mt-8">
                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Quick Actions</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-quick-action">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span>Edit User</span>
                    </a>
                    
                    <button class="admin-quick-action text-red-600 dark:text-red-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span>Delete User</span>
                    </button>
                    
                    <button class="admin-quick-action text-yellow-600 dark:text-yellow-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                        <span>Reset Password</span>
                    </button>
                    
                    <button class="admin-quick-action text-green-600 dark:text-green-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 7.89a2 2 0 002.83 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>Send Email</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
