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
            <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">{{ $document->getFileSizeFormatted() }} • {{ $document->question_count }} Questions • Processed {{ $document->updated_at->diffForHumans() }}</p>
        </div>
        
        @if($document->questionSet)
            <a href="{{ route('documents.download', $document) }}" 
               class="px-6 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105">
                <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                </svg>
                Download Q&A
            </a>
        @endif
    </div>

    @if($document->questionSet && count($document->questionSet->questions_answers) > 0)
        <!-- Quiz Header -->
        <div class="mb-8 p-6 sm:p-8 bg-gradient-to-r from-primary-50 to-secondary-50 dark:from-primary-900/20 dark:to-secondary-900/20 border border-primary-200 dark:border-primary-800 rounded-2xl transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-primary-900 dark:text-primary-100 mb-2 transition-colors">Interactive Quiz</h2>
                    <p class="text-primary-800 dark:text-primary-300 transition-colors">Answer questions and get instant feedback with detailed explanations</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl font-bold text-primary-600 dark:text-primary-400 transition-colors" id="score">0/{{ count($document->questionSet->questions_answers) }}</div>
                    <div class="text-sm text-primary-700 dark:text-primary-500 font-medium">Score</div>
                </div>
            </div>
            
            <!-- Quiz Controls -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between pt-6 border-t border-primary-200 dark:border-primary-700 space-y-3 sm:space-y-0">
                <div class="flex flex-wrap gap-3">
                    <button id="showExplanations" class="px-4 py-2 bg-success-600 dark:bg-success-700 hover:bg-success-700 dark:hover:bg-success-600 text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                        </svg>
                        Show All Explanations
                    </button>
                    <button id="hideExplanations" class="px-4 py-2 bg-secondary-600 dark:bg-secondary-700 hover:bg-secondary-700 dark:hover:bg-secondary-600 text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105 hidden">
                        <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z"/>
                        </svg>
                        Hide Explanations
                    </button>
                </div>
                <button id="resetQuiz" class="px-4 py-2 bg-warning-600 dark:bg-warning-700 hover:bg-warning-700 dark:hover:bg-warning-600 text-white text-sm font-medium rounded-lg transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z"/>
</svg>
Reset Quiz
</button>
</div>
</div>
    <!-- Questions -->
    <div class="space-y-8">
        @foreach($document->questionSet->questions_answers as $index => $qa)
            <div class="bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-2xl p-6 sm:p-8 shadow-lg transition-all duration-300 question-container" data-question="{{ $index + 1 }}">
                <!-- Question -->
                <h3 class="text-xl font-bold text-text-primary dark:text-text-dark-primary mb-6 transition-colors">
                    <span class="inline-flex items-center justify-center w-8 h-8 bg-primary-100 dark:bg-primary-800 text-primary-600 dark:text-primary-400 rounded-full text-sm font-bold mr-3">{{ $index + 1 }}</span>
                    {{ $qa['question'] }}
                </h3>
                
                <!-- Options -->
                @if(isset($qa['options']) && is_array($qa['options']))
                    <div class="space-y-4 mb-6">
                        @foreach($qa['options'] as $optionIndex => $option)
                            @php $letter = chr(65 + $optionIndex); @endphp
                            <div class="option-choice p-4 border-2 border-border dark:border-border-dark rounded-xl cursor-pointer hover:border-primary-300 dark:hover:border-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all duration-200 group"
                                 data-question="{{ $index + 1 }}" 
                                 data-option="{{ $letter }}"
                                 data-correct="{{ isset($qa['correct_answer']) && $qa['correct_answer'] === $letter ? 'true' : 'false' }}">
                                <div class="flex items-center space-x-4">
                                    <div class="option-circle w-10 h-10 border-2 border-text-tertiary dark:border-text-dark-tertiary rounded-full flex items-center justify-center font-bold text-text-secondary dark:text-text-dark-secondary group-hover:border-primary-500 dark:group-hover:border-primary-400 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-all duration-200">
                                        {{ $letter }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-text-primary dark:text-text-dark-primary font-medium transition-colors">{{ $option }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Explanation Section -->
                    @if(isset($qa['explanation']))
                        <div class="explanation-section mt-6 p-6 bg-primary-50 dark:bg-primary-900/20 border-l-4 border-primary-400 dark:border-primary-600 rounded-r-xl hidden transition-all duration-300">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-primary-500 dark:text-primary-400 mr-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-primary-800 dark:text-primary-300 mb-2">Explanation</h4>
                                    <p class="text-primary-700 dark:text-primary-400 text-sm leading-relaxed mb-3">{{ $qa['explanation'] }}</p>
                                    @if(isset($qa['correct_answer']))
                                        <div class="inline-flex items-center px-3 py-1 bg-success-100 dark:bg-success-900/30 border border-success-200 dark:border-success-800 rounded-full">
                                            <svg class="w-4 h-4 text-success-600 dark:text-success-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                            <span class="text-success-600 dark:text-success-400 text-xs font-medium">Correct Answer: {{ $qa['correct_answer'] }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>

    <!-- Final Results Section -->
    <div id="resultsSection" class="mt-8 bg-gradient-to-r from-success-50 to-primary-50 dark:from-success-900/20 dark:to-primary-900/20 rounded-2xl p-6 sm:p-8 border border-success-200 dark:border-success-800 hidden transition-all duration-300">
        <h3 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-6 text-center transition-colors">🎉 Quiz Complete!</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="bg-success-100 dark:bg-success-900/30 rounded-xl p-4 border border-success-200 dark:border-success-700">
                <div class="text-3xl font-bold text-success-600 dark:text-success-400" id="correctCount">0</div>
                <div class="text-sm text-success-700 dark:text-success-500 font-medium">Correct</div>
            </div>
            <div class="bg-error-100 dark:bg-error-900/30 rounded-xl p-4 border border-error-200 dark:border-error-700">
                <div class="text-3xl font-bold text-error-600 dark:text-error-400" id="incorrectCount">0</div>
                <div class="text-sm text-error-700 dark:text-error-500 font-medium">Incorrect</div>
            </div>
            <div class="bg-primary-100 dark:bg-primary-900/30 rounded-xl p-4 border border-primary-200 dark:border-primary-700">
                <div class="text-3xl font-bold text-primary-600 dark:text-primary-400" id="percentage">0%</div>
                <div class="text-sm text-primary-700 dark:text-primary-500 font-medium">Score</div>
            </div>
            <div class="bg-secondary-100 dark:bg-secondary-900/30 rounded-xl p-4 border border-secondary-200 dark:border-secondary-700">
                <div class="text-3xl font-bold text-secondary-600 dark:text-secondary-400">{{ count($document->questionSet->questions_answers) }}</div>
                <div class="text-sm text-secondary-700 dark:text-secondary-500 font-medium">Total</div>
            </div>
        </div>
    </div>
@else
    <!-- No Questions Available -->
    <div class="text-center py-16 bg-surface dark:bg-surface-dark rounded-2xl border border-border dark:border-border-dark">
        <svg class="w-16 h-16 text-text-tertiary dark:text-text-dark-tertiary mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="text-xl font-semibold text-text-primary dark:text-text-dark-primary mb-2">No Questions Available</h3>
        <p class="text-text-secondary dark:text-text-dark-secondary">No questions found for this document.</p>
    </div>
@endif
</div>
<style>
.option-choice.selected {
    border-color: #57534e !important;
    background-color: #fafaf9 !important;
}

.option-choice.selected.dark {
    background-color: #1c1917 !important;
}

.option-choice.correct {
    border-color: #16a34a !important;
    background-color: #dcfce7 !important;
}

.option-choice.correct.dark {
    background-color: #14532d !important;
}

.option-choice.incorrect {
    border-color: #dc2626 !important;
    background-color: #fef2f2 !important;
}

.option-choice.incorrect.dark {
    background-color: #7f1d1d !important;
}

.option-circle.selected {
    background-color: #57534e !important;
    color: white !important;
    border-color: #57534e !important;
}

.option-circle.correct {
    background-color: #16a34a !important;
    color: white !important;
    border-color: #16a34a !important;
}

.option-circle.incorrect {
    background-color: #dc2626 !important;
    color: white !important;
    border-color: #dc2626 !important;
}

.explanation-section.show {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        max-height: 0;
    }
    to {
        opacity: 1;
        max-height: 200px;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let answeredQuestions = new Set();
    let correctAnswers = 0;
    const totalQuestions = document.querySelectorAll('.question-container').length;
    const scoreDisplay = document.getElementById('score');
    const showExplanationsBtn = document.getElementById('showExplanations');
    const hideExplanationsBtn = document.getElementById('hideExplanations');
    const resetQuizBtn = document.getElementById('resetQuiz');
    const resultsSection = document.getElementById('resultsSection');
    
    function updateScore() {
        if (scoreDisplay) {
            scoreDisplay.textContent = correctAnswers + '/' + totalQuestions;
        }
        
        // Show results if all questions answered
        if (answeredQuestions.size === totalQuestions) {
            showResults();
        }
    }
    
    function showResults() {
        const incorrectAnswers = totalQuestions - correctAnswers;
        const percentage = Math.round((correctAnswers / totalQuestions) * 100);
        
        document.getElementById('correctCount').textContent = correctAnswers;
        document.getElementById('incorrectCount').textContent = incorrectAnswers;
        document.getElementById('percentage').textContent = percentage + '%';
        
        resultsSection.classList.remove('hidden');
        resultsSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }
    
    // Handle option clicks
    document.querySelectorAll('.option-choice').forEach(function(option) {
        option.addEventListener('click', function() {
            const questionNum = this.getAttribute('data-question');
            const selectedOption = this.getAttribute('data-option');
            const isCorrect = this.getAttribute('data-correct') === 'true';
            const questionContainer = document.querySelector('.question-container[data-question="' + questionNum + '"]');
            
            // Don't allow re-answering
            if (answeredQuestions.has(questionNum)) {
                return;
            }
            
            // Clear previous selections in this question
            const allOptionsInQuestion = questionContainer.querySelectorAll('.option-choice');
            allOptionsInQuestion.forEach(function(opt) {
                opt.classList.remove('selected', 'correct', 'incorrect');
                opt.querySelector('.option-circle').classList.remove('selected', 'correct', 'incorrect');
            });
            
            // Mark this option as selected and show if correct/incorrect
            this.classList.add('selected');
            this.querySelector('.option-circle').classList.add('selected');
            
            if (isCorrect) {
                this.classList.add('correct');
                this.querySelector('.option-circle').classList.add('correct');
                correctAnswers++;
            } else {
                this.classList.add('incorrect'); 
                this.querySelector('.option-circle').classList.add('incorrect');
                
                // Also highlight the correct answer
                allOptionsInQuestion.forEach(function(opt) {
                    if (opt.getAttribute('data-correct') === 'true') {
                        opt.classList.add('correct');
                        opt.querySelector('.option-circle').classList.add('correct');
                    }
                });
            }
            
            // Add to answered questions and update score
            answeredQuestions.add(questionNum);
            updateScore();
            
            // Show explanation for this question automatically
            const explanation = questionContainer.querySelector('.explanation-section');
            if (explanation) {
                explanation.classList.remove('hidden');
                explanation.classList.add('show');
            }
        });
    });
    
    // Show all explanations
    if (showExplanationsBtn) {
        showExplanationsBtn.addEventListener('click', function() {
            document.querySelectorAll('.explanation-section').forEach(function(explanation) {
                explanation.classList.remove('hidden');
                explanation.classList.add('show');
            });
            showExplanationsBtn.classList.add('hidden');
            hideExplanationsBtn.classList.remove('hidden');
        });
    }
    
    // Hide all explanations
    if (hideExplanationsBtn) {
        hideExplanationsBtn.addEventListener('click', function() {
            document.querySelectorAll('.explanation-section').forEach(function(explanation) {
                // Only hide explanations for unanswered questions
                const questionContainer = explanation.closest('.question-container');
                const questionNum = questionContainer.getAttribute('data-question');
                
                if (!answeredQuestions.has(questionNum)) {
                    explanation.classList.add('hidden');
                    explanation.classList.remove('show');
                }
            });
            hideExplanationsBtn.classList.add('hidden');
            showExplanationsBtn.classList.remove('hidden');
        });
    }
    
    // Reset quiz
    if (resetQuizBtn) {
        resetQuizBtn.addEventListener('click', function() {
            // Clear all selections
            document.querySelectorAll('.option-choice').forEach(function(option) {
                option.classList.remove('selected', 'correct', 'incorrect');
                option.querySelector('.option-circle').classList.remove('selected', 'correct', 'incorrect');
            });
            
            // Hide all explanations
            document.querySelectorAll('.explanation-section').forEach(function(explanation) {
                explanation.classList.add('hidden');
                explanation.classList.remove('show');
            });
            
            // Hide results section
            resultsSection.classList.add('hidden');
            
            // Reset counters
            answeredQuestions.clear();
            correctAnswers = 0;
            updateScore();
            
            // Reset explanation buttons
            showExplanationsBtn.classList.remove('hidden');
            hideExplanationsBtn.classList.add('hidden');
            
            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }
    
    // Initialize score
    updateScore();
});
</script>
@endsection