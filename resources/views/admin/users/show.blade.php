<x-modern-layout title="User Details">
    <!-- Page Header -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon">
                    <i class="fas fa-user"></i>
                </div>
                <div>
                    <h1>User Management</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage user information</p>
                </div>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Edit User
                </a>
                <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile Overview -->
    <div class="modern-card animate-fadeInUp">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 via-purple-600 to-indigo-600 rounded-2xl flex items-center justify-center shadow-xl">
                    <span class="text-2xl font-bold text-white">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-2">{{ $user->name }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 text-lg mb-3">{{ $user->email }}</p>
                    <div class="flex flex-wrap gap-3">
                        @if($user->email_verified_at)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-green-500 to-emerald-600 text-white shadow-md">
                                <i class="fas fa-check-circle mr-2"></i>
                                Email Verified
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-red-500 to-pink-600 text-white shadow-md">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                Unverified
                            </span>
                        @endif
                        
                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-md">
                            <i class="fas fa-calendar mr-2"></i>
                            Member since {{ $user->created_at->format('M Y') }}
                        </span>

                        @if($user->roles->first())
                            <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-semibold bg-gradient-to-r from-purple-500 to-pink-600 text-white shadow-md">
                                <i class="fas fa-shield-alt mr-2"></i>
                                {{ ucfirst($user->roles->first()->name) }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Status Actions -->
            <div class="flex flex-col gap-2">
                @if(!$user->email_verified_at)
                    <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Manually verify this user\'s email?')">
                            <i class="fas fa-check"></i>
                            Verify Email
                        </button>
                    </form>
                @endif
                
                <button onclick="resetUserPassword()" class="btn btn-warning btn-sm">
                    <i class="fas fa-key"></i>
                    Reset Password
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Account Status</span>
                <div class="stat-icon" style="background: var(--gradient-success);">
                    <i class="fas fa-user-check"></i>
                </div>
            </div>
            <div class="stat-value">
                @if($user->email_verified_at)
                    <span class="text-green-600">Active</span>
                @else
                    <span class="text-red-600">Pending</span>
                @endif
            </div>
            <div class="stat-change {{ $user->email_verified_at ? 'positive' : 'negative' }}">
                <i class="fas {{ $user->email_verified_at ? 'fa-arrow-up' : 'fa-exclamation-triangle' }}"></i>
                {{ $user->email_verified_at ? 'Verified Account' : 'Needs Verification' }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Last Activity</span>
                <div class="stat-icon" style="background: var(--gradient-primary);">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stat-value text-lg">{{ $user->updated_at->diffForHumans() }}</div>
            <div class="stat-change">
                <i class="fas fa-calendar"></i>
                {{ $user->updated_at->format('M d, Y') }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">User Role</span>
                <div class="stat-icon" style="background: var(--gradient-secondary);">
                    <i class="fas fa-shield-alt"></i>
                </div>
            </div>
            <div class="stat-value text-lg">
                @if($user->roles->first())
                    {{ ucfirst($user->roles->first()->name) }}
                @else
                    No Role
                @endif
            </div>
            <div class="stat-change">
                <i class="fas fa-users"></i>
                Role Assignment
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-title">Registration</span>
                <div class="stat-icon" style="background: var(--gradient-warning);">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <div class="stat-value text-lg">{{ $user->created_at->diffForHumans() }}</div>
            <div class="stat-change">
                <i class="fas fa-calendar-plus"></i>
                {{ $user->created_at->format('M d, Y') }}
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Basic Information -->
        <div class="modern-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-icon" style="background: var(--gradient-primary);">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    Basic Information
                </div>
            </div>

            <div class="space-y-6">
                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Full Name</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</p>
                    </div>
                    <i class="fas fa-user text-blue-500"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Email Address</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $user->email }}</p>
                    </div>
                    <i class="fas fa-envelope text-green-500"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Email Verification</label>
                        @if($user->email_verified_at)
                            <p class="text-lg font-semibold text-green-600">
                                Verified on {{ $user->email_verified_at->format('M d, Y \a\t H:i') }}
                            </p>
                        @else
                            <p class="text-lg font-semibold text-red-600">
                                Not verified
                            </p>
                        @endif
                    </div>
                    <i class="fas {{ $user->email_verified_at ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }}"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Registration Date</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                    <i class="fas fa-calendar-plus text-purple-500"></i>
                </div>

                <div class="flex justify-between items-center p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                    <div>
                        <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Last Updated</label>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $user->updated_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                    <i class="fas fa-clock text-orange-500"></i>
                </div>
            </div>
        </div>

        <!-- Roles & Permissions -->
        <div class="modern-card">
            <div class="card-header">
                <div class="card-title">
                    <div class="card-icon" style="background: var(--gradient-secondary);">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    Roles & Permissions
                </div>
            </div>

            <div class="space-y-6">
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-3 block">Assigned Roles</label>
                    @if($user->roles && $user->roles->count() > 0)
                        <div class="grid gap-3">
                            @foreach($user->roles as $role)
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 dark:from-purple-900 dark:to-pink-900 rounded-lg border border-purple-200 dark:border-purple-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-600 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-crown text-white text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900 dark:text-gray-100">{{ ucfirst($role->name) }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Administrative role</p>
                                        </div>
                                    </div>
                                    <span class="px-3 py-1 bg-purple-100 dark:bg-purple-800 text-purple-800 dark:text-purple-200 rounded-full text-xs font-semibold">
                                        Active
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-user-slash text-4xl text-gray-300 dark:text-gray-600 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">No roles assigned</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500">Contact an administrator to assign roles</p>
                        </div>
                    @endif
                </div>

                @if($user->hasAnyRole() && $user->getAllPermissions()->count() > 0)
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-3 block">Direct Permissions</label>
                    <div class="max-h-40 overflow-y-auto space-y-2">
                        @foreach($user->getAllPermissions() as $permission)
                            <div class="flex items-center gap-3 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <i class="fas fa-key text-yellow-500 text-sm"></i>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $permission->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div>
                    <label class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide mb-3 block">Direct Permissions</label>
                    <div class="text-center py-6">
                        <i class="fas fa-lock text-3xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No direct permissions assigned</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Email Verification Alert -->
    @if(!$user->email_verified_at)
    <div class="modern-card bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900 dark:to-orange-900 border border-amber-200 dark:border-amber-700">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-600 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-exclamation-triangle text-white text-lg"></i>
            </div>
            <div class="flex-1">
                <h4 class="text-lg font-bold text-amber-800 dark:text-amber-200 mb-2">Email Verification Required</h4>
                <p class="text-amber-700 dark:text-amber-300 mb-4">This user has not verified their email address yet. You can manually verify their email or resend the verification link.</p>
                <div class="flex flex-wrap gap-3">
                    <form method="POST" action="{{ route('admin.users.verify', $user) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Manually verify this user\'s email?')">
                            <i class="fas fa-check"></i>
                            Manually Verify Email
                        </button>
                    </form>
                    <button type="button" class="btn btn-secondary">
                        <i class="fas fa-envelope"></i>
                        Resend Verification Email
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon" style="background: var(--gradient-success);">
                    <i class="fas fa-bolt"></i>
                </div>
                Quick Actions
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.users.edit', $user) }}" class="group relative p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900 dark:to-indigo-900 rounded-xl border border-blue-200 dark:border-blue-700 hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-edit text-white text-lg"></i>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Edit User</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Modify user information</p>
            </a>
            
            <button onclick="confirmDeleteUser()" class="group relative p-6 bg-gradient-to-br from-red-50 to-pink-50 dark:from-red-900 dark:to-pink-900 rounded-xl border border-red-200 dark:border-red-700 hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-trash text-white text-lg"></i>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Delete User</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Remove user account</p>
            </button>
            
            <button onclick="resetUserPassword()" class="group relative p-6 bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900 dark:to-orange-900 rounded-xl border border-yellow-200 dark:border-yellow-700 hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-key text-white text-lg"></i>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Reset Password</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Generate new password</p>
            </button>
            
            <a href="mailto:{{ $user->email }}" class="group relative p-6 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900 dark:to-emerald-900 rounded-xl border border-green-200 dark:border-green-700 hover:shadow-lg transition-all duration-300 text-center">
                <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-envelope text-white text-lg"></i>
                </div>
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-1">Send Email</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">Contact user directly</p>
            </a>
        </div>
    </div>

    <!-- User Activity Timeline (New Feature) -->
    <div class="modern-card">
        <div class="card-header">
            <div class="card-title">
                <div class="card-icon" style="background: var(--gradient-warning);">
                    <i class="fas fa-history"></i>
                </div>
                Activity Timeline
            </div>
        </div>

        <div class="space-y-4">
            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-plus text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">Account Created</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">User registered on {{ $user->created_at->format('M d, Y \a\t H:i') }}</p>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
            </div>

            @if($user->email_verified_at)
            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">Email Verified</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Email verification completed on {{ $user->email_verified_at->format('M d, Y \a\t H:i') }}</p>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email_verified_at->diffForHumans() }}</span>
            </div>
            @endif

            <div class="flex items-start gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="w-10 h-10 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-edit text-white text-sm"></i>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-gray-900 dark:text-gray-100">Profile Updated</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Last profile update on {{ $user->updated_at->format('M d, Y \a\t H:i') }}</p>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $user->updated_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</x-modern-layout>

<script>
function confirmDeleteUser() {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        // Create a form and submit it for user deletion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.users.destroy", $user) }}';
        
        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);
        
        // Add method spoofing for DELETE
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

function resetUserPassword() {
    if (confirm('Are you sure you want to reset this user\'s password? A new temporary password will be generated.')) {
        // Add your password reset logic here
        fetch('{{ route("admin.users.reset-password", $user) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Password reset successfully!\n\nNew temporary password: ${data.new_password}\n\nPlease save this password and share it with the user securely.`);
            } else {
                alert('Error resetting password. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error resetting password. Please try again.');
        });
    }
}
</script>
