@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-stone-50 via-stone-100 to-stone-200 dark:from-stone-950 dark:via-stone-900 dark:to-stone-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
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
                        <span class="ml-2 text-stone-600 dark:text-stone-300 truncate">{{ $folder->name }}</span>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Folder Header -->
        <div class="mb-8">
            <div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-sm rounded-2xl shadow-sm border border-stone-200 dark:border-stone-700 p-6 transition-colors">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-stone-100 dark:bg-stone-800 rounded-xl flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-6 h-6 text-stone-600 dark:text-stone-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <h1 class="text-xl sm:text-2xl font-bold text-stone-900 dark:text-stone-100 transition-colors truncate">{{ $folder->name }}</h1>
                            @if($folder->description)
                                <p class="text-stone-600 dark:text-stone-400 mt-1 text-sm sm:text-base transition-colors">{{ $folder->description }}</p>
                            @endif
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs sm:text-sm text-stone-500 dark:text-stone-400 mt-3 transition-colors">
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                    </svg>
                                    {{ $documents->total() }} documents
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                    {{ $folder->getCompletedDocumentCount() }} completed
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                                    </svg>
                                    {{ $folder->getTotalQuestionCount() }} total questions
                                </span>
                                <span>Created {{ $folder->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 lg:flex-shrink-0">
                        <a href="{{ route('folders.edit', $folder) }}" 
                           class="px-4 py-2 text-stone-700 dark:text-stone-300 bg-white dark:bg-stone-800 border border-stone-300 dark:border-stone-600 rounded-xl hover:bg-stone-50 dark:hover:bg-stone-700 hover:border-stone-400 dark:hover:border-stone-500 transition-all duration-200 transform hover:scale-105 text-center text-sm">
                            Edit Folder
                        </a>
                        <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-all duration-200 transform hover:scale-105 delete-folder-btn text-center text-sm"
                                data-id="{{ $folder->id }}" 
                                data-name="{{ $folder->name }}">
                            Delete Folder
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents in Folder -->
        <div class="bg-white/80 dark:bg-stone-900/80 backdrop-blur-sm rounded-2xl shadow-sm border border-stone-200 dark:border-stone-700 transition-colors overflow-hidden">
            <div class="px-6 py-4 border-b border-stone-200 dark:border-stone-700">
                <h2 class="text-lg font-semibold text-stone-900 dark:text-stone-100 transition-colors">Documents in this folder</h2>
            </div>

            @if($documents->count() > 0)
                <!-- Mobile Card Layout -->
                <div class="block lg:hidden">
                    <div class="divide-y divide-stone-200 dark:divide-stone-700">
                        @foreach($documents as $document)
                            <div class="p-4 hover:bg-stone-50 dark:hover:bg-stone-800/50 transition-colors">
                                <div class="flex items-start space-x-3">
                                    <div class="flex-shrink-0">
                                        @if($document->status === 'completed')
                                            <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                </svg>
                                            </div>
                                        @elseif($document->status === 'processing')
                                            <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                            </div>
                                        @elseif($document->status === 'text_extracted')
                                            <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                                </svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 bg-stone-100 dark:bg-stone-800 rounded-lg flex items-center justify-center">
                                                <svg class="w-4 h-4 text-stone-600 dark:text-stone-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-stone-900 dark:text-stone-100 truncate transition-colors">
                                            @if($document->title)
                                                {{ $document->title }}
                                                <span class="text-xs text-stone-500 dark:text-stone-400 block sm:inline sm:ml-2">({{ $document->original_name }})</span>
                                            @else
                                                {{ $document->original_name }}
                                            @endif
                                        </p>
                                        <div class="flex flex-wrap items-center gap-2 text-xs text-stone-500 dark:text-stone-400 mt-1">
                                            <span class="px-2 py-1 bg-stone-100 dark:bg-stone-800 rounded-full">{{ strtoupper($document->file_type) }}</span>
                                            <span>{{ $document->getFileSizeFormatted() }}</span>
                                            <span>{{ $document->question_count }} questions</span>
                                            <span>{{ $document->created_at->diffForHumans() }}</span>
                                        </div>

                                        <!-- Mobile Actions -->
                                        <div class="flex flex-wrap items-center gap-2 mt-3">
                                            @if($document->status === 'completed' && $document->questionSet)
                                                <a href="{{ route('documents.show', $document) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-stone-600 hover:bg-stone-700 text-white text-xs font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                                    {{ $document->format === 'flashcard' ? 'Study' : 'Quiz' }}
                                                </a>
                                                <a href="{{ route('documents.download', $document) }}" 
                                                   class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 transition-colors" title="Download">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                                    </svg>
                                                </a>
                                            @elseif($document->status === 'text_extracted')
                                                <a href="{{ route('documents.format', $document) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                                    Choose Format
                                                </a>
                                            @elseif($document->status === 'processing')
                                                <span class="text-xs text-blue-600 dark:text-blue-400">Processing...</span>
                                            @elseif($document->status === 'failed')
                                                <span class="text-xs text-red-600 dark:text-red-400">Failed</span>
                                            @endif

                                            <!-- Remove from Folder -->
                                            <button class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-amber-600 dark:hover:text-amber-400 remove-from-folder-btn transition-colors"
                                                    data-document-id="{{ $document->id }}"
                                                    title="Remove from folder">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                                </svg>
                                            </button>

                                            <!-- Edit/Delete -->
                                            <a href="{{ route('documents.edit', $document) }}" 
                                               class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 transition-colors" title="Edit Title">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                            </a>
                                            
                                            <button class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-red-600 dark:hover:text-red-400 delete-btn transition-colors" 
                                                    data-id="{{ $document->id }}" 
                                                    data-name="{{ $document->title ?? $document->original_name }}"
                                                    title="Delete Document">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Desktop Table Layout -->
                <div class="hidden lg:block">
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="border-b border-stone-200 dark:border-stone-700">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 dark:text-stone-400 uppercase tracking-wider">Document</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 dark:text-stone-400 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 dark:text-stone-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-stone-500 dark:text-stone-400 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-stone-500 dark:text-stone-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-200 dark:divide-stone-700">
                                @foreach($documents as $document)
                                    <tr class="hover:bg-stone-50 dark:hover:bg-stone-800/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0">
                                                    @if($document->status === 'completed')
                                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                                            </svg>
                                                        </div>
                                                    @elseif($document->status === 'processing')
                                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 animate-spin" fill="none" viewBox="0 0 24 24">
                                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                            </svg>
                                                        </div>
                                                    @elseif($document->status === 'text_extracted')
                                                        <div class="w-8 h-8 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                                            </svg>
                                                        </div>
                                                    @else
                                                        <div class="w-8 h-8 bg-stone-100 dark:bg-stone-800 rounded-lg flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-stone-600 dark:text-stone-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"/>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-stone-900 dark:text-stone-100">
                                                        @if($document->title)
                                                            {{ $document->title }}
                                                            <span class="text-xs text-stone-500 dark:text-stone-400 block sm:inline sm:ml-2">({{ $document->original_name }})</span>
                                                        @else
                                                            {{ $document->original_name }}
                                                        @endif
                                                    </div>
                                                    <div class="text-xs text-stone-500 dark:text-stone-400">{{ $document->getFileSizeFormatted() }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-stone-200">
                                                {{ strtoupper($document->file_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($document->status === 'completed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                    Completed
                                                </span>
                                            @elseif($document->status === 'processing')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                                    Processing
                                                </span>
                                            @elseif($document->status === 'text_extracted')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 dark:bg-amber-900/30 text-amber-800 dark:text-amber-300">
                                                    Ready
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-stone-100 dark:bg-stone-800 text-stone-800 dark:text-stone-200">
                                                    {{ ucfirst($document->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-stone-500 dark:text-stone-400">
                                            {{ $document->created_at->diffForHumans() }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                @if($document->status === 'completed' && $document->questionSet)
                                                    <a href="{{ route('documents.show', $document) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-stone-600 hover:bg-stone-700 text-white text-xs font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                                        {{ $document->format === 'flashcard' ? 'Study' : 'Quiz' }}
                                                    </a>
                                                    <a href="{{ route('documents.download', $document) }}" 
                                                       class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 transition-colors" title="Download">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                                        </svg>
                                                    </a>
                                                @elseif($document->status === 'text_extracted')
                                                    <a href="{{ route('documents.format', $document) }}" 
                                                       class="inline-flex items-center px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                                                        Choose Format
                                                    </a>
                                                @elseif($document->status === 'processing')
                                                    <span class="text-xs text-blue-600 dark:text-blue-400">Processing...</span>
                                                @elseif($document->status === 'failed')
                                                    <span class="text-xs text-red-600 dark:text-red-400">Failed</span>
                                                @endif

                                                <!-- Remove from Folder -->
                                                <button class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-amber-600 dark:hover:text-amber-400 remove-from-folder-btn transition-colors"
                                                        data-document-id="{{ $document->id }}"
                                                        title="Remove from folder">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                                    </svg>
                                                </button>

                                                <!-- Edit/Delete -->
                                                <a href="{{ route('documents.edit', $document) }}" 
                                                   class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-stone-600 dark:hover:text-stone-300 transition-colors" title="Edit Title">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                    </svg>
                                                </a>
                                                
                                                <button class="p-1.5 text-stone-500 dark:text-stone-400 hover:text-red-600 dark:hover:text-red-400 delete-btn transition-colors" 
                                                        data-id="{{ $document->id }}" 
                                                        data-name="{{ $document->title ?? $document->original_name }}"
                                                        title="Delete Document">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($documents->hasPages())
                    <div class="px-6 py-4 border-t border-stone-200 dark:border-stone-700">
                        {{ $documents->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-stone-100 dark:bg-stone-800 rounded-2xl flex items-center justify-center mx-auto mb-4 transition-colors">
                        <svg class="w-8 h-8 text-stone-600 dark:text-stone-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-stone-900 dark:text-stone-100 mb-2 transition-colors">No documents in this folder</h3>
                    <p class="text-stone-600 dark:text-stone-400 mb-6 text-sm sm:text-base transition-colors">Add documents to this folder from the main dashboard.</p>
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-6 py-3 bg-stone-600 hover:bg-stone-700 text-white font-medium rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        Go to Dashboard
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Document Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 dark:bg-black/70 hidden z-50 p-4 transition-colors">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-xl max-w-sm w-full p-6 border border-stone-200 dark:border-stone-700 transition-colors animate-scale-in">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-stone-900 dark:text-stone-100 transition-colors">Delete Document</h3>
                </div>
            </div>
            <p class="text-stone-600 dark:text-stone-400 mb-6 transition-colors">
                Are you sure you want to delete "<span id="deleteDocName" class="font-medium"></span>"?
            </p>
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button id="cancelDelete" class="px-4 py-2 text-stone-700 dark:text-stone-300 border border-stone-300 dark:border-stone-600 rounded-xl hover:bg-stone-50 dark:hover:bg-stone-800 transition-colors">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Folder Modal -->
<div id="deleteFolderModal" class="fixed inset-0 bg-black/50 dark:bg-black/70 hidden z-50 p-4 transition-colors">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-white dark:bg-stone-900 rounded-2xl shadow-xl max-w-sm w-full p-6 border border-stone-200 dark:border-stone-700 transition-colors animate-scale-in">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-stone-900 dark:text-stone-100 transition-colors">Delete Folder</h3>
                </div>
            </div>
            <p class="text-stone-600 dark:text-stone-400 mb-6 transition-colors">
                Are you sure you want to delete "<span id="deleteFolderName" class="font-medium"></span>"? Documents will be moved to Recent Documents.
            </p>
            <div class="flex flex-col sm:flex-row justify-end gap-3">
                <button id="cancelDeleteFolder" class="px-4 py-2 text-stone-700 dark:text-stone-300 border border-stone-300 dark:border-stone-600 rounded-xl hover:bg-stone-50 dark:hover:bg-stone-800 transition-colors">
                    Cancel
                </button>
                <form id="deleteFolderForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                        Delete Folder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete Document Modal
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteDocName = document.getElementById('deleteDocName');
    const cancelDelete = document.getElementById('cancelDelete');

    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const docId = this.getAttribute('data-id');
            const docName = this.getAttribute('data-name');
            
            deleteDocName.textContent = docName;
            deleteForm.action = '/documents/' + docId;
            deleteModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    cancelDelete.addEventListener('click', function() {
        deleteModal.classList.add('hidden');
        document.body.style.overflow = '';
    });

    // Delete Folder Modal
    const deleteFolderModal = document.getElementById('deleteFolderModal');
    const deleteFolderForm = document.getElementById('deleteFolderForm');
    const deleteFolderName = document.getElementById('deleteFolderName');
    const cancelDeleteFolder = document.getElementById('cancelDeleteFolder');

    document.querySelectorAll('.delete-folder-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const folderId = this.getAttribute('data-id');
            const folderName = this.getAttribute('data-name');
            
            deleteFolderName.textContent = folderName;
            deleteFolderForm.action = '/folders/' + folderId;
            deleteFolderModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
    });

    cancelDeleteFolder.addEventListener('click', function() {
        deleteFolderModal.classList.add('hidden');
        document.body.style.overflow = '';
    });

    // Remove from folder functionality
    document.querySelectorAll('.remove-from-folder-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const documentId = this.getAttribute('data-document-id');
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/move-document';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.innerHTML = '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                            '<input type="hidden" name="document_id" value="' + documentId + '">' +
                            '<input type="hidden" name="folder_id" value="">';
            
            document.body.appendChild(form);
            form.submit();
        });
    });

    // Close modals on click outside
    [deleteModal, deleteFolderModal].forEach(function(modal) {
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
    });

    // Close modals on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            [deleteModal, deleteFolderModal].forEach(function(modal) {
                if (modal && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
    });
});
</script>

<style>
@keyframes scaleIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.animate-scale-in {
    animation: scaleIn 0.2s ease-out;
}
</style>
@endsection