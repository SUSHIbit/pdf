@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-cyan-600 hover:text-cyan-700 font-medium">
            ← Back to Dashboard
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Document Title</h1>
        <p class="text-gray-600">Change the title for "{{ $document->original_name }}"</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form action="{{ route('documents.update', $document) }}" method="POST">
            @csrf
            @method('PATCH')
            
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Document Title
                </label>
                <input type="text" 
                       id="title" 
                       name="title" 
                       value="{{ old('title', $document->title ?: $document->original_name) }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-cyan-500 focus:border-cyan-500"
                       required>
                @error('title')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-sm text-gray-500">This title will be used in the dashboard and downloads.</p>
            </div>

            <div class="flex items-center justify-between">
                <button type="button" 
                        onclick="history.back()"
                        class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors">
                    Cancel
                </button>
                <button type="submit"
                        class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md transition-colors">
                    Update Title
                </button>
            </div>
        </form>
    </div>

    <!-- Document Info -->
    <div class="mt-6 bg-gray-50 rounded-lg p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-2">Document Information</h3>
        <div class="text-sm text-gray-600 space-y-1">
            <p><span class="font-medium">Original filename:</span> {{ $document->original_name }}</p>
            <p><span class="font-medium">File type:</span> {{ strtoupper($document->file_type) }}</p>
            <p><span class="font-medium">File size:</span> {{ $document->getFileSizeFormatted() }}</p>
            <p><span class="font-medium">Uploaded:</span> {{ $document->created_at->format('M j, Y \a\t g:i A') }}</p>
            @if($document->question_count)
                <p><span class="font-medium">Questions:</span> {{ $document->question_count }}</p>
            @endif
        </div>
    </div>
</div>
@endsection