@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-stone-50 via-stone-100 to-stone-200 dark:from-stone-950 dark:via-stone-900 dark:to-stone-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Navigation -->
        <div class="mb-6">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 sm:space-x-4 text-sm">
                    <li>
                        <a href="{{ route('dashboard') }}" class="text-stone-500 dark:text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 transition-colors">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                            </svg>
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-stone-400 dark:text-stone-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <a href="{{ route('dashboard') }}" class="ml-2 text-stone-500 dark:text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 transition-colors">Dashboard</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-stone-400 dark:text-stone-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <a href="{{ route('folders.show', $folder) }}" class="ml-2 text-stone-500 dark:text-stone-400 hover:text-stone-700 dark:hover:text-stone-200 transition-colors truncate">{{ $folder->name }}</a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-4 h-4 text-stone-400 dark:text-stone-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <span class="ml-2 text-stone-600 dark:text-stone-300">Edit</span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-stone-900 dark:text-stone-100 mb-2 transition-colors">
                Edit Folder
            </h1>
            <p class="text-stone-600 dark:text-stone-400 text-sm sm:text-base transition-colors">
                Update the name and description for "{{ $folder->name }}"
            </p>
        </div>

        <div class="grid gap-6 lg:gap-8">
            <!-- Edit Form -->
            <div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-sm rounded-2xl shadow-sm border border-stone-200 dark:border-stone-700 p-6 sm:p-8 transition-colors">
                <form action="{{ route('folders.update', $folder) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Folder Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-stone-900 dark:text-stone-100 transition-colors">
                            Folder Name
                        </label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name', $folder->name) }}"
                               required
                               class="block w-full px-4 py-3 bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-600 rounded-xl text-stone-900 dark:text-stone-100 placeholder-stone-500 dark:placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-500 dark:focus:ring-stone-400 focus:border-transparent transition-all duration-200 @error('name') border-red-500 dark:border-red-400 @enderror"
                               placeholder="Enter folder name">
                        @error('name')
                            <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <label for="description" class="block text-sm font-medium text-stone-900 dark:text-stone-100 transition-colors">
                            Description
                        </label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="4"
                                  class="block w-full px-4 py-3 bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-600 rounded-xl text-stone-900 dark:text-stone-100 placeholder-stone-500 dark:placeholder-stone-400 focus:outline-none focus:ring-2 focus:ring-stone-500 dark:focus:ring-stone-400 focus:border-transparent transition-all duration-200 @error('description') border-red-500 dark:border-red-400 @enderror"
                                  placeholder="Optional description for this folder">{{ old('description', $folder->description) }}</textarea>
                        @error('description')
                            <p class="text-sm text-red-600 dark:text-red-400 flex items-center mt-2">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-stone-500 dark:text-stone-400 transition-colors">
                            Briefly describe what documents this folder contains.
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-4">
                        <button type="button" 
                                onclick="history.back()"
                                class="w-full sm:w-auto px-6 py-3 text-stone-700 dark:text-stone-300 bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-600 rounded-xl hover:bg-stone-50 dark:hover:bg-stone-700 hover:border-stone-400 dark:hover:border-stone-500 transition-all duration-200 text-center">
                            Cancel
                        </button>
                        <button type="submit"
                                class="w-full sm:w-auto px-8 py-3 bg-stone-600 hover:bg-stone-700 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105 text-center">
                            Update Folder
                        </button>
                    </div>
                </form>
            </div>

            <!-- Folder Information -->
            <div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-sm rounded-2xl shadow-sm border border-stone-200 dark:border-stone-700 p-6 sm:p-8 transition-colors">
                <h3 class="text-lg font-semibold text-stone-900 dark:text-stone-100 mb-6 transition-colors">
                    Folder Information
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="flex justify-between sm:block">
                        <span class="font-medium text-stone-600 dark:text-stone-400 transition-colors">Documents:</span>
                        <span class="text-stone-900 dark:text-stone-100 transition-colors sm:ml-0 ml-2">{{ $folder->getDocumentCount() }}</span>
                    </div>
                    <div class="flex justify-between sm:block">
                        <span class="font-medium text-stone-600 dark:text-stone-400 transition-colors">Completed:</span>
                        <span class="text-stone-900 dark:text-stone-100 transition-colors sm:ml-0 ml-2">{{ $folder->getCompletedDocumentCount() }}</span>
                    </div>
                    <div class="flex justify-between sm:block">
                        <span class="font-medium text-stone-600 dark:text-stone-400 transition-colors">Total Questions:</span>
                        <span class="text-stone-900 dark:text-stone-100 transition-colors sm:ml-0 ml-2">{{ $folder->getTotalQuestionCount() }}</span>
                    </div>
                    <div class="flex justify-between sm:block">
                        <span class="font-medium text-stone-600 dark:text-stone-400 transition-colors">Created:</span>
                        <span class="text-stone-900 dark:text-stone-100 transition-colors sm:ml-0 ml-2">{{ $folder->created_at->format('M j, Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection