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
            <nav class="flex items-center justify-between p-6 lg:px-8">
                <div class="flex lg:flex-1">
                    <span class="text-xl font-bold text-gray-900">AI Document Q&A</span>
                </div>
                <div class="flex lg:flex-1 lg:justify-end space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-cyan-600">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold leading-6 text-gray-900 hover:text-cyan-600">
                            Log in
                        </a>
                        <a href="{{ route('register') }}" class="rounded-md bg-cyan-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-cyan-700">
                            Sign up
                        </a>
                    @endauth
                </div>
            </nav>
        </header>

        <!-- Hero Section -->
        <div class="relative isolate px-6 pt-14 lg:px-8">
            <div class="mx-auto max-w-2xl py-32 sm:py-48 lg:py-56">
                <div class="text-center">
                    <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                        Transform Documents into 
                        <span class="text-cyan-600">Interactive Q&A</span>
                    </h1>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Upload PDF, Word, PowerPoint, or text files and let AI generate comprehensive questions and answers. Perfect for studying, training, and knowledge extraction.
                    </p>
                    <div class="mt-10 flex items-center justify-center gap-x-6">
                        @auth
                            <a href="{{ route('documents.upload') }}" 
                               class="rounded-md bg-cyan-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-cyan-700 transition-colors">
                                Upload Document
                            </a>
                            <a href="{{ route('dashboard') }}" 
                               class="text-sm font-semibold leading-6 text-gray-900 hover:text-cyan-600">
                                View Dashboard <span aria-hidden="true">→</span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" 
                               class="rounded-md bg-cyan-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-cyan-700 transition-colors">
                                Get Started
                            </a>
                            <a href="{{ route('login') }}" 
                               class="text-sm font-semibold leading-6 text-gray-900 hover:text-cyan-600">
                                Learn more <span aria-hidden="true">→</span>
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="py-24 sm:py-32 bg-white">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                    <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">
                        How It Works
                    </h2>
                    <p class="mt-6 text-lg leading-8 text-gray-600">
                        Simple four-step process to transform your documents into valuable Q&A content.
                    </p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-4">
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-cyan-600">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"/>
                                </svg>
                            </div>
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                Upload Document
                            </dt>
                            <dd class="mt-1 text-base leading-7 text-gray-600">
                                Upload your PDF, Word, PowerPoint, or TXT file. We support files up to 10MB.
                            </dd>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-cyan-600">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                            </div>
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                Text Extraction
                            </dt>
                            <dd class="mt-1 text-base leading-7 text-gray-600">
                                Our system extracts all text content from your document for you to review.
                            </dd>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-cyan-600">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                                </svg>
                            </div>
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                AI Processing
                            </dt>
                            <dd class="mt-1 text-base leading-7 text-gray-600">
                                Our AI analyzes your content and generates intelligent multiple-choice questions.
                            </dd>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-cyan-600">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                                </svg>
                            </div>
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                Interactive Quiz
                            </dt>
                            <dd class="mt-1 text-base leading-7 text-gray-600">
                                Take the interactive quiz and download your Q&A pairs for future use.
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Supported File Types Section -->
        <div class="py-16 bg-gray-50">
            <div class="mx-auto max-w-7xl px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center mb-12">
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                        Supported File Types
                    </h2>
                    <p class="mt-4 text-gray-600">
                        Upload documents in various formats and let AI do the rest
                    </p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="text-center p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">PDF</h3>
                        <p class="text-sm text-gray-600">Portable Document Format</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">DOCX</h3>
                        <p class="text-sm text-gray-600">Microsoft Word Documents</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">PPTX</h3>
                        <p class="text-sm text-gray-600">PowerPoint Presentations</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900">TXT</h3>
                        <p class="text-sm text-gray-600">Plain Text Files</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>