<x-modern-layout title="Edit User">
    <!-- Page Header -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h1>Edit User</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Modify user information and permissions</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                    <i class="fas fa-eye"></i>
                    View User
                </a>
                <a href="{{ route('admin.users') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile Overview -->
    <div class="modern-card">
        <div class="flex items-center gap-6 mb-6">
            <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 via-orange-600 to-red-600 rounded-2xl flex items-center justify-center shadow-xl">
                <span class="text-xl font-bold text-white">
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                </span>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-1">{{ $user->name }}</h2>
                <p class="text-gray-600 dark:text-gray-400 text-lg">{{ $user->email }}</p>
                <div class="flex items-center gap-3 mt-2">
                    @if($user->email_verified_at)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                            <i class="fas fa-check-circle mr-1"></i>
                            Verified
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            Unverified
                        </span>
                    @endif
                    @if($user->roles->first())
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200">
                            <i class="fas fa-shield-alt mr-1"></i>
                            {{ ucfirst($user->roles->first()->name) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Form -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-edit"></i>
                </div>
                User Information
            </div>
        </div>
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Edit User</h3>
                        <p class="text-gray-600 dark:text-gray-400">Modify user details and permissions for {{ $user->name }}</p>
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

            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-8">
                @csrf
                @method('PUT')

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
                                   value="{{ old('name', $user->name) }}" 
                                   class="admin-form-input"
                                   placeholder="Enter full name"
                                   required>
                        </div>

                        <div class="admin-form-group">
                            <label for="email" class="admin-form-label">Email Address *</label>
                            <input type="email" 
                                   name="email" 
                                   id="email" 
                                   value="{{ old('email', $user->email) }}" 
                                   class="admin-form-input"
                                   placeholder="Enter email address"
                                   required>
                        </div>

                        <div class="admin-form-group">
                            <label for="password" class="admin-form-label">New Password</label>
                            <input type="password" 
                                   name="password" 
                                   id="password" 
                                   class="admin-form-input"
                                   placeholder="Leave blank to keep current">
                        </div>

                        <div class="admin-form-group">
                            <label for="password_confirmation" class="admin-form-label">Confirm New Password</label>
                            <input type="password" 
                                   name="password_confirmation" 
                                   id="password_confirmation" 
                                   class="admin-form-input"
                                   placeholder="Confirm new password">
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
                                <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Administrator</option>
                                <option value="moderator" {{ old('role', $user->role ?? '') == 'moderator' ? 'selected' : '' }}>Moderator</option>
                                <option value="user" {{ old('role', $user->role ?? '') == 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>

                        <div class="admin-form-group">
                            <label for="status" class="admin-form-label">Status *</label>
                            <select name="status" id="status" class="admin-form-select" required>
                                <option value="">Select status</option>
                                <option value="active" {{ old('status', $user->status ?? '') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="suspended" {{ old('status', $user->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                            </select>
                        </div>

                        <div class="admin-form-group">
                            <label class="admin-form-checkbox">
                                <input type="checkbox" 
                                       name="email_verified" 
                                       value="1" 
                                       {{ $user->email_verified_at ? 'checked' : '' }}>
                                <span class="admin-form-checkbox-label">Mark email as verified</span>
                            </label>
                        </div>

                        <!-- Current Status Display -->
                        <div class="admin-form-group">
                            <label class="admin-form-label">Current Status</label>
                            <div class="flex flex-wrap gap-2 mt-2">
                                @if($user->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        Email Verified
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                        </svg>
                                        Email Not Verified
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

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save mr-2"></i>
                        Update User
                    </button>
                </div>
            </form>
    </div>
</x-modern-layout>
