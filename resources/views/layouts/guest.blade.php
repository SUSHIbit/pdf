<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" x-bind:class="{ 'dark': isDark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AI Document Q&A') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function themeManager() {
            return {
                isDark: false,
                
                init() {
                    // Check for saved theme preference or default to system preference
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
                <a href="/" class="text-xl md:text-2xl font-bold text-text-primary dark:text-text-dark-primary transition-colors">
                    AI Document Q&A
                </a>
                
                <!-- Theme Toggle -->
                <button @click="toggleTheme()" 
                        class="p-2 rounded-lg bg-surface dark:bg-surface-dark text-text-secondary dark:text-text-dark-secondary hover:bg-primary-100 dark:hover:bg-primary-800 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 shadow-sm border border-border dark:border-border-dark"
                        aria-label="Toggle theme">
                    <!-- Sun Icon (shown in dark mode) -->
                    <svg x-show="isDark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>
                    </svg>
                    <!-- Moon Icon (shown in light mode) -->
                    <svg x-show="!isDark" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="w-full max-w-md">
            <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 sm:p-8 transition-all duration-300 animate-fade-in">
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </div>
        </div>

        <!-- Footer Links -->
        <div class="mt-6 text-center space-y-2">
            <div class="flex items-center justify-center space-x-4 text-sm text-text-tertiary dark:text-text-dark-tertiary">
                <a href="/" class="hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors">
                    Home
                </a>
                <span class="w-1 h-1 bg-text-tertiary dark:bg-text-dark-tertiary rounded-full"></span>
                <span class="text-xs">Secure & Private</span>
            </div>
            <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">
                Transform your documents into interactive learning materials
            </p>
        </div>
    </div>
</body>
</html>