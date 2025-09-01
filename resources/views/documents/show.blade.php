{{-- resources/views/documents/show.blade.php --}}
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
            <p class="text-gray-600">{{ $document->getFileSizeFormatted() }} • {{ $document->question_count }} Questions • Processed {{ $document->updated_at->diffForHumans() }}</p>
        </div>
        
        @if($document->questionSet)
            <a href="{{ route('documents.download', $document) }}" 
               class="px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white rounded-md">
                Download Q&A
            </a>
        @endif
    </div>

    @if($document->questionSet && count($document->questionSet->questions_answers) > 0)
        <!-- Quiz Header -->
        <div class="mb-8 p-6 bg-gradient-to-r from-cyan-50 to-blue-50 border border-cyan-200 rounded-lg">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-xl font-bold text-cyan-900 mb-1">Interactive Quiz</h2>
                    <p class="text-cyan-800">Answer questions and get instant feedback with detailed explanations</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-cyan-600" id="score">0/{{ count($document->questionSet->questions_answers) }}</div>
                    <div class="text-sm text-cyan-700">Score</div>
                </div>
            </div>
            
            <!-- Quiz Controls -->
            <div class="flex items-center justify-between pt-4 border-t border-cyan-200">
                <div class="flex space-x-3">
                    <button id="showExplanations" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded-md transition-colors">
                        Show All Explanations
                    </button>
                    <button id="hideExplanations" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded-md transition-colors hidden">
                        Hide Explanations
                    </button>
                </div>
                <button id="resetQuiz" class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white text-sm rounded-md transition-colors">
                    Reset Quiz
                </button>
            </div>
        </div>

        <!-- Questions -->
        <div class="space-y-8">
            @foreach($document->questionSet->questions_answers as $index => $qa)
                <div class="bg-white border border-gray-200 rounded-lg p-6 shadow-sm question-container" data-question="{{ $index + 1 }}">
                    <!-- Question -->
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        {{ $index + 1 }}. {{ $qa['question'] }}
                    </h3>
                    
                    <!-- Options -->
                    @if(isset($qa['options']) && is_array($qa['options']))
                        <div class="space-y-3 mb-4">
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

                        <!-- Explanation Section -->
                        @if(isset($qa['explanation']))
                            <div class="explanation-section mt-4 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-r-lg hidden">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-500 mr-3 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-medium text-blue-800 mb-1">Explanation</h4>
                                        <p class="text-blue-700 text-sm leading-relaxed">{{ $qa['explanation'] }}</p>
                                        @if(isset($qa['correct_answer']))
                                            <p class="text-blue-600 text-xs font-medium mt-2">✓ Correct Answer: {{ $qa['correct_answer'] }}</p>
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
        <div id="resultsSection" class="mt-8 bg-gray-50 rounded-lg p-6 hidden">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Quiz Complete!</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div class="bg-green-100 rounded-lg p-3">
                    <div class="text-2xl font-bold text-green-600" id="correctCount">0</div>
                    <div class="text-sm text-green-700">Correct</div>
                </div>
                <div class="bg-red-100 rounded-lg p-3">
                    <div class="text-2xl font-bold text-red-600" id="incorrectCount">0</div>
                    <div class="text-sm text-red-700">Incorrect</div>
                </div>
                <div class="bg-blue-100 rounded-lg p-3">
                    <div class="text-2xl font-bold text-blue-600" id="percentage">0%</div>
                    <div class="text-sm text-blue-700">Score</div>
                </div>
                <div class="bg-purple-100 rounded-lg p-3">
                    <div class="text-2xl font-bold text-purple-600">{{ count($document->questionSet->questions_answers) }}</div>
                    <div class="text-sm text-purple-700">Total</div>
                </div>
            </div>
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