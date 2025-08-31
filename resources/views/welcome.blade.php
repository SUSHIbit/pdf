@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cyan-50 to-blue-100">
    <div class="relative">
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
                        Upload any PDF, DOCX, or TXT file and let AI generate comprehensive questions and answers. Perfect for studying, training, and knowledge extraction.
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
                        Simple three-step process to transform your documents into valuable Q&A content.
                    </p>
                </div>
                <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                    <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
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
                                Upload your PDF, DOCX, or TXT file. We support files up to 10MB.
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
                                Our AI analyzes your content and generates intelligent questions and detailed answers.
                            </dd>
                        </div>
                        <div class="flex flex-col items-center text-center">
                            <div class="mb-6 flex h-16 w-16 items-center justify-center rounded-lg bg-cyan-600">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                </svg>
                            </div>
                            <dt class="text-base font-semibold leading-7 text-gray-900">
                                Download Results
                            </dt>
                            <dd class="mt-1 text-base leading-7 text-gray-600">
                                Review and download your generated Q&A pairs in an easy-to-use format.
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection