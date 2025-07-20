<x-modern-layout title="Profile Settings">
    <!-- Profile Header -->
    <div class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-purple-600 to-indigo-600 mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="text-center">
                <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl font-bold text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <h1 class="text-4xl font-bold text-white mb-3">Profile Settings</h1>
                <p class="text-blue-100 text-lg">Manage your account information and preferences</p>
            </div>
        </div>
    </div>

    <!-- Profile Content -->
    <div class="space-y-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Profile Information -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-user text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Profile Information</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Update your account's profile information and email address</p>
                        </div>
                    </div>
                </div>
                <div class="modern-card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="modern-card">
                <div class="modern-card-header">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-lock text-yellow-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Update Password</h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">Ensure your account is using a long, random password to stay secure</p>
                        </div>
                    </div>
                </div>
                <div class="modern-card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="modern-card border-red-200 dark:border-red-800">
                <div class="modern-card-header bg-red-50 dark:bg-red-900/20">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-red-500 bg-opacity-20 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-trash-alt text-red-600 text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-red-900 dark:text-red-100">Delete Account</h2>
                            <p class="text-red-600 dark:text-red-400 mt-1">Permanently delete your account and all associated data</p>
                        </div>
                    </div>
                </div>
                <div class="modern-card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-modern-layout>
