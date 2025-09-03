@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center py-16">
        <!-- Success Icon -->
        <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100 mb-8">
            <svg class="h-10 w-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
        </div>

        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Payment Successful!</h1>
        <p class="text-lg text-gray-600 mb-8">
            Your credits have been added to your account and are ready to use.
        </p>

        <!-- Credit Info -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                </svg>
                <div class="text-2xl font-bold text-green-800">{{ auth()->user()->credits }} Credits Available</div>
            </div>
            <p class="text-sm text-green-700">
                You can now upload documents and generate Q&A pairs. Each document costs 1 credit to process.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="{{ route('documents.upload') }}" 
               class="inline-flex items-center px-6 py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-md transition-colors">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"/>
                </svg>
                Upload Your First Document
            </a>
            
            <div class="text-center">
                <a href="{{ route('dashboard') }}" 
                   class="text-gray-600 hover:text-gray-800 font-medium">
                    Return to Dashboard
                </a>
            </div>
        </div>

        <!-- Receipt Info -->
        <div class="mt-12 pt-8 border-t border-gray-200">
            <p class="text-sm text-gray-500 mb-2">
                A receipt has been sent to your email address.
            </p>
            <p class="text-xs text-gray-400">
                Need help? Contact support at support@example.com
            </p>
        </div>
    </div>
</div>
@endsection