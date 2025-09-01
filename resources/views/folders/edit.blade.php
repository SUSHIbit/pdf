@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <a href="{{ route('dashboard') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <a href="{{ route('folders.show', $folder) }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">{{ $folder->name }}</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500">Edit</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Folder</h1>
        <p class="text-gray-600">Update the name and description for "{{ $folder->name }}"</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('folders.update', $folder) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Folder Name
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $folder->name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500"
                       required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea id="description" 
                          name="description" 
                          rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500"
                          placeholder="Optional description for this folder">{{ old('description', $folder->description) }}</textarea>
                @error('description')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">Briefly describe what documents this folder contains.</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="button" 
                        onclick="history.back()"
                        class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md transition-colors">
                    Update Folder
                </button>
            </div>
        </form>
    </div>

    <!-- Folder Info -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-2">Folder Information</h3>
        <div class="text-sm text-gray-600 space-y-1">
            <p><span class="font-medium">Documents:</span> {{ $folder->getDocumentCount() }}</p>
            <p><span class="font-medium">Completed:</span> {{ $folder->getCompletedDocumentCount() }}</p>
            <p><span class="font-medium">Total Questions:</span> {{ $folder->getTotalQuestionCount() }}</p>
            <p><span class="font-medium">Created:</span> {{ $folder->created_at->format('M j, Y \a\t g:i A') }}</p>
        </div>
    </div>
</div>
@endsection