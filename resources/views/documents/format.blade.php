@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Document Header -->
    <div class="mb-8">
        <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">{{ $document->original_name }}</h1>
                    <div class="flex items-center space-x-4 text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
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
                    <div class="flex items-center text-success-600 dark:text-success-400 mb-2">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                        <span class="text-sm font-medium">Text Extracted</span>
                    </div>
                    <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">{{ str_word_count($document->extracted_text) }} words extracted</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Format Selection -->
    <div class="mb-8">
        <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 sm:p-8 transition-all duration-300">
            <h2 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-4 text-center transition-colors">Choose Your Study Format</h2>
            <p class="text-text-secondary dark:text-text-dark-secondary text-center mb-8 transition-colors">Select how you'd like to study this document</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <!-- MCQ Option -->
                <div class="group border-2 border-border dark:border-border-dark rounded-2xl p-6 hover:border-primary-400 dark:hover:border-primary-500 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105 bg-background dark:bg-background-dark"
                     onclick="selectFormat('mcq')">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 dark:group-hover:bg-primary-700 transition-colors">
                            <svg class="w-8 h-8 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">Multiple Choice Questions</h3>
                        <p class="text-text-secondary dark:text-text-dark-secondary mb-4 transition-colors">Test your knowledge with interactive quiz questions</p>
                        
                        <ul class="text-sm text-text-tertiary dark:text-text-dark-tertiary space-y-2 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                4 answer options per question
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Detailed explanations
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Interactive quiz interface
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Instant scoring
                            </li>
                        </ul>
                        
                        <div class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-3 mb-4">
                            <p class="text-sm text-primary-800 dark:text-primary-300 font-medium">Perfect for testing comprehension</p>
                        </div>
                    </div>
                </div>

                <!-- Flashcard Option -->
                <div class="group border-2 border-border dark:border-border-dark rounded-2xl p-6 hover:border-secondary-400 dark:hover:border-secondary-500 hover:shadow-lg transition-all cursor-pointer transform hover:scale-105 bg-background dark:bg-background-dark"
                     onclick="selectFormat('flashcard')">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-secondary-100 dark:bg-secondary-800 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:bg-secondary-200 dark:group-hover:bg-secondary-700 transition-colors">
                            <svg class="w-8 h-8 text-secondary-600 dark:text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">Flashcards</h3>
                        <p class="text-text-secondary dark:text-text-dark-secondary mb-4 transition-colors">Study with term and definition flashcards</p>
                        
                        <ul class="text-sm text-text-tertiary dark:text-text-dark-tertiary space-y-2 mb-6">
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Front: Key terms & concepts
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Back: Definitions & explanations
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Click to flip cards
                            </li>
                            <li class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                Navigate through deck
                            </li>
                        </ul>
                        
                        <div class="bg-secondary-50 dark:bg-secondary-900/20 rounded-xl p-3 mb-4">
                            <p class="text-sm text-secondary-800 dark:text-secondary-300 font-medium">Great for memorization</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Info -->
    <div class="bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-2xl p-6 transition-all duration-300">
        <div class="flex">
            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-primary-800 dark:text-primary-300 mb-1">Ready for Processing</h3>
                <p class="text-sm text-primary-700 dark:text-primary-400">
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