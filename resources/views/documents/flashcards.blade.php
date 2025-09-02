{{-- resources/views/documents/flashcards.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Back Navigation -->
    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-cyan-600 hover:text-cyan-700 font-medium">
            ← Back to Dashboard
        </a>
    </div>

    <!-- Document Header -->
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $document->original_name }}</h1>
            <p class="text-gray-600">{{ $document->getFileSizeFormatted() }} • {{ $document->question_count }} Flashcards • Processed {{ $document->updated_at->diffForHumans() }}</p>
        </div>
        
        @if($document->questionSet)
            <a href="{{ route('documents.download', $document) }}" 
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md">
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
        <div class="mb-8 p-6 bg-gradient-to-r from-purple-50 to-indigo-50 border border-purple-200 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-purple-900 mb-1">Interactive Flashcards</h2>
                    <p class="text-purple-800">Click cards to flip and navigate through your study deck</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-purple-600" id="cardCounter">1/{{ $totalCards }}</div>
                    <div class="text-sm text-purple-700">Card Progress</div>
                </div>
            </div>
            
            <!-- Flashcard Controls -->
            <div class="flex items-center justify-between pt-4 border-t border-purple-200">
                <div class="flex space-x-3">
                    <button id="shuffleCards" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-md transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                        </svg>
                        Shuffle
                    </button>
                    <button id="resetCards" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-md transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
                        </svg>
                        Reset
                    </button>
                </div>
                
                <!-- Navigation Buttons -->
                <div class="flex space-x-2">
                    <button id="prevCard" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-md transition-colors disabled:opacity-50">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"/>
                        </svg>
                        Previous
                    </button>
                    <button id="nextCard" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm rounded-md transition-colors">
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
                            <div class="bg-white border-2 border-purple-200 rounded-xl p-8 h-full flex flex-col justify-center items-center shadow-lg hover:shadow-xl transition-shadow">
                                <div class="text-center">
                                    <div class="text-sm text-purple-600 font-medium mb-4">FRONT</div>
                                    <div id="cardFront" class="text-2xl font-bold text-gray-900 leading-relaxed">
                                        <!-- Front content will be populated by JavaScript -->
                                    </div>
                                </div>
                                <div class="absolute bottom-4 left-4 text-xs text-gray-400">
                                    Click to flip
                                </div>
                            </div>
                        </div>
                        
                        <!-- Back of Card -->
                        <div class="flashcard-face flashcard-back absolute inset-0 w-full h-full backface-hidden rotate-y-180">
                            <div class="bg-purple-50 border-2 border-purple-300 rounded-xl p-8 h-full flex flex-col justify-center items-center shadow-lg hover:shadow-xl transition-shadow">
                                <div class="text-center">
                                    <div class="text-sm text-purple-600 font-medium mb-4">BACK</div>
                                    <div id="cardBack" class="text-lg text-gray-800 leading-relaxed">
                                        <!-- Back content will be populated by JavaScript -->
                                    </div>
                                </div>
                                <div class="absolute bottom-4 left-4 text-xs text-gray-400">
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
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm text-gray-600">Progress</span>
                <span class="text-sm text-gray-600" id="progressText">1 of {{ $totalCards }} cards</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div id="progressBar" class="bg-purple-600 h-2 rounded-full transition-all duration-300" data-initial-width="{{ $initialWidth }}"></div>
            </div>
        </div>

        <!-- Keyboard Shortcuts Help -->
        <div class="bg-gray-50 rounded-lg p-4">
            <h3 class="text-sm font-medium text-gray-900 mb-2">Keyboard Shortcuts</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs text-gray-600">
                <div><kbd class="bg-gray-200 px-2 py-1 rounded">Space</kbd> Flip card</div>
                <div><kbd class="bg-gray-200 px-2 py-1 rounded">←</kbd> Previous card</div>
                <div><kbd class="bg-gray-200 px-2 py-1 rounded">→</kbd> Next card</div>
                <div><kbd class="bg-gray-200 px-2 py-1 rounded">R</kbd> Reset deck</div>
            </div>
        </div>
    @else
        <!-- No Flashcards Available -->
        <div class="text-center py-12">
            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No flashcards available</h3>
            <p class="text-gray-600">No flashcards found for this document.</p>
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
    font-family: monospace;
    font-size: 0.875em;
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