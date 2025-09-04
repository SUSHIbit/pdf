@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Navigation -->
    <div class="mb-6">
        <a href="{{ route('documents.format', $document) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
            ← Back to Format Selection
        </a>
    </div>

    <!-- Document Header -->
    <div class="mb-8">
        <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
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
                    <div class="flex items-center {{ request('format') === 'flashcard' ? 'text-secondary-600 dark:text-secondary-400' : 'text-primary-600 dark:text-primary-400' }} mb-2">
                        @if(request('format') === 'flashcard')
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z"/>
                                <path fill-rule="evenodd" d="M3 8a2 2 0 012-2v9a2 2 0 01-2-2V8z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-medium">Flashcard Format</span>
                        @else
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z"/>
                            </svg>
                            <span class="text-sm font-medium">MCQ Format</span>
                        @endif
                    </div>
                    <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">{{ str_word_count($document->extracted_text) }} words extracted</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quantity Selection Form -->
    <div class="mb-8">
        <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 sm:p-8 transition-all duration-300">
            <h2 class="text-xl font-bold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                @if(request('format') === 'flashcard')
                    Choose Number of Flashcards
                @else
                    Choose Number of Questions
                @endif
            </h2>
            
            <form action="{{ route('documents.process', $document) }}" method="POST" id="processForm">
                @csrf
                <input type="hidden" name="format" value="{{ request('format', 'mcq') }}">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="question-option group relative border-2 border-border dark:border-border-dark rounded-2xl p-6 cursor-pointer hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-200 transform hover:scale-105 bg-background dark:bg-background-dark" data-count="10" data-credits="1">
                        <input type="radio" name="question_count" value="10" id="q10" class="sr-only" checked>
                        <label for="q10" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">10</div>
                                <div class="text-sm text-text-secondary dark:text-text-dark-secondary mb-3 transition-colors">
                                    @if(request('format') === 'flashcard')
                                        Flashcards
                                    @else
                                        Questions
                                    @endif
                                </div>
                                <div class="text-xs text-primary-600 dark:text-primary-400 font-medium bg-primary-50 dark:bg-primary-900/20 px-3 py-1 rounded-full">1 Credit</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="question-option group relative border-2 border-border dark:border-border-dark rounded-2xl p-6 cursor-pointer hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-200 transform hover:scale-105 bg-background dark:bg-background-dark" data-count="20" data-credits="2">
                        <input type="radio" name="question_count" value="20" id="q20" class="sr-only">
                        <label for="q20" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">20</div>
                                <div class="text-sm text-text-secondary dark:text-text-dark-secondary mb-3 transition-colors">
                                    @if(request('format') === 'flashcard')
                                        Flashcards
                                    @else
                                        Questions
                                    @endif
                                </div>
                                <div class="text-xs text-primary-600 dark:text-primary-400 font-medium bg-primary-50 dark:bg-primary-900/20 px-3 py-1 rounded-full">2 Credits</div>
                                <div class="absolute -top-2 -right-2 bg-warning-500 text-white text-xs px-2 py-1 rounded-full font-medium transform rotate-12">Popular</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="question-option group relative border-2 border-border dark:border-border-dark rounded-2xl p-6 cursor-pointer hover:border-primary-400 dark:hover:border-primary-500 transition-all duration-200 transform hover:scale-105 bg-background dark:bg-background-dark" data-count="30" data-credits="3">
                        <input type="radio" name="question_count" value="30" id="q30" class="sr-only">
                        <label for="q30" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">30</div>
                                <div class="text-sm text-text-secondary dark:text-text-dark-secondary mb-3 transition-colors">
                                    @if(request('format') === 'flashcard')
                                        Flashcards
                                    @else
                                        Questions
                                    @endif
                                </div>
                                <div class="text-xs text-primary-600 dark:text-primary-400 font-medium bg-primary-50 dark:bg-primary-900/20 px-3 py-1 rounded-full">3 Credits</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Credit Alert -->
                <div id="creditAlert" class="hidden bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-xl p-4 mb-6 transition-all duration-300">
                    <div class="flex">
                        <svg class="w-5 h-5 text-warning-500 dark:text-warning-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-warning-800 dark:text-warning-300">Insufficient Credits</h3>
                            <p class="text-sm text-warning-700 dark:text-warning-400 mt-1" id="creditMessage"></p>
                            <a href="{{ route('payment.packages') }}" 
                               class="text-sm font-medium text-warning-800 dark:text-warning-300 underline hover:text-warning-900 dark:hover:text-warning-200 mt-2 inline-block transition-colors">
                                Buy Credits →
                            </a>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="generateBtn"
                            class="px-8 py-4 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 disabled:bg-border dark:disabled:bg-border-dark text-white font-semibold rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105 disabled:transform-none">
                        <svg class="w-5 h-5 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                        </svg>
                        <span id="generateText">
                            @if(request('format') === 'flashcard')
                                Generate Flashcards
                            @else
                                Generate Questions
                            @endif
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Extracted Text Content -->
    <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border border-border dark:border-border-dark p-6 sm:p-8 transition-all duration-300">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-xl font-bold text-text-primary dark:text-text-dark-primary transition-colors">Extracted Text Content</h2>
            <button id="toggleContent" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors group">
                <span id="toggleText">Expand</span>
                <svg id="toggleIcon" class="w-4 h-4 inline ml-1 transform rotate-180 transition-transform group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"/>
                </svg>
            </button>
        </div>

        <div id="extractedContent" class="prose max-w-none" style="display: none;">
            <div class="bg-background-secondary dark:bg-background-dark-secondary rounded-xl p-6 border border-border dark:border-border-dark max-h-96 overflow-y-auto transition-all duration-300">
                <pre class="whitespace-pre-wrap text-sm text-text-primary dark:text-text-dark-primary font-mono leading-relaxed">{{ $document->extracted_text }}</pre>
            </div>
        </div>

        <!-- Content Statistics -->
        <div class="mt-6 pt-6 border-t border-border dark:border-border-dark">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-primary-50 dark:bg-primary-900/20 rounded-xl p-4 border border-primary-200 dark:border-primary-800 transition-all duration-300">
                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ str_word_count($document->extracted_text) }}</div>
                    <div class="text-xs text-primary-700 dark:text-primary-500 font-medium">Words</div>
                </div>
                <div class="bg-success-50 dark:bg-success-900/20 rounded-xl p-4 border border-success-200 dark:border-success-800 transition-all duration-300">
                    <div class="text-2xl font-bold text-success-600 dark:text-success-400">{{ strlen($document->extracted_text) }}</div>
                    <div class="text-xs text-success-700 dark:text-success-500 font-medium">Characters</div>
                </div>
                <div class="bg-warning-50 dark:bg-warning-900/20 rounded-xl p-4 border border-warning-200 dark:border-warning-800 transition-all duration-300">
                    <div class="text-2xl font-bold text-warning-600 dark:text-warning-400">{{ substr_count($document->extracted_text, "\n") + 1 }}</div>
                    <div class="text-xs text-warning-700 dark:text-warning-500 font-medium">Lines</div>
                </div>
                <div class="bg-secondary-50 dark:bg-secondary-900/20 rounded-xl p-4 border border-secondary-200 dark:border-secondary-800 transition-all duration-300">
                    <div class="text-2xl font-bold text-secondary-600 dark:text-secondary-400">{{ count(array_filter(explode('.', $document->extracted_text))) }}</div>
                    <div class="text-xs text-secondary-700 dark:text-secondary-500 font-medium">Sentences</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Processing Info -->
    <div class="mt-6 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-2xl p-6 transition-all duration-300">
        <div class="flex">
            <svg class="w-5 h-5 text-primary-600 dark:text-primary-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
            </svg>
            <div>
                <h3 class="text-sm font-medium text-primary-800 dark:text-primary-300 mb-1">Ready for Processing</h3>
                <p class="text-sm text-primary-700 dark:text-primary-400">
                    @if(request('format') === 'flashcard')
                        Choose how many flashcards you want to generate from this text. Each flashcard will have a term/concept on the front and its definition/explanation on the back for effective studying.
                    @else
                        Choose how many questions you want to generate from this text. Each question will include four answer options and a detailed explanation to help with learning.
                    @endif
                </p>
                <div class="mt-2">
                    <p class="text-xs text-primary-600 dark:text-primary-500 font-medium">
                        💡 Tip: More {{ request('format') === 'flashcard' ? 'flashcards' : 'questions' }} provide better coverage of your document content but cost more credits.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script id="app-data" type="application/json">
{
    "userCredits": {{ auth()->user()->credits }},
    "format": "{{ request('format', 'mcq') }}"
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appData = JSON.parse(document.getElementById('app-data').textContent);
    const userCredits = appData.userCredits;
    const format = appData.format;
    
    const questionOptions = document.querySelectorAll('.question-option');
    const creditAlert = document.getElementById('creditAlert');
    const creditMessage = document.getElementById('creditMessage');
    const generateBtn = document.getElementById('generateBtn');
    const generateText = document.getElementById('generateText');
    const processForm = document.getElementById('processForm');
    const toggleButton = document.getElementById('toggleContent');
    const toggleText = document.getElementById('toggleText');
    const toggleIcon = document.getElementById('toggleIcon');
    const extractedContent = document.getElementById('extractedContent');

    let selectedCredits = 1;
    let isCollapsed = true;

    // Handle option selection
    questionOptions.forEach(function(option) {
        option.addEventListener('click', function() {
            const count = this.getAttribute('data-count');
            const credits = parseInt(this.getAttribute('data-credits'));
            
            // Clear previous selections
            questionOptions.forEach(opt => {
                opt.classList.remove('border-primary-500', 'bg-primary-50', 'dark:border-primary-400', 'dark:bg-primary-900/20');
                opt.classList.add('border-border', 'dark:border-border-dark');
            });
            
            // Select this option
            this.classList.remove('border-border', 'dark:border-border-dark');
            this.classList.add('border-primary-500', 'bg-primary-50', 'dark:border-primary-400', 'dark:bg-primary-900/20');
            
            // Update radio button
            document.getElementById('q' + count).checked = true;
            selectedCredits = credits;
            
            // Update button text
            const itemType = format === 'flashcard' ? 'Flashcards' : 'Questions';
            generateText.textContent = `Generate ${count} ${itemType} (${credits} Credit${credits > 1 ? 's' : ''})`;
            
            checkCredits();
        });
    });

    // Initialize first option as selected
    document.querySelector('.question-option[data-count="10"]').click();

    function checkCredits() {
        if (userCredits < selectedCredits) {
            creditAlert.classList.remove('hidden');
            creditMessage.textContent = `You need ${selectedCredits} credits but only have ${userCredits}. Please purchase more credits.`;
            generateBtn.disabled = true;
        } else {
            creditAlert.classList.add('hidden');
            generateBtn.disabled = false;
        }
    }

    // Form submission
    processForm.addEventListener('submit', function(e) {
        generateBtn.disabled = true;
        const itemType = format === 'flashcard' ? 'Flashcards' : 'Questions';
        generateText.textContent = `Generating ${itemType}...`;
        
        generateBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating ' + itemType + '...';
    });

    // Toggle content
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

    checkCredits();
});
</script>
@endsection