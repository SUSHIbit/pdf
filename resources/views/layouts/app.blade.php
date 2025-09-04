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
                mobileMenuOpen: false,
                
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
                },

                toggleMobileMenu() {
                    this.mobileMenuOpen = !this.mobileMenuOpen;
                    // Prevent body scroll when menu is open
                    if (this.mobileMenuOpen) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                },

                closeMobileMenu() {
                    this.mobileMenuOpen = false;
                    document.body.style.overflow = '';
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-background dark:bg-background-dark transition-colors duration-300">
    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 transition-colors duration-300">
        <!-- Fixed Header -->
        <header class="fixed inset-x-0 top-0 z-50 bg-surface/95 dark:bg-surface-dark/95 backdrop-blur-sm border-b border-border dark:border-border-dark shadow-sm transition-colors duration-300">
            <nav class="flex items-center justify-between p-4 lg:px-8 max-w-7xl mx-auto">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xl md:text-2xl font-bold text-text-primary dark:text-text-dark-primary transition-colors">
                        AI Document Q&A
                    </a>
                </div>
                
                <!-- Desktop Navigation (hidden on mobile) -->
                <div class="hidden md:flex items-center space-x-4">
                    <!-- Credits Display -->
                    <div class="flex items-center space-x-2 bg-primary-100 dark:bg-primary-800 px-3 py-2 rounded-full">
                        <svg class="w-4 h-4 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        </svg>
                        <span class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ auth()->user()->credits }} credits</span>
                    </div>

                    <!-- Navigation Links -->
                    <a href="{{ route('dashboard') }}" class="text-base font-medium text-text-primary dark:text-text-dark-primary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors px-3 py-2 {{ request()->routeIs('dashboard') ? 'bg-primary-100 dark:bg-primary-800 rounded-lg' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('payment.packages') }}" class="text-base font-medium text-text-primary dark:text-text-dark-primary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors px-3 py-2 {{ request()->routeIs('payment.*') ? 'bg-primary-100 dark:bg-primary-800 rounded-lg' : '' }}">
                        Buy Credits
                    </a>

                    <!-- Theme Toggle Button -->
                    <button @click="toggleTheme()" 
                            class="p-2 rounded-lg bg-primary-100 dark:bg-primary-800 text-text-secondary dark:text-text-dark-secondary hover:bg-primary-200 dark:hover:bg-primary-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
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

                    <!-- User Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 text-text-primary dark:text-text-dark-primary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors p-2 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-800">
                            <div class="w-8 h-8 bg-primary-600 dark:bg-primary-500 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                            </div>
                            <span class="hidden lg:block">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-surface dark:bg-surface-dark rounded-xl shadow-lg py-2 border border-border dark:border-border-dark">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">Profile</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation (visible on mobile) -->
                <div class="flex md:hidden items-center space-x-2">
                    <!-- Credits Display (Mobile) -->
                    <div class="flex items-center space-x-1 bg-primary-100 dark:bg-primary-800 px-2 py-1 rounded-full">
                        <svg class="w-3 h-3 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        </svg>
                        <span class="text-xs font-medium text-primary-700 dark:text-primary-300">{{ auth()->user()->credits }}</span>
                    </div>

                    <!-- Theme Toggle Button (Mobile) -->
                    <button @click="toggleTheme()" 
                            class="p-2 rounded-lg bg-primary-100 dark:bg-primary-800 text-text-secondary dark:text-text-dark-secondary hover:bg-primary-200 dark:hover:bg-primary-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
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

                    <!-- Hamburger Menu Button -->
                    <button @click="toggleMobileMenu()" 
                            class="p-2 rounded-lg bg-primary-100 dark:bg-primary-800 text-text-secondary dark:text-text-dark-secondary hover:bg-primary-200 dark:hover:bg-primary-700 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400"
                            aria-label="Toggle menu"
                            :aria-expanded="mobileMenuOpen">
                        <!-- Hamburger Icon (when menu is closed) -->
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <!-- X Icon (when menu is open) -->
                        <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </nav>

            <!-- Mobile Menu Dropdown -->
            <div x-show="mobileMenuOpen" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 @click.away="closeMobileMenu()"
                 class="md:hidden bg-surface/98 dark:bg-surface-dark/98 backdrop-blur-md border-b border-border dark:border-border-dark shadow-lg">
                <div class="px-4 py-3 space-y-1 max-w-7xl mx-auto">
                    <a href="{{ route('dashboard') }}" 
                       @click="closeMobileMenu()"
                       class="block px-4 py-3 text-base font-medium text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors {{ request()->routeIs('dashboard') ? 'bg-primary-100 dark:bg-primary-800' : '' }}">
                        <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('payment.packages') }}" 
                       @click="closeMobileMenu()"
                       class="block px-4 py-3 text-base font-medium text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors {{ request()->routeIs('payment.*') ? 'bg-primary-100 dark:bg-primary-800' : '' }}">
                        <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        </svg>
                        Buy Credits
                    </a>
                    
                    <hr class="border-border dark:border-border-dark my-3">
                    
                    <!-- User Info -->
                    <div class="px-4 py-2 text-sm text-text-secondary dark:text-text-dark-secondary">
                        {{ auth()->user()->name }} • {{ auth()->user()->email }}
                    </div>
                    
                    <a href="{{ route('profile.edit') }}" 
                       @click="closeMobileMenu()"
                       class="block px-4 py-3 text-base font-medium text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                        <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
                        </svg>
                        Profile
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" 
                                @click="closeMobileMenu()"
                                class="block w-full text-left px-4 py-3 text-base font-medium text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5 inline mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </header>

        <!-- Mobile Menu Overlay (when menu is open) -->
        <div x-show="mobileMenuOpen" 
             x-transition:enter="transition-opacity ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="closeMobileMenu()"
             class="fixed inset-0 bg-black/20 dark:bg-black/40 backdrop-blur-sm z-40 md:hidden"
             style="top: 73px;">
        </div>

        <!-- Page Content -->
        <main class="pt-20 md:pt-24 pb-8">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 text-success-700 dark:text-success-300 px-4 py-3 rounded-xl animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-error-50 dark:bg-error-900/20 border border-error-200 dark:border-error-800 text-error-700 dark:text-error-300 px-4 py-3 rounded-xl animate-fade-in">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>