@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-cyan-600 hover:text-cyan-700 font-medium">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Document Header -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
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
        </div>
    </div>

    <!-- Format Selection -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4 text-center">Choose Your Study Format</h2>
            <p class="text-gray-600 text-center mb-8">Select how you'd like to study this document</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- MCQ Option -->
                <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-cyan-400 hover:shadow-md transition-all cursor-pointer group"
                     onclick="selectFormat('mcq')">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-cyan-200 transition-colors">
                            <svg class="w-8 h-8 text-cyan-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Multiple Choice Questions</h3>
                        <p class="text-gray-600 mb-4">Test your knowledge with interactive quiz questions</p>
                        
                        <ul class="text-sm text-gray-500 space-y-2 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                4 answer options per question
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Detailed explanations
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Interactive quiz interface
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Instant scoring
                            </li>
                        </ul>
                        
                        <div class="bg-cyan-50 rounded-lg p-3 mb-4">
                            <p class="text-sm text-cyan-800 font-medium">Perfect for testing comprehension</p>
                        </div>
                    </div>
                </div>

                <!-- Flashcard Option -->
                <div class="border-2 border-gray-200 rounded-xl p-6 hover:border-purple-400 hover:shadow-md transition-all cursor-pointer group"
                     onclick="selectFormat('flashcard')">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-purple-200 transition-colors">
                            <svg class="w-8 h-8 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Flashcards</h3>
                        <p class="text-gray-600 mb-4">Study with term and definition flashcards</p>
                        
                        <ul class="text-sm text-gray-500 space-y-2 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Front: Key terms & concepts
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Back: Definitions & explanations
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Click to flip cards
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Navigate through deck
                            </li>
                        </ul>
                        
                        <div class="bg-purple-50 rounded-lg p-3 mb-4">
                            <p class="text-sm text-purple-800 font-medium">Great for memorization</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Info -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex">
            <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-blue-800 mb-1">Ready for Processing</h3>
                <p class="text-sm text-blue-700">
                    Choose your preferred study format. Both options use the same credit system and allow you to select the quantity (10, 20, or 30 items).
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Hidden forms for submission -->
<form id="mcqForm" action="{{ route('documents.preview', $document) }}" method="GET" style="display: none;">
    <input type="hidden" name="format" value="mcq">
</form>

<form id="flashcardForm" action="{{ route('documents.preview', $document) }}" method="GET" style="display: none;">
    <input type="hidden" name="format" value="flashcard">
</form>

<script>
function selectFormat(format) {
    if (format === 'mcq') {
        document.getElementById('mcqForm').submit();
    } else if (format === 'flashcard') {
        document.getElementById('flashcardForm').submit();
    }
}

// Add keyboard support
document.addEventListener('keydown', function(e) {
    if (e.key === '1') {
        selectFormat('mcq');
    } else if (e.key === '2') {
        selectFormat('flashcard');
    }
});
</script>
@endsection