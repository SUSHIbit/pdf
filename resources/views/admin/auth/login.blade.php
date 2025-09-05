<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" x-bind:class="{ 'dark': isDark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - {{ config('app.name', 'AI Document Q&A') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function themeManager() {
            return {
                isDark: false,
                
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        this.isDark = true;
                    }
                    this.updateTheme();
                },
                
                toggleTheme() {
                    this.isDark = !this.isDark;
                    this.updateTheme();
                },
                
                updateTheme() {
                    localStorage.setItem('theme', this.isDark ? 'dark' : 'light');
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 min-h-screen transition-colors duration-300">
    <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4">
        <!-- Header with Logo and Theme Toggle -->
        <div class="w-full max-w-md mb-8">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="text-xl md:text-2xl font-bold text-text-primary dark:text-text-dark-primary transition-colors">
                    <span class="text-primary-600 dark:text-primary-400">Admin</span> Panel
                </div>
                
                <!-- Theme Toggle -->
                <button @click="toggleTheme()" 
                        class="p-2 rounded-lg bg-surface dark:bg-surface-dark text-text-secondary dark:text-text-dark-secondary hover:bg-primary-100 dark:hover:bg-primary-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 shadow-sm border border-border dark:border-border-dark"
                        aria-label="Toggle theme">
                    <svg x-show="isDark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <svg x-show="!isDark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="w-full max-w-md">
            <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 sm:p-8 transition-all duration-300 animate-fade-in">
                <!-- Page Title -->
                <div class="text-center mb-8">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-800 mb-4 transition-colors">
                        <svg class="h-6 w-6 text-primary-600 dark:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                        Admin Login
                    </h1>
                    <p class="text-text-secondary dark:text-text-dark-secondary text-sm transition-colors">
                        Sign in to access the admin panel
                    </p>
                </div>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="mb-6 p-4 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-xl animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-success-600 dark:text-success-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <p class="text-sm font-medium text-success-800 dark:text-success-300">
                                {{ session('success') }}
                            </p>
                        </div>
                    </div>
                @endif

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

                <!-- Login Form -->
                <form method="POST" action="{{ route('admin.login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div class="space-y-2"></div>
                    <label for="email" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            Email Address
                        </label>
                        <div class="relative">
                            <input id="email" 
                                   name="email" 
                                   type="email" 
                                   autocomplete="email"
                                   required
                                   autofocus
                                   value="{{ old('email') }}"
                                   class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('email') border-error-500 dark:border-error-400 @enderror" 
                                   placeholder="Enter your admin email">
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

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                            Password
                        </label>
                        <div class="relative" x-data="{ showPassword: false }">
                            <input id="password" 
                                   name="password" 
                                   :type="showPassword ? 'text' : 'password'"
                                   autocomplete="current-password"
                                   required
                                   class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('password') border-error-500 dark:border-error-400 @enderror" 
                                   placeholder="Enter your password">
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

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" 
                               name="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-primary-600 dark:text-primary-500 bg-surface dark:bg-surface-dark border-border dark:border-border-dark rounded focus:ring-primary-500 dark:focus:ring-primary-400 transition-colors">
                        <label for="remember_me" class="ml-2 block text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                            Remember me
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" 
                            class="w-full flex justify-center items-center px-4 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-semibold rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-surface dark:focus:ring-offset-surface-dark transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] group">
                        <svg class="w-5 h-5 mr-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Sign In to Admin Panel
                    </button>
                </form>

                <!-- Footer -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">
                        Secure admin access to AI Document Q&A
                    </p>
                    <a href="{{ route('home') }}" class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors mt-2 inline-block">
                        ← Back to main site
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>