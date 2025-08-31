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
            <p class="text-gray-600">{{ $document->getFileSizeFormatted() }} • Processed {{ $document->updated_at->diffForHumans() }}</p>
        </div>
        
        @if($document->questionSet)
            <a href="{{ route('documents.download', $document) }}" 
               class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md">
                Download Questions
            </a>
        @endif
    </div>

    @if($document->questionSet && count($document->questionSet->questions_answers) > 0)
        <!-- Quiz Header -->
        <div class="mb-8 p-6 bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-cyan-900 mb-1">Interactive Quiz</h2>
                    <p class="text-cyan-800">Click your answers and see results instantly</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-cyan-600" id="score">0/{{ count($document->questionSet->questions_answers) }}</div>
                    <div class="text-sm text-cyan-700">Score</div>
                </div>
            </div>
        </div>

        <!-- Questions -->
        <div class="space-y-8">
            @foreach($document->questionSet->questions_answers as $index => $qa)
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm" data-question="{{ $index + 1 }}">
                    <!-- Question -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ $index + 1 }}. {{ $qa['question'] }}
                    </h3>
                    
                    <!-- Options -->
                    @if(isset($qa['options']) && is_array($qa['options']))
                        <div class="space-y-3">
                            @foreach($qa['options'] as $optionIndex => $option)
                                @php $letter = chr(65 + $optionIndex); @endphp
                                <div class="option-choice p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-cyan-300 hover:bg-cyan-50 transition-all"
                                     data-question="{{ $index + 1 }}" 
                                     data-option="{{ $letter }}"
                                     data-correct="{{ isset($qa['correct_answer']) && $qa['correct_answer'] === $letter ? 'true' : 'false' }}">
                                    <div class="flex items-center space-x-3">
                                        <div class="option-circle w-8 h-8 border-2 border-gray-400 rounded-full flex items-center justify-center font-bold text-gray-600">
                                            {{ $letter }}
                                        </div>
                                        <div class="flex-1">
                                            <p class="text-gray-800">{{ $option }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Answer Display -->
                        <div class="answer-reveal mt-4 p-4 bg-green-50 border border-green-200 rounded-lg hidden">
                            <p class="text-green-800">
                                <span class="font-bold">Correct Answer: {{ $qa['correct_answer'] ?? 'A' }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="mt-8 text-center space-x-4">
            <button id="showAnswers" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium">
                Show All Answers
            </button>
            <button id="resetQuiz" class="px-6 py-3 bg-amber-600 hover:bg-amber-700 text-white rounded-md font-medium">
                Reset Quiz
            </button>
        </div>
    @else
        <!-- No Questions Available -->
        <div class="text-center py-12">
            <p class="text-gray-600">No questions available for this document.</p>
        </div>
    @endif
</div>

<style>
.option-choice.selected {
    border-color: #0891b2 !important;
    background-color: #ecfeff !important;
}

.option-choice.correct {
    border-color: #059669 !important;
    background-color: #ecfdf5 !important;
}

.option-choice.incorrect {
    border-color: #dc2626 !important;
    background-color: #fef2f2 !important;
}

.option-circle.selected {
    background-color: #0891b2 !important;
    color: white !important;
    border-color: #0891b2 !important;
}

.option-circle.correct {
    background-color: #059669 !important;
    color: white !important;
    border-color: #059669 !important;
}

.option-circle.incorrect {
    background-color: #dc2626 !important;
    color: white !important;
    border-color: #dc2626 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let answeredQuestions = new Set();
    const totalQuestions = document.querySelectorAll('[data-question]').length / document.querySelectorAll('.option-choice').length * document.querySelectorAll('[data-question="1"]').length;
    const scoreDisplay = document.getElementById('score');
    
    // Calculate actual total questions
    const questionDivs = document.querySelectorAll('.bg-white[data-question]');
    const actualTotal = questionDivs.length;
    
    function updateScore() {
        if (scoreDisplay) {
            scoreDisplay.textContent = answeredQuestions.size + '/' + actualTotal;
        }
    }
    
    // Handle option clicks
    document.querySelectorAll('.option-choice').forEach(function(option) {
        option.addEventListener('click', function() {
            const questionNum = this.getAttribute('data-question');
            const selectedOption = this.getAttribute('data-option');
            const isCorrect = this.getAttribute('data-correct') === 'true';
            const questionDiv = document.querySelector('.bg-white[data-question="' + questionNum + '"]');
            
            // Clear previous selections in this question
            const allOptionsInQuestion = questionDiv.querySelectorAll('.option-choice');
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
            } else {
                this.classList.add('incorrect'); 
                this.querySelector('.option-circle').classList.add('incorrect');
            }
            
            // Add to answered questions
            answeredQuestions.add(questionNum);
            updateScore();
        });
    });
    
    // Show all answers
    const showAnswersBtn = document.getElementById('showAnswers');
    if (showAnswersBtn) {
        showAnswersBtn.addEventListener('click', function() {
            document.querySelectorAll('.answer-reveal').forEach(function(reveal) {
                reveal.classList.remove('hidden');
            });
        });
    }
    
    // Reset quiz
    const resetQuizBtn = document.getElementById('resetQuiz');
    if (resetQuizBtn) {
        resetQuizBtn.addEventListener('click', function() {
            // Clear all selections
            document.querySelectorAll('.option-choice').forEach(function(option) {
                option.classList.remove('selected', 'correct', 'incorrect');
                option.querySelector('.option-circle').classList.remove('selected', 'correct', 'incorrect');
            });
            
            // Hide all answers
            document.querySelectorAll('.answer-reveal').forEach(function(reveal) {
                reveal.classList.add('hidden');
            });
            
            // Reset score
            answeredQuestions.clear();
            updateScore();
        });
    }
    
    // Initialize score
    updateScore();
});
</script>
@endsection