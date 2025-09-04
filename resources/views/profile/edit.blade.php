@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                Profile Settings
            </h1>
            <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">
                Manage your account information and security settings.
            </p>
        </div>

        <div class="space-y-6">
            <!-- Profile Information Section -->
            <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-colors">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                        Profile Information
                    </h2>
                    <p class="text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                        Update your account's profile information and email address.
                    </p>
                </div>

                <!-- Email Verification Form (Hidden) -->
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                <!-- Profile Update Form -->
                <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                    @csrf
                    @method('patch')

                    <!-- Name Field -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            Full Name
                        </label>
                        <input id="name" 
                               name="name" 
                               type="text" 
                               value="{{ old('name', $user->name) }}"
                               required 
                               autofocus 
                               autocomplete="name"
                               class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('name') border-error-500 dark:border-error-400 @enderror">
                        @error('name')
                            <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            Email Address
                        </label>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ old('email', $user->email) }}"
                               required 
                               autocomplete="username"
                               class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('email') border-error-500 dark:border-error-400 @enderror">
                        @error('email')
                            <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="mt-3 p-3 bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg">
                                <p class="text-sm text-warning-800 dark:text-warning-300 mb-2">
                                    Your email address is unverified.
                                </p>
                                <button form="send-verification" 
                                        class="text-sm font-medium text-warning-600 dark:text-warning-400 hover:text-warning-700 dark:hover:text-warning-300 transition-colors">
                                    Click here to re-send the verification email.
                                </button>
                            </div>

                            @if (session('status') === 'verification-link-sent')
                                <div class="mt-2 p-3 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-lg">
                                    <p class="text-sm text-success-800 dark:text-success-300">
                                        A new verification link has been sent to your email address.
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <!-- Phone Field -->
                    <div class="space-y-2">
                        <label for="phone" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            Phone Number <span class="text-text-tertiary dark:text-text-dark-tertiary text-xs">(Optional)</span>
                        </label>
                        <div class="relative">
                            <input id="phone" 
                                   name="phone" 
                                   type="tel" 
                                   value="{{ old('phone', $user->phone ? $user->formatted_phone : '') }}"
                                   autocomplete="tel"
                                   class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('phone') border-error-500 dark:border-error-400 @enderror"
                                   placeholder="e.g., +60123456789 or 0123456789">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                        </div>
                        @error('phone')
                            <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">
                            Enter your Malaysian phone number. We'll use this for payment notifications.
                        </p>
                    </div>

                    <div class="flex items-center justify-end space-x-3 pt-4">
                        <button type="submit" 
                                class="px-6 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105">
                            Save Changes
                        </button>

                        @if (session('status') === 'profile-updated')
                            <div class="inline-flex items-center px-3 py-1 bg-success-100 dark:bg-success-900/30 border border-success-200 dark:border-success-800 rounded-full animate-fade-in">
                                <svg class="w-4 h-4 text-success-600 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-success-800 dark:text-success-300">Saved!</span>
                            </div>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Update Password Section -->
            <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-colors">
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                        Update Password
                    </h2>
                    <p class="text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                        Ensure your account is using a long, random password to stay secure.
                    </p>
                </div>

                <form method="post" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <!-- Current Password -->
                    <div class="space-y-2" x-data="{ showCurrentPassword: false }">
                        <label for="update_password_current_password" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            Current Password
                        </label>
                        <div class="relative">
                            <input id="update_password_current_password" 
                                   name="current_password" 
                                   :type="showCurrentPassword ? 'text' : 'password'"
                                   autocomplete="current-password"
                                   class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('current_password', 'updatePassword') border-error-500 dark:border-error-400 @enderror">
                            <button type="button" 
                                    @click="showCurrentPassword = !showCurrentPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg x-show="!showCurrentPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showCurrentPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        @error('current_password', 'updatePassword')
                            <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="space-y-2" x-data="{ showNewPassword: false }">
                        <label for="update_password_password" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            New Password
                        </label>
                        <div class="relative">
                            <input id="update_password_password" 
                                   name="password" 
                                   :type="showNewPassword ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('password', 'updatePassword') border-error-500 dark:border-error-400 @enderror">
                            <button type="button" 
                                    @click="showNewPassword = !showNewPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg x-show="!showNewPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showNewPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                </svg>
                            </button>
                        </div>
                        @error('password', 'updatePassword')
                            <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2" x-data="{ showConfirmPassword: false }">
                        <label for="update_password_password_confirmation" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            Confirm Password
                        </label>
                        <div class="relative">
                            <input id="update_password_password_confirmation" 
                                   name="password_confirmation" 
                                   :type="showConfirmPassword ? 'text' : 'password'"
                                   autocomplete="new-password"
                                   class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('password_confirmation', 'updatePassword') border-error-500 dark:border-error-400 @enderror">
                            <button type="button" 
                                    @click="showConfirmPassword = !showConfirmPassword"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <svg x-show="!showConfirmPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="showConfirmPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
</svg>
</button>
</div>
@error('password_confirmation', 'updatePassword')
<p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
<svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
</svg>
{{ $message }}
</p>
@enderror
</div>
                <div class="flex items-center justify-end space-x-3 pt-4">
                    <button type="submit" 
                            class="px-6 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105">
                        Update Password
                    </button>

                    @if (session('status') === 'password-updated')
                        <div class="inline-flex items-center px-3 py-1 bg-success-100 dark:bg-success-900/30 border border-success-200 dark:border-success-800 rounded-full animate-fade-in">
                            <svg class="w-4 h-4 text-success-600 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            <span class="text-sm font-medium text-success-800 dark:text-success-300">Updated!</span>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Delete Account Section -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-error-200 dark:border-error-800 p-6 transition-colors">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-error-600 dark:text-error-400 mb-2 transition-colors">
                    Delete Account
                </h2>
                <p class="text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                    Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
                </p>
            </div>

            <button x-data="" 
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="px-6 py-3 bg-error-600 hover:bg-error-700 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105">
                Delete Account
            </button>
        </div>
    </div>
</div>
</div>
<!-- Delete Account Modal -->
<div x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail == 'confirm-user-deletion' ? show = true : null"
     x-on:close-modal.window="$event.detail == 'confirm-user-deletion' ? show = false : null"
     x-show="show"
     class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 p-4 transition-colors"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl max-w-md w-full p-6 border border-border dark:border-border-dark transition-colors animate-scale-in"
             x-on:click.away="show = false">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-error-100 dark:bg-error-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-error-600 dark:text-error-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary transition-colors">Delete Account</h3>
                </div>
            </div>
        <p class="text-text-secondary dark:text-text-dark-secondary mb-6 transition-colors">
            Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
        </p>

        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="mb-6">
                <label for="password" class="sr-only">Password</label>
                <input id="password"
                       name="password"
                       type="password"
                       placeholder="Enter your password"
                       class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-error-500 dark:focus:ring-error-400 focus:border-transparent transition-all duration-200 @error('password', 'userDeletion') border-error-500 dark:border-error-400 @enderror">
                @error('password', 'userDeletion')
                    <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" 
                        x-on:click="show = false"
                        class="px-4 py-2 text-text-secondary dark:text-text-dark-secondary border border-border dark:border-border-dark rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-error-600 hover:bg-error-700 text-white rounded-xl transition-colors">
                    Delete Account
                </button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection