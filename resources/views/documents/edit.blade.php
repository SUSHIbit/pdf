@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
            ← Back to Dashboard
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">Edit Document Title</h1>
        <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">Change the title for "{{ $document->original_name }}"</p>
    </div>

    <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 sm:p-8 transition-all duration-300">
        <form action="{{ route('documents.update', $document) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                    Document Title
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $document->title ?: $document->original_name) }}"
                       class="w-full px-4 py-3 bg-background dark:bg-background-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                       required>
                @error('title')
                    <p class="mt-2 text-sm text-error-600 dark:text-error-400 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
                <p class="mt-2 text-sm text-text-tertiary dark:text-text-dark-tertiary transition-colors">This title will be used in the dashboard and downloads.</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="button" 
                        onclick="history.back()"
                        class="px-6 py-3 text-text-secondary dark:text-text-dark-secondary bg-background-secondary dark:bg-background-dark-secondary border border-border dark:border-border-dark rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 font-medium">
                    Cancel
                </button>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105">
                    Update Title
                </button>
            </div>
        </form>
    </div>

    <!-- Document Info -->
    <div class="mt-6 bg-background-secondary dark:bg-background-dark-secondary rounded-2xl p-6 border border-border dark:border-border-dark transition-all duration-300">
        <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">Document Information</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm transition-colors">
            <div class="bg-surface dark:bg-surface-dark p-4 rounded-xl border border-border dark:border-border-dark">
                <span class="font-medium text-text-secondary dark:text-text-dark-secondary">Original filename:</span>
                <p class="text-text-primary dark:text-text-dark-primary mt-1">{{ $document->original_name }}</p>
            </div>
            <div class="bg-surface dark:bg-surface-dark p-4 rounded-xl border border-border dark:border-border-dark">
                <span class="font-medium text-text-secondary dark:text-text-dark-secondary">File type:</span>
                <p class="text-text-primary dark:text-text-dark-primary mt-1">{{ strtoupper($document->file_type) }}</p>
            </div>
            <div class="bg-surface dark:bg-surface-dark p-4 rounded-xl border border-border dark:border-border-dark">
                <span class="font-medium text-text-secondary dark:text-text-dark-secondary">File size:</span>
                <p class="text-text-primary dark:text-text-dark-primary mt-1">{{ $document->getFileSizeFormatted() }}</p>
            </div>
            <div class="bg-surface dark:bg-surface-dark p-4 rounded-xl border border-border dark:border-border-dark">
                <span class="font-medium text-text-secondary dark:text-text-dark-secondary">Uploaded:</span>
                <p class="text-text-primary dark:text-text-dark-primary mt-1">{{ $document->created_at->format('M j, Y \a\t g:i A') }}</p>
            </div>
            @if($document->question_count)
                <div class="bg-surface dark:bg-surface-dark p-4 rounded-xl border border-border dark:border-border-dark sm:col-span-2">
                    <span class="font-medium text-text-secondary dark:text-text-dark-secondary">Questions:</span>
                    <p class="text-text-primary dark:text-text-dark-primary mt-1">{{ $document->question_count }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection