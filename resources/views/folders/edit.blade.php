@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 transition-colors duration-300">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-4">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-text-tertiary dark:text-text-dark-tertiary hover:text-text-secondary dark:hover:text-text-dark-secondary transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-text-tertiary dark:text-text-dark-tertiary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                            </svg>
                            <a href="{{ route('dashboard') }}" class="ml-4 text-sm font-medium text-text-secondary dark:text-text-dark-secondary hover:text-text-primary dark:hover:text-text-dark-primary transition-colors">Dashboard</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-text-tertiary dark:text-text-dark-tertiary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                            </svg>
                            <a href="{{ route('folders.show', $folder) }}" class="ml-4 text-sm font-medium text-text-secondary dark:text-text-dark-secondary hover:text-text-primary dark:hover:text-text-dark-primary transition-colors">{{ $folder->name }}</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-text-tertiary dark:text-text-dark-tertiary" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                            </svg>
                            <span class="ml-4 text-sm font-medium text-text-secondary dark:text-text-dark-secondary">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                Edit Folder
            </h1>
            <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">
                Update the name and description for "{{ $folder->name }}"
            </p>
        </div>

        <!-- Edit Form -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-colors">
            <form action="{{ route('folders.update', $folder) }}" method="POST" class="space-y-5">
                @csrf
                @method('PATCH')
                
                <!-- Folder Name -->
                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                        Folder Name
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name', $folder->name) }}"
                           required
                           class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('name') border-error-500 dark:border-error-400 @enderror"
                           placeholder="Enter folder name">
                    @error('name')
                        <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="space-y-2">
                    <label for="description" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary transition-colors">
                        Description
                    </label>
                    <textarea id="description" 
                              name="description" 
                              rows="4"
                              class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200 @error('description') border-error-500 dark:border-error-400 @enderror"
                              placeholder="Optional description for this folder">{{ old('description', $folder->description) }}</textarea>
                    @error('description')
                        <p class="text-sm text-error-600 dark:text-error-400 flex items-center mt-2">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                    <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary transition-colors">
                        Briefly describe what documents this folder contains.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-4">
                    <button type="button" 
                            onclick="history.back()"
                            class="px-4 py-2 text-text-secondary dark:text-text-dark-secondary bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 hover:border-primary-300 dark:hover:border-primary-600 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105">
                        Update Folder
                    </button>
                </div>
            </form>
        </div>

        <!-- Folder Information -->
        <div class="mt-6 bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-colors">
            <h3 class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                Folder Information
            </h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-text-secondary dark:text-text-dark-secondary transition-colors">Documents:</span>
                    <span class="text-text-primary dark:text-text-dark-primary transition-colors ml-2">{{ $folder->getDocumentCount() }}</span>
                </div>
                <div>
                    <span class="font-medium text-text-secondary dark:text-text-dark-secondary transition-colors">Completed:</span>
                    <span class="text-text-primary dark:text-text-dark-primary transition-colors ml-2">{{ $folder->getCompletedDocumentCount() }}</span>
                </div>
                <div>
                    <span class="font-medium text-text-secondary dark:text-text-dark-secondary transition-colors">Total Questions:</span>
                    <span class="text-text-primary dark:text-text-dark-primary transition-colors ml-2">{{ $folder->getTotalQuestionCount() }}</span>
                </div>
                <div>
                    <span class="font-medium text-text-secondary dark:text-text-dark-secondary transition-colors">Created:</span>
                    <span class="text-text-primary dark:text-text-dark-primary transition-colors ml-2">{{ $folder->created_at->format('M j, Y') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection