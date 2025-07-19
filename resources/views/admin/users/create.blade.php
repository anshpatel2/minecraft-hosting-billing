<x-admin-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create New User') }}
            </h2>
            <a href="{{ route('admin.users') }}" class="admin-btn admin-btn-secondary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="admin-card mb-8">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Add New User</h3>
                        <p class="text-gray-600 dark:text-gray-400">Create a new user account and assign roles</p>
                    </div>
                </div>
            </div>

            <!-- Display Validation Errors -->
            @if ($errors->any())
                <div class="admin-alert admin-alert-error mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.95-.833-2.72 0L4.094 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <h4 class="font-semibold mb-2">Please correct the following errors:</h4>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-8">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- User Information Card -->
                    <div class="admin-form">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">User Information</h4>
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="name" class="admin-form-label">Full Name *</label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}" 
                                   class="admin-form-input"
                                   placeholder="Enter full name"
                                   required>
                        </div>

                        <div class="admin-form-group">
                            <label for="email" class="admin-form-label">Email Address *</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email') }}" 
                                   class="admin-form-input"
                                   placeholder="Enter email address"
                                   required>
                        </div>

                        <div class="admin-form-group">
                            <label for="password" class="admin-form-label">Password *</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="admin-form-input"
                                   placeholder="Enter password"
                                   required>
                        </div>

                        <div class="admin-form-group">
                            <label for="password_confirmation" class="admin-form-label">Confirm Password *</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="admin-form-input"
                                   placeholder="Confirm password"
                                   required>
                        </div>
                    </div>

                    <!-- Role & Status Card -->
                    <div class="admin-form">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Role & Status</h4>
                        </div>
                        
                        <div class="admin-form-group">
                            <label for="role" class="admin-form-label">Role *</label>
                            <select name="role" id="role" class="admin-form-select" required>
                                <option value="">Select a role</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="moderator" {{ old('role') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <div class="admin-form-group">
                            <label for="status" class="admin-form-label">Status *</label>
                            <select name="status" id="status" class="admin-form-select" required>
                                <option value="">Select status</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-checkbox">
                                <input type="checkbox" 
                                       name="email_verified" 
                                       value="1" 
                                       {{ old('email_verified') ? 'checked' : '' }}>
                                <span class="admin-form-checkbox-label">Mark email as verified</span>
                            </label>
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-checkbox">
                                <input type="checkbox" 
                                       name="send_welcome_email" 
                                       value="1" 
                                       {{ old('send_welcome_email') ? 'checked' : '' }} 
                                       checked>
                                <span class="admin-form-checkbox-label">Send welcome email</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="admin-card">
                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('admin.users') }}" class="admin-btn admin-btn-secondary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="admin-btn admin-btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create User
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
