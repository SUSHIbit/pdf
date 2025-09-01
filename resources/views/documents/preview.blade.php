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
        </div>
    </div>

    <!-- Question Selection Form -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Choose Number of Questions</h2>
            
            <form action="{{ route('documents.process', $document) }}" method="POST" id="processForm">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="question-option relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-cyan-400 transition-colors" data-count="10" data-credits="1">
                        <input type="radio" name="question_count" value="10" id="q10" class="sr-only" checked>
                        <label for="q10" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900 mb-1">10</div>
                                <div class="text-sm text-gray-600 mb-2">Questions</div>
                                <div class="text-xs text-cyan-600 font-medium">1 Credit</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="question-option relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-cyan-400 transition-colors" data-count="20" data-credits="2">
                        <input type="radio" name="question_count" value="20" id="q20" class="sr-only">
                        <label for="q20" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900 mb-1">20</div>
                                <div class="text-sm text-gray-600 mb-2">Questions</div>
                                <div class="text-xs text-cyan-600 font-medium">2 Credits</div>
                                <div class="absolute top-2 right-2 bg-cyan-500 text-white text-xs px-2 py-1 rounded-full">Popular</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="question-option relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-cyan-400 transition-colors" data-count="30" data-credits="3">
                        <input type="radio" name="question_count" value="30" id="q30" class="sr-only">
                        <label for="q30" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900 mb-1">30</div>
                                <div class="text-sm text-gray-600 mb-2">Questions</div>
                                <div class="text-xs text-cyan-600 font-medium">3 Credits</div>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Credit Alert -->
                <div id="creditAlert" class="hidden bg-amber-50 border border-amber-200 rounded-md p-4 mb-6">
                    <div class="flex">
                        <svg class="w-5 h-5 text-amber-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
<path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                       </svg>
                       <div>
                           <h3 class="text-sm font-medium text-amber-800">Insufficient Credits</h3>
                           <p class="text-sm text-amber-700 mt-1" id="creditMessage"></p>
                           <a href="{{ route('payment.packages') }}" 
                              class="text-sm font-medium text-amber-800 underline hover:text-amber-900 mt-2 inline-block">
                               Buy Credits →
                           </a>
                       </div>
                   </div>
               </div>

               <div class="flex justify-end">
                   <button type="submit" id="generateBtn"
                           class="px-6 py-3 bg-cyan-600 hover:bg-cyan-700 disabled:bg-gray-400 text-white font-medium rounded-md transition-colors">
                       <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                           <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                       </svg>
                       <span id="generateText">Generate Questions</span>
                   </button>
               </div>
           </form>
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

   <!-- Processing Info -->
   <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
       <div class="flex">
           <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
               <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
           </svg>
           <div>
               <h3 class="text-sm font-medium text-blue-800 mb-1">Ready for Processing</h3>
               <p class="text-sm text-blue-700">
                   Choose how many questions you want to generate from this text. Each question will include four answer options and a detailed explanation to help with learning.
               </p>
               <div class="mt-2">
                   <p class="text-xs text-blue-600 font-medium">
                       💡 Tip: More questions provide better coverage of your document content but cost more credits.
                   </p>
               </div>
           </div>
       </div>
   </div>
</div>

<script id="app-data" type="application/json">
{
   "userCredits": {{ auth()->user()->credits }}
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
   const appData = JSON.parse(document.getElementById('app-data').textContent);
   const userCredits = appData.userCredits;
   
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

   let selectedCredits = 1; // Default
   let isCollapsed = false;

   // Handle question option selection
   questionOptions.forEach(function(option) {
       option.addEventListener('click', function() {
           const count = this.getAttribute('data-count');
           const credits = parseInt(this.getAttribute('data-credits'));
           
           // Clear previous selections
           questionOptions.forEach(opt => {
               opt.classList.remove('border-cyan-500', 'bg-cyan-50');
               opt.classList.add('border-gray-200');
           });
           
           // Select this option
           this.classList.remove('border-gray-200');
           this.classList.add('border-cyan-500', 'bg-cyan-50');
           
           // Update radio button
           document.getElementById('q' + count).checked = true;
           selectedCredits = credits;
           
           // Update button text
           generateText.textContent = `Generate ${count} Questions (${credits} Credit${credits > 1 ? 's' : ''})`;
           
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
       generateText.textContent = 'Generating Questions...';
       
       generateBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Generating Questions...';
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