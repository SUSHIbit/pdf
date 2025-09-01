@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-cyan-600 hover:text-cyan-700 font-medium">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Document Header -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $document->original_name }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-gray-500">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                            </svg>
                            {{ strtoupper($document->file_type) }} Document
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8z" clip-rule="evenodd"/>
                            </svg>
                            {{ $document->getFileSizeFormatted() }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                            </svg>
                            Uploaded {{ $document->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
                
                <div class="text-right">
                    <div class="flex items-center text-green-600 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                        <span class="text-sm font-medium">Text Extracted</span>
                    </div>
                    <p class="text-xs text-gray-500">{{ str_word_count($document->extracted_text) }} words extracted</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">Next Step:</span> Review the extracted content and generate questions
                </div>
                <div class="flex space-x-3">
                    <form action="{{ route('documents.process', $document) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" 
                                class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-md transition-colors"
                                @if(auth()->user()->credits < 1) disabled title="Insufficient credits" @endif>
                            <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                            </svg>
                            Generate Questions (1 Credit)
                        </button>
                    </form>
                    
                    @if(auth()->user()->credits < 1)
                        <a href="{{ route('payment.packages') }}" 
                           class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white font-medium rounded-md transition-colors">
                            Buy Credits
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Extracted Text Content -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-gray-900">Extracted Text Content</h2>
            <button id="toggleContent" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium">
                <span id="toggleText">Collapse</span>
                <svg id="toggleIcon" class="w-4 h-4 inline ml-1 transform transition-transform" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                </svg>
            </button>
        </div>

        <div id="extractedContent" class="prose max-w-none">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 max-h-96 overflow-y-auto">
                <pre class="whitespace-pre-wrap text-sm text-gray-800 font-mono leading-relaxed">{{ $document->extracted_text }}</pre>
            </div>
        </div>

        <!-- Content Statistics -->
        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-cyan-50 rounded-lg p-3">
                    <div class="text-lg font-bold text-cyan-600">{{ str_word_count($document->extracted_text) }}</div>
                    <div class="text-xs text-cyan-700">Words</div>
                </div>
                <div class="bg-blue-50 rounded-lg p-3">
                    <div class="text-lg font-bold text-blue-600">{{ strlen($document->extracted_text) }}</div>
                    <div class="text-xs text-blue-700">Characters</div>
                </div>
                <div class="bg-green-50 rounded-lg p-3">
                    <div class="text-lg font-bold text-green-600">{{ substr_count($document->extracted_text, "\n") + 1 }}</div>
                    <div class="text-xs text-green-700">Lines</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-3">
                    <div class="text-lg font-bold text-purple-600">{{ count(array_filter(explode('.', $document->extracted_text))) }}</div>
                    <div class="text-xs text-purple-700">Sentences</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Preview Notice -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800 mb-1">Preview Complete</h3>
                <p class="text-sm text-blue-700">
                    This is the text that was extracted from your <strong>{{ strtoupper($document->file_type) }}</strong> document. 
                    When you generate questions, our AI will analyze this content to create relevant multiple-choice questions. 
                    Please review the extracted text to ensure it captured your document content correctly.
                </p>
                <div class="mt-2">
                    <p class="text-xs text-blue-600 font-medium">
                        💡 Tip: The AI works best with well-structured, informative content that contains clear concepts and facts.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggleButton = document.getElementById('toggleContent');
    const toggleText = document.getElementById('toggleText');
    const toggleIcon = document.getElementById('toggleIcon');
    const extractedContent = document.getElementById('extractedContent');
    
    let isCollapsed = false;

    toggleButton.addEventListener('click', function() {
        if (isCollapsed) {
            extractedContent.style.display = 'block';
            toggleText.textContent = 'Collapse';
            toggleIcon.style.transform = 'rotate(0deg)';
        } else {
            extractedContent.style.display = 'none';
            toggleText.textContent = 'Expand';
            toggleIcon.style.transform = 'rotate(180deg)';
        }
        isCollapsed = !isCollapsed;
    });
});
</script>
@endsection