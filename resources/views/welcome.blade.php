<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="themeManager()" x-bind:class="{ 'dark': isDark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Document Q&A') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
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
<body class="font-sans antialiased bg-background dark:bg-background-dark transition-colors duration-300" data-authenticated="{{ auth()->check() ? '1' : '0' }}">
    <div class="min-h-screen bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 transition-colors duration-300">
        <!-- Fixed Header -->
        <header class="fixed inset-x-0 top-0 z-50 bg-surface/95 dark:bg-surface-dark/95 backdrop-blur-sm border-b border-border dark:border-border-dark shadow-sm transition-colors duration-300">
            <nav class="flex items-center justify-between p-4 lg:px-8 max-w-7xl mx-auto">
                <!-- Logo -->
                <div class="flex items-center">
                    <span class="text-xl md:text-2xl font-bold text-text-primary dark:text-text-dark-primary transition-colors">AI Document Q&A</span>
                </div>
                
                <!-- Desktop Navigation (hidden on mobile) -->
                <div class="hidden md:flex items-center space-x-4">
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
                    
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-base font-semibold text-text-primary dark:text-text-dark-primary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors px-3 py-2">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-base font-semibold text-text-primary dark:text-text-dark-primary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors px-3 py-2">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="rounded-md bg-primary-600 dark:bg-primary-700 px-4 py-2 text-base font-semibold text-white shadow-sm hover:bg-primary-700 dark:hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-all">
                            Sign up
                        </a>
                    @endauth
                </div>

                <!-- Mobile Navigation (visible on mobile) -->
                <div class="flex md:hidden items-center space-x-2">
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
                    @auth
                        <a href="{{ route('dashboard') }}" 
                           @click="closeMobileMenu()"
                           class="block px-4 py-3 text-base font-semibold text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           @click="closeMobileMenu()"
                           class="block px-4 py-3 text-base font-semibold text-text-primary dark:text-text-dark-primary hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" 
                           @click="closeMobileMenu()"
                           class="block px-4 py-3 text-base font-semibold text-white bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 rounded-lg transition-colors text-center">
                            Sign up
                        </a>
                    @endauth
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

        <!-- Hero Section -->
        <div class="relative isolate px-4 pt-20 md:pt-24 lg:px-8">
            <div class="mx-auto max-w-4xl py-16 sm:py-24 lg:py-32">
                <div class="text-center mb-12 md:mb-16">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold tracking-tight text-text-primary dark:text-text-dark-primary transition-colors">
                        Transform Documents into 
                        <span class="text-primary-600 dark:text-primary-400">Interactive Learning</span>
                    </h1>
                    <p class="mt-6 text-base sm:text-lg leading-7 sm:leading-8 text-text-secondary dark:text-text-dark-secondary max-w-3xl mx-auto transition-colors px-4">
                        Welcome to the easiest way to turn your study materials into powerful learning tools. Upload any document and instantly generate quiz questions or flashcards powered by AI.
                    </p>
                    <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4 text-sm text-text-tertiary dark:text-text-dark-tertiary transition-colors px-4">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            5 Free Credits for New Users
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Instant AI Processing
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Multiple File Formats
                        </div>
                    </div>
                </div>

                <!-- Upload Section -->
                <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 lg:p-8 mb-12 md:mb-16 transition-all duration-300 mx-2 sm:mx-0 animate-fade-in">
                    <div class="text-center mb-6">
                        <h2 class="text-xl sm:text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">Get Started Instantly</h2>
                        <p class="text-text-secondary dark:text-text-dark-secondary transition-colors text-sm sm:text-base">Drop your document below and we'll transform it into interactive learning materials</p>
                    </div>
                    
                    <div id="dropZone" class="border-2 border-dashed border-border dark:border-border-dark rounded-xl p-6 sm:p-8 lg:p-12 text-center hover:border-primary-400 dark:hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all cursor-pointer touch-manipulation group">
                        <input type="file" id="fileInput" accept=".pdf,.docx,.doc,.txt,.pptx" class="hidden">
                        
                        <div class="space-y-4">
                            <div class="mx-auto w-12 h-12 sm:w-16 sm:h-16 bg-primary-100 dark:bg-primary-800 rounded-full flex items-center justify-center transition-colors group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">Upload Your Document</h3>
                                <p class="text-text-secondary dark:text-text-dark-secondary text-sm sm:text-base transition-colors">
                                    <span class="font-medium text-primary-600 dark:text-primary-400">Click to browse</span> or drag and drop your file here
                                </p>
                                <p class="text-xs sm:text-sm text-text-tertiary dark:text-text-dark-tertiary mt-2">PDF, DOCX, PPTX, or TXT up to 10MB</p>
                            </div>
                            <div id="selectedFile" class="hidden">
                                <div class="inline-flex items-center px-4 py-2 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-lg animate-scale-in">
                                    <svg class="w-4 h-4 text-success-600 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                    <span id="fileName" class="text-sm font-medium text-success-800 dark:text-success-300"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rest of the sections remain the same as in the previous version -->
        <!-- How It Works Section -->
        <div class="py-12 sm:py-16 md:py-24 bg-surface dark:bg-surface-dark transition-colors duration-300">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-12 md:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                        How It Works
                    </h2>
                    <p class="text-base sm:text-lg leading-7 sm:leading-8 text-text-secondary dark:text-text-dark-secondary transition-colors">
                        Simple three-step process to transform your documents into powerful study tools
                    </p>
                </div>
                
                <div class="grid max-w-2xl mx-auto gap-8 lg:max-w-none lg:grid-cols-3 lg:gap-12">
                    <!-- Step 1 -->
                    <div class="text-center animate-slide-up">
                        <div class="mx-auto flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-full bg-primary-100 dark:bg-primary-800 mb-6 transition-colors">
                            <div class="text-xl sm:text-2xl font-bold text-primary-600 dark:text-primary-300 transition-colors">1</div>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">Upload Document</h3>
                        <p class="text-text-secondary dark:text-text-dark-secondary leading-relaxed text-sm sm:text-base transition-colors">
                            Upload your PDF, Word document, PowerPoint presentation, or text file. Our system supports files up to 10MB and extracts all readable content automatically.
                        </p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="text-center animate-slide-up" style="animation-delay: 0.1s;">
                        <div class="mx-auto flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900/30 mb-6 transition-colors">
                            <div class="text-xl sm:text-2xl font-bold text-purple-600 dark:text-purple-400 transition-colors">2</div>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">Choose Format</h3>
                        <p class="text-text-secondary dark:text-text-dark-secondary leading-relaxed text-sm sm:text-base transition-colors">
                            Select between two powerful study formats: interactive multiple-choice questions for testing knowledge, or flashcards for memorization and quick review.
                        </p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="text-center animate-slide-up" style="animation-delay: 0.2s;">
                        <div class="mx-auto flex h-16 w-16 sm:h-20 sm:w-20 items-center justify-center rounded-full bg-success-100 dark:bg-success-900/30 mb-6 transition-colors">
                            <div class="text-xl sm:text-2xl font-bold text-success-600 dark:text-success-400 transition-colors">3</div>
                        </div>
                        <h3 class="text-lg sm:text-xl font-semibold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">Study & Learn</h3>
                        <p class="text-text-secondary dark:text-text-dark-secondary leading-relaxed text-sm sm:text-base transition-colors">
                            Take interactive quizzes with instant feedback, study with flip-able flashcards, or download your materials for offline use. Track your progress as you learn.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-12 sm:py-16 md:py-24 bg-background-secondary dark:bg-background-dark-secondary transition-colors duration-300">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-12 md:mb-16">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                        Two Powerful Study Modes
                    </h2>
                    <p class="text-base sm:text-lg leading-7 sm:leading-8 text-text-secondary dark:text-text-dark-secondary transition-colors">
                        Choose the learning format that works best for your study goals
                    </p>
                </div>
                
                <div class="grid max-w-2xl mx-auto gap-6 sm:gap-8 lg:max-w-none lg:grid-cols-2 lg:gap-12">
                    <!-- Q&A Mode -->
                    <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-lg border border-border dark:border-border-dark p-6 lg:p-8 transition-all duration-300 hover:shadow-xl hover:scale-105 transform">
                        <div class="flex items-center mb-6">
                            <div class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-xl bg-primary-100 dark:bg-primary-800 transition-colors">
                                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-primary-600 dark:text-primary-300 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                                </svg>
                            </div>
                            <h3 class="ml-4 text-lg sm:text-xl font-semibold text-text-primary dark:text-text-dark-primary transition-colors">Multiple Choice Questions</h3>
                        </div>
                        <p class="text-text-secondary dark:text-text-dark-secondary mb-6 leading-relaxed text-sm sm:text-base transition-colors">
                            Perfect for testing comprehension and retention. Our AI generates thoughtful questions with four answer choices, correct answers, and detailed explanations to help you understand the material deeply.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Interactive quiz interface with instant scoring
                            </li>
                            <li class="flex items-center text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
<svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                               </svg>
                               Detailed explanations for every answer
                           </li>
                           <li class="flex items-center text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                               <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                               </svg>
                               Generate 10, 20, or 30 questions per document
                           </li>
                       </ul>
                   </div>
                   
                   <!-- Flashcard Mode -->
                   <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-lg border border-border dark:border-border-dark p-6 lg:p-8 transition-all duration-300 hover:shadow-xl hover:scale-105 transform">
                       <div class="flex items-center mb-6">
                           <div class="flex h-10 w-10 sm:h-12 sm:w-12 items-center justify-center rounded-xl bg-purple-100 dark:bg-purple-900/30 transition-colors">
                               <svg class="h-5 w-5 sm:h-6 sm:w-6 text-purple-600 dark:text-purple-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                                   <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                   <path fill-rule="evenodd" d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8z" clip-rule="evenodd"/>
                               </svg>
                           </div>
                           <h3 class="ml-4 text-lg sm:text-xl font-semibold text-text-primary dark:text-text-dark-primary transition-colors">Interactive Flashcards</h3>
                       </div>
                       <p class="text-text-secondary dark:text-text-dark-secondary mb-6 leading-relaxed text-sm sm:text-base transition-colors">
                           Great for memorization and quick review sessions. Each flashcard contains a key term or concept on the front and its definition or explanation on the back, perfect for active recall practice.
                       </p>
                       <ul class="space-y-3">
                           <li class="flex items-center text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                               <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                               </svg>
                               Click-to-flip interactive card interface
                           </li>
                           <li class="flex items-center text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                               <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                               </svg>
                               Keyboard shortcuts for faster navigation
                           </li>
                           <li class="flex items-center text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                               <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                   <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                               </svg>
                               Shuffle and reset deck functionality
                           </li>
                       </ul>
                   </div>
               </div>
           </div>
       </div>

       <!-- Supported File Types Section -->
       <div class="py-12 sm:py-16 md:py-24 bg-surface dark:bg-surface-dark transition-colors duration-300">
           <div class="mx-auto max-w-7xl px-4 lg:px-8">
               <div class="mx-auto max-w-2xl text-center mb-12">
                   <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                       Works with All Your Documents
                   </h2>
                   <p class="text-base sm:text-lg leading-7 sm:leading-8 text-text-secondary dark:text-text-dark-secondary transition-colors">
                       Upload documents in various formats and let our AI extract the content seamlessly
                   </p>
               </div>
               <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
                   <!-- PDF -->
                   <div class="group text-center p-4 sm:p-6 bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl border border-red-200 dark:border-red-800 hover:shadow-lg transition-all hover:scale-105 transform duration-200">
                       <div class="w-12 h-12 sm:w-16 sm:h-16 bg-surface dark:bg-surface-dark rounded-xl shadow-sm flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                           <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-600 dark:text-red-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                               <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                           </svg>
                       </div>
                       <h3 class="font-semibold text-text-primary dark:text-text-dark-primary text-sm sm:text-lg mb-1 transition-colors">PDF</h3>
                       <p class="text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">Portable documents with full text extraction</p>
                   </div>
                   
                   <!-- DOCX -->
                   <div class="group text-center p-4 sm:p-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl border border-blue-200 dark:border-blue-800 hover:shadow-lg transition-all hover:scale-105 transform duration-200">
                       <div class="w-12 h-12 sm:w-16 sm:h-16 bg-surface dark:bg-surface-dark rounded-xl shadow-sm flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                           <svg class="w-6 h-6 sm:w-8 sm:h-8 text-blue-600 dark:text-blue-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                               <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                           </svg>
                       </div>
                       <h3 class="font-semibold text-text-primary dark:text-text-dark-primary text-sm sm:text-lg mb-1 transition-colors">DOCX</h3>
                       <p class="text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">Microsoft Word documents and reports</p>
                   </div>
                   
                   <!-- PPTX -->
                   <div class="group text-center p-4 sm:p-6 bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl border border-orange-200 dark:border-orange-800 hover:shadow-lg transition-all hover:scale-105 transform duration-200">
                       <div class="w-12 h-12 sm:w-16 sm:h-16 bg-surface dark:bg-surface-dark rounded-xl shadow-sm flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                           <svg class="w-6 h-6 sm:w-8 sm:h-8 text-orange-600 dark:text-orange-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                               <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                           </svg>
                       </div>
                       <h3 class="font-semibold text-text-primary dark:text-text-dark-primary text-sm sm:text-lg mb-1 transition-colors">PPTX</h3>
                       <p class="text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">PowerPoint presentations and slides</p>
                   </div>
                   
                   <!-- TXT -->
                   <div class="group text-center p-4 sm:p-6 bg-gradient-to-br from-primary-50 to-primary-100 dark:from-primary-800 dark:to-primary-700 rounded-xl border border-primary-200 dark:border-primary-600 hover:shadow-lg transition-all hover:scale-105 transform duration-200">
                       <div class="w-12 h-12 sm:w-16 sm:h-16 bg-surface dark:bg-surface-dark rounded-xl shadow-sm flex items-center justify-center mx-auto mb-3 sm:mb-4 group-hover:scale-110 transition-transform">
                           <svg class="w-6 h-6 sm:w-8 sm:h-8 text-primary-600 dark:text-primary-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                               <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                           </svg>
                       </div>
                       <h3 class="font-semibold text-text-primary dark:text-text-dark-primary text-sm sm:text-lg mb-1 transition-colors">TXT</h3>
                       <p class="text-xs sm:text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">Plain text files and notes</p>
                   </div>
               </div>
           </div>
       </div>

       <!-- CTA Section -->
       <div class="bg-primary-600 dark:bg-primary-700 py-12 sm:py-16 md:py-24 transition-colors duration-300">
           <div class="mx-auto max-w-7xl px-4 lg:px-8">
               <div class="mx-auto max-w-2xl text-center">
                   <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold tracking-tight text-white mb-4">
                       Ready to Transform Your Learning?
                   </h2>
                   <p class="mx-auto text-base sm:text-lg leading-7 sm:leading-8 text-primary-200 dark:text-primary-300 mb-8 sm:mb-10 transition-colors">
                       Join thousands of students and professionals who are already using AI to enhance their learning experience. Get started with 5 free credits today!
                   </p>
                   @auth
                       <a href="{{ route('documents.upload') }}" 
                          class="inline-block rounded-md bg-white px-6 py-3 text-sm sm:text-base font-semibold text-primary-600 shadow-sm hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-white transition-all transform hover:scale-105">
                           Start Creating Now
                       </a>
                   @else
                       <a href="{{ route('register') }}" 
                          class="inline-block rounded-md bg-white px-6 py-3 text-sm sm:text-base font-semibold text-primary-600 shadow-sm hover:bg-primary-50 focus:outline-none focus:ring-2 focus:ring-white transition-all transform hover:scale-105">
                           Get Started Free
                       </a>
                   @endauth
               </div>
           </div>
       </div>
   </div>

   <!-- Login/Register Modal Overlay -->
   <div id="authModal" class="fixed inset-0 bg-black/50 dark:bg-black/70 hidden z-50 p-4 transition-colors">
       <div class="flex items-center justify-center min-h-screen">
           <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl max-w-md w-full p-6 border border-border dark:border-border-dark transition-colors animate-scale-in">
               <div class="text-center mb-6">
                   <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-primary-100 dark:bg-primary-800 mb-4 transition-colors">
                       <svg class="h-6 w-6 text-primary-600 dark:text-primary-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                       </svg>
                   </div>
                   <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">Login Required</h3>
                   <p class="text-text-secondary dark:text-text-dark-secondary text-sm sm:text-base transition-colors">Please sign in or create an account to upload documents and start generating your study materials.</p>
               </div>
               
               <div class="space-y-3">
                   <!-- Google OAuth Button -->
                   <a href="{{ route('auth.google') }}" 
                      class="w-full inline-flex justify-center items-center px-4 py-3 border border-border dark:border-border-dark text-sm font-medium rounded-lg text-text-secondary dark:text-text-dark-secondary bg-surface dark:bg-surface-dark hover:bg-primary-50 dark:hover:bg-primary-900/20 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-colors">
                       <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                           <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                           <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                           <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                           <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                       </svg>
                       Continue with Google
                   </a>

                   <!-- Divider -->
                   <div class="relative">
                       <div class="absolute inset-0 flex items-center">
                           <div class="w-full border-t border-border dark:border-border-dark"></div>
                       </div>
                       <div class="relative flex justify-center text-xs">
                           <span class="px-2 bg-surface dark:bg-surface-dark text-text-tertiary dark:text-text-dark-tertiary">or</span>
                       </div>
                   </div>
                   
                   <a href="{{ route('register') }}" 
                      class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-colors">
                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                           <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                       </svg>
                       Sign Up (Get 5 Free Credits)
                   </a>
                   
                   <a href="{{ route('login') }}" 
                      class="w-full inline-flex justify-center items-center px-4 py-3 border border-border dark:border-border-dark text-sm font-medium rounded-lg text-text-secondary dark:text-text-dark-secondary bg-surface dark:bg-surface-dark hover:bg-primary-50 dark:hover:bg-primary-900/20 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 transition-colors">
                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"/>
                       </svg>
                       Sign In
                   </a>
                   
                   <button onclick="closeAuthModal()" 
                           class="w-full text-center text-sm text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary py-2 transition-colors">
                       Cancel
                   </button>
               </div>
           </div>
       </div>
   </div>

   <script>
   document.addEventListener('DOMContentLoaded', function() {
       // Check authentication status
       const isUserAuthenticated = document.body.getAttribute('data-authenticated') === '1';

       const dropZone = document.getElementById('dropZone');
       const fileInput = document.getElementById('fileInput');
       const selectedFile = document.getElementById('selectedFile');
       const fileName = document.getElementById('fileName');
       const authModal = document.getElementById('authModal');

       // File selection handling
       function handleFileSelect(file) {
           if (!file) return;
           
           const allowedTypes = [
               'application/pdf', 
               'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
               'application/msword', 
               'text/plain', 
               'application/vnd.openxmlformats-officedocument.presentationml.presentation'
           ];
           
           if (!allowedTypes.includes(file.type)) {
               alert('Please select a valid file type: PDF, DOCX, DOC, TXT, or PPTX');
               return;
           }
           
           if (file.size > 10 * 1024 * 1024) {
               alert('File size must be less than 10MB');
               return;
           }

           // Show selected file
           fileName.textContent = file.name;
           selectedFile.classList.remove('hidden');
           
           // Check if user is authenticated
           if (isUserAuthenticated) {
               // User is logged in, proceed to upload
               proceedToUpload(file);
           } else {
               // User is not logged in, store file and show auth modal
               storeFileForUpload(file);
               showAuthModal();
           }
       }

       function proceedToUpload(file) {
           // Create form and submit to upload endpoint
           const formData = new FormData();
           formData.append('document', file);
           formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
           
           fetch('{{ route("documents.store") }}', {
               method: 'POST',
               body: formData
           })
           .then(function(response) {
               return response.json();
           })
           .then(function(data) {
               if (data.success) {
                   window.location.href = data.redirect;
               } else {
                   alert('Error uploading file: ' + (data.message || 'Unknown error'));
               }
           })
           .catch(function(error) {
               console.error('Error:', error);
               alert('An error occurred while uploading the file.');
           });
       }

       function storeFileForUpload(file) {
           // Store file in session storage for retrieval after login
           const reader = new FileReader();
           reader.onload = function(e) {
               try {
                   sessionStorage.setItem('pendingUploadFile', JSON.stringify({
                       name: file.name,
                       type: file.type,
                       size: file.size,
                       data: e.target.result
                   }));
                   sessionStorage.setItem('pendingUpload', 'true');
                   
                   // CRITICAL FIX: Set server-side session flag via AJAX
                   fetch('/set-pending-upload', {
                       method: 'POST',
                       headers: {
                           'Content-Type': 'application/json',
                           'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                       },
                       body: JSON.stringify({ pending: true })
                   }).catch(function(error) {
                       console.error('Error setting server session:', error);
                   });
               } catch (error) {
                   console.error('Error storing file data:', error);
               }
           };
           reader.readAsDataURL(file);
       }

       function showAuthModal() {
           if (authModal) {
               authModal.classList.remove('hidden');
               document.body.style.overflow = 'hidden';
           }
       }

       function closeAuthModal() {
           if (authModal) {
               authModal.classList.add('hidden');
               document.body.style.overflow = '';
           }
       }

       // Make closeAuthModal available globally
       window.closeAuthModal = closeAuthModal;

       // Click handler
       if (dropZone) {
           dropZone.addEventListener('click', function() {
               fileInput.click();
           });
       }

       // File input change
       if (fileInput) {
           fileInput.addEventListener('change', function(e) {
               const file = e.target.files[0];
               if (file) {
                   handleFileSelect(file);
               }
           });
       }

       // Drag and drop handlers
       if (dropZone) {
           dropZone.addEventListener('dragover', function(e) {
               e.preventDefault();
               dropZone.classList.add('border-primary-400', 'dark:border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
           });

           dropZone.addEventListener('dragleave', function(e) {
               e.preventDefault();
               dropZone.classList.remove('border-primary-400', 'dark:border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
           });

           dropZone.addEventListener('drop', function(e) {
               e.preventDefault();
               dropZone.classList.remove('border-primary-400', 'dark:border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
               
               const files = e.dataTransfer.files;
               if (files.length > 0) {
                   handleFileSelect(files[0]);
               }
           });
       }

       // Close modal on escape key
       document.addEventListener('keydown', function(e) {
           if (e.key === 'Escape' && authModal && !authModal.classList.contains('hidden')) {
               closeAuthModal();
           }
       });

       // Close modal on background click
       if (authModal) {
           authModal.addEventListener('click', function(e) {
               if (e.target === authModal) {
                   closeAuthModal();
               }
           });
       }
   });
   </script>
</body>
</html>