@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Navigation -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Document Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">{{ $document->original_name }}</h1>
            <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">{{ $document->getFileSizeFormatted() }} • {{ $document->question_count }} Flashcards • Processed {{ $document->updated_at->diffForHumans() }}</p>
        </div>
        
        @if($document->questionSet)
            <a href="{{ route('documents.download', $document) }}" 
               class="px-6 py-3 bg-secondary-600 dark:bg-secondary-700 hover:bg-secondary-700 dark:hover:bg-secondary-600 text-white font-medium rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105">
                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                </svg>
                Download Flashcards
            </a>
        @endif
    </div>

    @if($document->questionSet && count($document->questionSet->questions_answers) > 0)
        @php
            $totalCards = count($document->questionSet->questions_answers);
            $initialWidth = $totalCards > 0 ? round(100 / $totalCards, 2) : 0;
        @endphp
        
        <!-- Flashcard Header -->
        <div class="mb-8 p-6 sm:p-8 bg-gradient-to-r from-secondary-50 to-primary-50 dark:from-secondary-900/20 dark:to-primary-900/20 border border-secondary-200 dark:border-secondary-800 rounded-2xl transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-secondary-900 dark:text-secondary-100 mb-2 transition-colors">Interactive Flashcards</h2>
                    <p class="text-secondary-800 dark:text-secondary-300 transition-colors">Click cards to flip and navigate through your study deck</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold text-secondary-600 dark:text-secondary-400 transition-colors" id="cardCounter">1/{{ $totalCards }}</div>
                    <div class="text-sm text-secondary-700 dark:text-secondary-500 font-medium">Card Progress</div>
                </div>
            </div>
            
            <!-- Flashcard Controls -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-6 border-t border-secondary-200 dark:border-secondary-700 space-y-4 sm:space-y-0">
                <div class="flex flex-wrap gap-3">
                    <button id="shuffleCards" class="px-4 py-2 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                        </svg>
                        Shuffle
                    </button>
                    <button id="resetCards" class="px-4 py-2 bg-secondary-600 dark:bg-secondary-700 hover:bg-secondary-700 dark:hover:bg-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                        </svg>
                        Reset
                    </button>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="flex space-x-2">
                    <button id="prevCard" class="px-4 py-2 bg-text-tertiary dark:bg-text-dark-tertiary hover:bg-text-secondary dark:hover:bg-text-dark-secondary text-white text-sm font-medium rounded-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/>
                        </svg>
                        Previous
                    </button>
                    <button id="nextCard" class="px-4 py-2 bg-text-tertiary dark:bg-text-dark-tertiary hover:bg-text-secondary dark:hover:bg-text-dark-secondary text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                        Next
                        <svg class="w-4 h-4 inline ml-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Flashcard Container -->
        <div class="mb-8">
            <div class="relative">
                <!-- Single Flashcard -->
                <div id="flashcardContainer" class="perspective-1000">
                    <div id="flashcard" class="flashcard-inner relative w-full h-80 cursor-pointer transform-style-preserve-3d transition-transform duration-600">
                        <!-- Front of Card -->
                        <div class="flashcard-face flashcard-front absolute inset-0 w-full h-full backface-hidden">
                            <div class="bg-surface dark:bg-surface-dark border-2 border-secondary-200 dark:border-secondary-700 rounded-2xl p-8 h-full flex flex-col justify-center items-center shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                                <div class="text-center">
                                    <div class="text-sm text-secondary-600 dark:text-secondary-400 font-medium mb-6 bg-secondary-100 dark:bg-secondary-900/20 px-4 py-2 rounded-full">FRONT</div>
                                    <div id="cardFront" class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-text-dark-primary leading-relaxed transition-colors">
                                        <!-- Front content will be populated by JavaScript -->
                                    </div>
                                </div>
                                <div class="absolute bottom-6 left-6 text-xs text-text-tertiary dark:text-text-dark-tertiary bg-background-secondary dark:bg-background-dark-secondary px-3 py-1 rounded-full">
                                    Click to flip
                                </div>
                            </div>
                        </div>
                        
                        <!-- Back of Card -->
                        <div class="flashcard-face flashcard-back absolute inset-0 w-full h-full backface-hidden rotate-y-180">
                            <div class="bg-secondary-50 dark:bg-secondary-900/20 border-2 border-secondary-300 dark:border-secondary-600 rounded-2xl p-8 h-full flex flex-col justify-center items-center shadow-xl hover:shadow-2xl transition-all duration-300 transform hover:scale-105">
                                <div class="text-center">
                                    <div class="text-sm text-secondary-600 dark:text-secondary-400 font-medium mb-6 bg-secondary-200 dark:bg-secondary-800/30 px-4 py-2 rounded-full">BACK</div>
                                    <div id="cardBack" class="text-lg sm:text-xl text-text-primary dark:text-text-dark-primary leading-relaxed transition-colors">
                                        <!-- Back content will be populated by JavaScript -->
                                    </div>
                                </div>
                                <div class="absolute bottom-6 left-6 text-xs text-text-tertiary dark:text-text-dark-tertiary bg-secondary-100 dark:bg-secondary-800/20 px-3 py-1 rounded-full">
                                    Click to flip
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary transition-colors">Progress</span>
                <span class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary transition-colors" id="progressText">1 of {{ $totalCards }} cards</span>
            </div>
            <div class="w-full bg-background-secondary dark:bg-background-dark-secondary rounded-full h-3 border border-border dark:border-border-dark">
                <div id="progressBar" class="bg-secondary-600 dark:bg-secondary-500 h-3 rounded-full transition-all duration-300 relative overflow-hidden" data-initial-width="{{ $initialWidth }}">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent animate-pulse"></div>
                </div>
            </div>
        </div>

        <!-- Keyboard Shortcuts Help -->
        <div class="bg-background-secondary dark:bg-background-dark-secondary rounded-2xl p-6 border border-border dark:border-border-dark transition-all duration-300">
            <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">Keyboard Shortcuts</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm text-text-secondary dark:text-text-dark-secondary">
                <div class="flex items-center">
                    <kbd class="bg-surface dark:bg-surface-dark px-3 py-2 rounded-lg border border-border dark:border-border-dark font-mono text-xs mr-3 font-semibold">Space</kbd>
                    <span>Flip card</span>
                </div>
                <div class="flex items-center">
                    <kbd class="bg-surface dark:bg-surface-dark px-3 py-2 rounded-lg border border-border dark:border-border-dark font-mono text-xs mr-3 font-semibold">←</kbd>
                    <span>Previous card</span>
                </div>
                <div class="flex items-center">
                    <kbd class="bg-surface dark:bg-surface-dark px-3 py-2 rounded-lg border border-border dark:border-border-dark font-mono text-xs mr-3 font-semibold">→</kbd>
                    <span>Next card</span>
                </div>
                <div class="flex items-center">
                    <kbd class="bg-surface dark:bg-surface-dark px-3 py-2 rounded-lg border border-border dark:border-border-dark font-mono text-xs mr-3 font-semibold">R</kbd>
                    <span>Reset deck</span>
                </div>
            </div>
        </div>
    @else
        <!-- No Flashcards Available -->
        <div class="text-center py-16 bg-surface dark:bg-surface-dark rounded-2xl border border-border dark:border-border-dark">
            <svg class="w-16 h-16 text-text-tertiary dark:text-text-dark-tertiary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-xl font-semibold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">No flashcards available</h3>
            <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">No flashcards found for this document.</p>
        </div>
    @endif
</div>

<!-- Flashcard data -->
<script id="flashcard-data" type="application/json">
@if($document->questionSet && count($document->questionSet->questions_answers) > 0)
{!! json_encode($document->questionSet->questions_answers) !!}
@else
[]
@endif
</script>

<style>
.perspective-1000 {
    perspective: 1000px;
}

.transform-style-preserve-3d {
    transform-style: preserve-3d;
}

.backface-hidden {
    backface-visibility: hidden;
}

.rotate-y-180 {
    transform: rotateY(180deg);
}

.flashcard-inner.flipped {
    transform: rotateY(180deg);
}

.flashcard-face {
    transition: all 0.3s ease;
}

kbd {
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', 'Courier New', monospace;
    font-size: 0.75rem;
    font-weight: 600;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const flashcardData = JSON.parse(document.getElementById('flashcard-data').textContent);
    
    if (flashcardData.length === 0) {
        return;
    }

    let currentCardIndex = 0;
    let isFlipped = false;
    let originalOrder = [...flashcardData];
    let currentDeck = [...flashcardData];

    // DOM elements
    const flashcard = document.getElementById('flashcard');
    const cardFront = document.getElementById('cardFront');
    const cardBack = document.getElementById('cardBack');
    const cardCounter = document.getElementById('cardCounter');
    const progressText = document.getElementById('progressText');
    const progressBar = document.getElementById('progressBar');
    const prevButton = document.getElementById('prevCard');
    const nextButton = document.getElementById('nextCard');
    const shuffleButton = document.getElementById('shuffleCards');
    const resetButton = document.getElementById('resetCards');

    // Set initial progress bar width using JavaScript
    const initialWidth = progressBar.getAttribute('data-initial-width');
    progressBar.style.width = initialWidth + '%';

    function updateCard() {
        const currentCard = currentDeck[currentCardIndex];
        cardFront.textContent = currentCard.front || 'No front text';
        cardBack.textContent = currentCard.back || 'No back text';
        
        // Update counter and progress
        cardCounter.textContent = `${currentCardIndex + 1}/${currentDeck.length}`;
        progressText.textContent = `${currentCardIndex + 1} of ${currentDeck.length} cards`;
        
        const progressPercent = ((currentCardIndex + 1) / currentDeck.length) * 100;
        progressBar.style.width = Math.round(progressPercent) + '%';
        
        // Update navigation buttons
        prevButton.disabled = currentCardIndex === 0;
        nextButton.disabled = currentCardIndex === currentDeck.length - 1;
        
        // Reset flip state
        if (isFlipped) {
            flipCard();
        }
    }

    function flipCard() {
        flashcard.classList.toggle('flipped');
        isFlipped = !isFlipped;
    }

    function nextCard() {
        if (currentCardIndex < currentDeck.length - 1) {
            currentCardIndex++;
            updateCard();
        }
    }

    function prevCard() {
        if (currentCardIndex > 0) {
            currentCardIndex--;
            updateCard();
        }
    }

    function shuffleDeck() {
        // Fisher-Yates shuffle
        for (let i = currentDeck.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [currentDeck[i], currentDeck[j]] = [currentDeck[j], currentDeck[i]];
        }
        currentCardIndex = 0;
        updateCard();
    }

    function resetDeck() {
        currentDeck = [...originalOrder];
        currentCardIndex = 0;
        updateCard();
    }

    // Event listeners
    if (flashcard) flashcard.addEventListener('click', flipCard);
    if (nextButton) nextButton.addEventListener('click', nextCard);
    if (prevButton) prevButton.addEventListener('click', prevCard);
    if (shuffleButton) shuffleButton.addEventListener('click', shuffleDeck);
    if (resetButton) resetButton.addEventListener('click', resetDeck);

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        switch(e.key) {
            case ' ':
                e.preventDefault();
                flipCard();
                break;
            case 'ArrowLeft':
                e.preventDefault();
                prevCard();
                break;
            case 'ArrowRight':
                e.preventDefault();
                nextCard();
                break;
            case 'r':
            case 'R':
                e.preventDefault();
                resetDeck();
                break;
            case 's':
            case 'S':
                e.preventDefault();
                shuffleDeck();
                break;
        }
    });

    // Initialize first card
    updateCard();
});
</script>
@endsection