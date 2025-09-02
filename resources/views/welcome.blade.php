<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'AI Document Q&A') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-cyan-50 to-blue-100">
        <!-- Header -->
        <header class="absolute inset-x-0 top-0 z-50">
            <nav class="flex items-center justify-between p-4 lg:px-8">
                <div class="flex lg:flex-1">
                    <span class="text-xl font-bold text-gray-900">AI Document Q&A</span>
                </div>
                <div class="flex lg:flex-1 lg:justify-end space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-cyan-600 transition-colors">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-cyan-600 transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="rounded-md bg-cyan-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-all">
                            Sign up
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <div class="relative isolate px-4 pt-14 lg:px-8">
            <div class="mx-auto max-w-4xl py-20 sm:py-32 lg:py-40">
                <div class="text-center mb-16">
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-5xl lg:text-6xl">
                        Transform Documents into 
                        <span class="text-cyan-600">Interactive Learning</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600 max-w-3xl mx-auto">
                        Welcome to the easiest way to turn your study materials into powerful learning tools. Upload any document and instantly generate quiz questions or flashcards powered by AI.
                    </p>
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4 text-sm text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            5 Free Credits for New Users
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Instant AI Processing
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            Multiple File Formats
                        </div>
                    </div>
                </div>

                <!-- Upload Section -->
                <div class="bg-white rounded-2xl shadow-xl border border-gray-200 p-6 lg:p-8 mb-16">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Get Started Instantly</h2>
                        <p class="text-gray-600">Drop your document below and we'll transform it into interactive learning materials</p>
                    </div>
                    
                    <div id="dropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 lg:p-12 text-center hover:border-cyan-400 hover:bg-cyan-50 transition-all cursor-pointer">
                        <input type="file" id="fileInput" accept=".pdf,.docx,.doc,.txt,.pptx" class="hidden">
                        
                        <div class="space-y-4">
                            <div class="mx-auto w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-cyan-600" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Upload Your Document</h3>
                                <p class="text-gray-600">
                                    <span class="font-medium text-cyan-600">Click to browse</span> or drag and drop your file here
                                </p>
                                <p class="text-sm text-gray-500 mt-2">PDF, DOCX, PPTX, or TXT up to 10MB</p>
                            </div>
                            <div id="selectedFile" class="hidden">
                                <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-lg">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                    <span id="fileName" class="text-sm font-medium text-green-800"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="py-16 sm:py-24 bg-white">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-4">
                        How It Works
                    </h2>
                    <p class="text-lg leading-8 text-gray-600">
                        Simple three-step process to transform your documents into powerful study tools
                    </p>
                </div>
                
                <div class="grid max-w-2xl mx-auto gap-8 lg:max-w-none lg:grid-cols-3 lg:gap-12">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-cyan-100 mb-6">
                            <div class="text-2xl font-bold text-cyan-600">1</div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Upload Document</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Upload your PDF, Word document, PowerPoint presentation, or text file. Our system supports files up to 10MB and extracts all readable content automatically.
                        </p>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-purple-100 mb-6">
                            <div class="text-2xl font-bold text-purple-600">2</div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Choose Format</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Select between two powerful study formats: interactive multiple-choice questions for testing knowledge, or flashcards for memorization and quick review.
                        </p>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-green-100 mb-6">
                            <div class="text-2xl font-bold text-green-600">3</div>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Study & Learn</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Take interactive quizzes with instant feedback, study with flip-able flashcards, or download your materials for offline use. Track your progress as you learn.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-16 sm:py-24 bg-gray-50">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-16">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-4">
                        Two Powerful Study Modes
                    </h2>
                    <p class="text-lg leading-8 text-gray-600">
                        Choose the learning format that works best for your study goals
                    </p>
                </div>
                
                <div class="grid max-w-2xl mx-auto gap-8 lg:max-w-none lg:grid-cols-2 lg:gap-12">
                    <!-- Q&A Mode -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 lg:p-8">
                        <div class="flex items-center mb-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-cyan-100">
                                <svg class="h-6 w-6 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                                </svg>
                            </div>
                            <h3 class="ml-4 text-xl font-semibold text-gray-900">Multiple Choice Questions</h3>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Perfect for testing comprehension and retention. Our AI generates thoughtful questions with four answer choices, correct answers, and detailed explanations to help you understand the material deeply.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Interactive quiz interface with instant scoring
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Detailed explanations for every answer
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Generate 10, 20, or 30 questions per document
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Flashcard Mode -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 lg:p-8">
                        <div class="flex items-center mb-6">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-purple-100">
                                <svg class="h-6 w-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                    <path fill-rule="evenodd" d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="ml-4 text-xl font-semibold text-gray-900">Interactive Flashcards</h3>
                        </div>
                        <p class="text-gray-600 mb-6 leading-relaxed">
                            Great for memorization and quick review sessions. Each flashcard contains a key term or concept on the front and its definition or explanation on the back, perfect for active recall practice.
                        </p>
                        <ul class="space-y-3">
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Click-to-flip interactive card interface
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Keyboard shortcuts for faster navigation
                            </li>
                            <li class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 text-green-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
        <div class="py-16 sm:py-24 bg-white">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-12">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl mb-4">
                        Works with All Your Documents
                    </h2>
                    <p class="text-lg leading-8 text-gray-600">
                        Upload documents in various formats and let our AI extract the content seamlessly
                    </p>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 lg:gap-8">
                    <!-- PDF -->
                    <div class="group text-center p-6 bg-gradient-to-br from-red-50 to-red-100 rounded-xl border border-red-200 hover:shadow-lg transition-all">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-lg mb-1">PDF</h3>
                        <p class="text-sm text-gray-600">Portable documents with full text extraction</p>
                    </div>
                    
                    <!-- DOCX -->
                    <div class="group text-center p-6 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 hover:shadow-lg transition-all">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-lg mb-1">DOCX</h3>
                        <p class="text-sm text-gray-600">Microsoft Word documents and reports</p>
                    </div>
                    
                    <!-- PPTX -->
                    <div class="group text-center p-6 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200 hover:shadow-lg transition-all">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-lg mb-1">PPTX</h3>
                        <p class="text-sm text-gray-600">PowerPoint presentations and slides</p>
                    </div>
                    
                    <!-- TXT -->
                    <div class="group text-center p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200 hover:shadow-lg transition-all">
                        <div class="w-16 h-16 bg-white rounded-xl shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 text-lg mb-1">TXT</h3>
                        <p class="text-sm text-gray-600">Plain text files and notes</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-cyan-600 py-16 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl mb-4">
                        Ready to Transform Your Learning?
                    </h2>
                    <p class="mx-auto text-lg leading-8 text-cyan-100 mb-10">
                        Join thousands of students and professionals who are already using AI to enhance their learning experience. Get started with 5 free credits today!
                    </p>
                    @auth
                        <a href="{{ route('documents.upload') }}" 
                           class="rounded-md bg-white px-6 py-3 text-base font-semibold text-cyan-600 shadow-sm hover:bg-cyan-50 focus:outline-none focus:ring-2 focus:ring-white transition-all">
                            Start Creating Now
                        </a>
                    @else
                        <a href="{{ route('register') }}" 
                           class="rounded-md bg-white px-6 py-3 text-base font-semibold text-cyan-600 shadow-sm hover:bg-cyan-50 focus:outline-none focus:ring-2 focus:ring-white transition-all">
                            Get Started Free
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Login/Register Modal Overlay -->
    <div id="authModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 p-4">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6">
                <div class="text-center mb-6">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-cyan-100 mb-4">
                        <svg class="h-6 w-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Login Required</h3>
                    <p class="text-gray-600">Please sign in or create an account to upload documents and start generating your study materials.</p>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('register') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-medium rounded-lg text-white bg-cyan-600 hover:bg-cyan-700 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"/>
                        </svg>
                        Sign Up (Get 5 Free Credits)
                    </a>
                    
                    <a href="{{ route('login') }}" 
                       class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-cyan-500 transition-colors">
<path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z"/>
                        </svg>
                        Sign In
                    </a>
                    
                    <button onclick="closeAuthModal()" 
                            class="w-full text-center text-sm text-gray-500 hover:text-gray-700 py-2 transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check authentication status
        var isUserAuthenticated = false;
        @auth
            isUserAuthenticated = true;
        @endauth

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
                dropZone.classList.add('border-cyan-400', 'bg-cyan-50');
            });

            dropZone.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-cyan-400', 'bg-cyan-50');
            });

            dropZone.addEventListener('drop', function(e) {
                e.preventDefault();
                dropZone.classList.remove('border-cyan-400', 'bg-cyan-50');
                
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