@extends('layouts.guest')

@section('content')
    <!-- Page Title -->
    <div class="text-center mb-8">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                Join Wizardry
            </h1>
            <p class="text-text-secondary dark:text-text-dark-secondary text-sm transition-colors">
                Get started with 5 free credits
            </p>
        </div>
        <!-- Credit Indicator -->
        <div class="mt-3 inline-flex items-center px-3 py-1 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-full">
            <svg class="w-4 h-4 text-success-600 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
            </svg>
            <span class="text-xs font-medium text-success-800 dark:text-success-300">
                5 Free Credits Included
            </span>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('error'))
        <div class="mb-6 p-4 bg-error-50 dark:bg-error-900/20 border border-error-200 dark:border-error-800 rounded-xl animate-fade-in">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-error-600 dark:text-error-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                </svg>
                <p class="text-sm font-medium text-error-800 dark:text-error-300">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    @endif

    <!-- Google OAuth Button -->
    <div class="mb-6">
        <a href="{{ route('auth.google') }}" 
           class="w-full inline-flex justify-center items-center px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl shadow-sm font-medium text-sm text-text-secondary dark:text-text-dark-secondary hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:border-primary-300 dark:hover:border-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 group">
            <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform duration-200" viewBox="0 0 24 24">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            Sign up with Google
        </a>
    </div>

    <!-- Divider -->
    <div class="relative mb-6">
        <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-border dark:border-border-dark"></div>
        </div>
        <div class="relative flex justify-center text-sm">
            <span class="px-3 bg-surface dark:bg-surface-dark text-text-tertiary dark:text-text-dark-tertiary">
                Or sign up with email
            </span>
        </div>
    </div>

    <!-- Registration Form -->
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <!-- Name Field -->
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                Full Name
            </label>
            <div class="relative">
                <input id="name" 
                       name="name" 
                       type="text" 
                       autocomplete="name"
                       required
                       autofocus
                       value="{{ old('name') }}"
                       class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('name') border-error-500 dark:border-error-400 @enderror" 
                       placeholder="Enter your full name">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
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
            <div class="relative">
                <input id="email" 
                       name="email" 
                       type="email" 
                       autocomplete="email"
                       required
                       value="{{ old('email') }}"
                       class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('email') border-error-500 dark:border-error-400 @enderror" 
                       placeholder="Enter your email">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                    </svg>
                </div>
            </div>
            @error('email')
                <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
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
                       autocomplete="tel"
                       value="{{ old('phone') }}"
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

        <!-- Password Field -->
        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                Password
            </label>
            <div class="relative" x-data="{ showPassword: false }">
                <input id="password" 
                       name="password" 
                       :type="showPassword ? 'text' : 'password'"
                       autocomplete="new-password"
                       required
                       class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('password') border-error-500 dark:border-error-400 @enderror" 
                       placeholder="Create a secure password">
                <button type="button" 
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center">
                    <svg x-show="!showPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    <svg x-show="showPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                    </svg>
                    {{ $message }}
                </p>
            @enderror
</div>
    <!-- Confirm Password Field -->
    <div class="space-y-2">
        <label for="password_confirmation" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
            Confirm Password
        </label>
        <div class="relative" x-data="{ showConfirmPassword: false }">
            <input id="password_confirmation" 
                   name="password_confirmation" 
                   :type="showConfirmPassword ? 'text' : 'password'"
                   autocomplete="new-password"
                   required
                   class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('password_confirmation') border-error-500 dark:border-error-400 @enderror" 
                   placeholder="Confirm your password">
            <button type="button" 
                    @click="showConfirmPassword = !showConfirmPassword"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <svg x-show="!showConfirmPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
                <svg x-show="showConfirmPassword" class="h-5 w-5 text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                </svg>
            </button>
        </div>
        @error('password_confirmation')
            <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                </svg>
                {{ $message }}
            </p>
        @enderror
    </div>

    <!-- Password Requirements -->
    <div class="text-xs text-text-tertiary dark:text-text-dark-tertiary space-y-1">
        <p class="font-medium">Password must contain:</p>
        <ul class="ml-4 space-y-1">
            <li class="flex items-center">
                <svg class="w-3 h-3 text-text-tertiary dark:text-text-dark-tertiary mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
                At least 8 characters
            </li>
        </ul>
    </div>

    <!-- Submit Button -->
    <button type="submit" 
            class="w-full flex justify-center items-center px-4 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-semibold rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-surface dark:focus:ring-offset-surface-dark transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] group">
        <svg class="w-5 h-5 mr-2 group-hover:rotate-12 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
        </svg>
        Create Account
    </button>
</form>

<!-- Sign In Link -->
<div class="mt-8 text-center">
    <p class="text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
        Already have an account?
        <a href="{{ route('login') }}" 
           class="font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors ml-1 group">
            Sign in
            <svg class="w-4 h-4 inline ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
            </svg>
        </a>
    </p>
    <div class="mt-4 text-xs text-text-tertiary dark:text-text-dark-tertiary">
        Start creating study materials instantly
    </div>
</div>
@endsection