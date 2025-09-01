@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Upload Document</h1>
        <p class="text-gray-600">Upload a PDF, DOCX, PPTX, or TXT file and choose how many questions to generate.</p>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <!-- File Upload Section -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Select Document
                </label>
                
                <div id="dropZone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-cyan-400 transition-colors cursor-pointer">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-cyan-600 hover:text-cyan-500 focus-within:outline-none">
                                <span id="uploadText">Upload a file</span>
                                <input id="document" name="document" type="file" accept=".pdf,.docx,.doc,.txt,.pptx" class="sr-only" required>
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, DOCX, PPTX, or TXT up to 10MB</p>
                        <div id="selectedFile" class="hidden mt-2 text-sm text-green-600 font-medium"></div>
                    </div>
                </div>
                
                @error('document')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Question Count Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Number of Questions
                </label>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="question-option relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-cyan-400 transition-colors" data-count="10" data-credits="1">
                        <input type="radio" name="question_count" value="10" id="q10" class="sr-only" {{ old('question_count', '10') == '10' ? 'checked' : '' }}>
                        <label for="q10" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900 mb-1">10</div>
                                <div class="text-sm text-gray-600 mb-2">Questions</div>
                                <div class="text-xs text-cyan-600 font-medium">1 Credit</div>
                            </div>
                        </label>
                    </div>
                    
                    <div class="question-option relative border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-cyan-400 transition-colors" data-count="20" data-credits="2">
                        <input type="radio" name="question_count" value="20" id="q20" class="sr-only" {{ old('question_count') == '20' ? 'checked' : '' }}>
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
                        <input type="radio" name="question_count" value="30" id="q30" class="sr-only" {{ old('question_count') == '30' ? 'checked' : '' }}>
                        <label for="q30" class="cursor-pointer">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900 mb-1">30</div>
                                <div class="text-sm text-gray-600 mb-2">Questions</div>
                                <div class="text-xs text-cyan-600 font-medium">3 Credits</div>
                            </div>
                        </label>
                    </div>
                </div>
                
                @error('question_count')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Credit Check Alert -->
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
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 disabled:bg-gray-400 text-white font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"/>
                    </svg>
                    <span id="submitText">Upload & Extract Text</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Information Section -->
    <div class="mt-6 bg-gray-50 rounded-md p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-2">Pricing & Features</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
                <h4 class="font-medium text-gray-700 mb-1">Credit Costs:</h4>
                <ul class="space-y-1">
                    <li>• 10 questions = 1 credit</li>
                    <li>• 20 questions = 2 credits</li>
                    <li>• 30 questions = 3 credits</li>
                </ul>
            </div>
            <div>
                <h4 class="font-medium text-gray-700 mb-1">What You Get:</h4>
                <ul class="space-y-1">
                    <li>• Multiple choice questions</li>
                    <li>• Detailed explanations</li>
                    <li>• Interactive quiz mode</li>
                    <li>• Downloadable results</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script id="app-data" type="application/json">
{
    "userCredits": {{ auth()->check() ? auth()->user()->credits : 0 }}
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const appData = JSON.parse(document.getElementById('app-data').textContent);
    const userCredits = appData.userCredits;
    
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('document');
    const uploadText = document.getElementById('uploadText');
    const selectedFile = document.getElementById('selectedFile');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const form = document.getElementById('uploadForm');
    const creditAlert = document.getElementById('creditAlert');
    const creditMessage = document.getElementById('creditMessage');
    const questionOptions = document.querySelectorAll('.question-option');

    let selectedCredits = 1; // Default to 10 questions

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
            
            checkCredits();
        });
    });

    // Initialize first option as selected
    document.querySelector('.question-option[data-count="10"]').click();

    function checkCredits() {
        if (userCredits < selectedCredits) {
            creditAlert.classList.remove('hidden');
            creditMessage.textContent = `You need ${selectedCredits} credits but only have ${userCredits}. Please purchase more credits.`;
            submitBtn.disabled = true;
        } else {
            creditAlert.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    // File handling
    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            selectedFile.textContent = 'Selected: ' + file.name + ' (' + fileSize + ' MB)';
            selectedFile.classList.remove('hidden');
            uploadText.textContent = 'Change file';
            
            if (file.size > 10 * 1024 * 1024) {
                selectedFile.textContent = 'File too large: ' + file.name + ' - Maximum 10MB allowed';
                selectedFile.classList.add('text-red-600');
                selectedFile.classList.remove('text-green-600');
                submitBtn.disabled = true;
            } else {
                selectedFile.classList.add('text-green-600');
                selectedFile.classList.remove('text-red-600');
                checkCredits();
            }
        }
    });

    // Drag and drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-cyan-400', 'bg-cyan-50');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-cyan-400', 'bg-cyan-50');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-cyan-400', 'bg-cyan-50');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            const allowedTypes = ['.pdf', '.docx', '.doc', '.txt', '.pptx'];
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            
            if (allowedTypes.includes(fileExtension)) {
                fileInput.files = files;
                fileInput.dispatchEvent(new Event('change'));
            } else {
                alert('Please select a valid file type: PDF, DOCX, DOC, TXT, or PPTX');
            }
        }
    });

    // Form submission
    form.addEventListener('submit', function(e) {
        if (!fileInput.files[0]) {
            e.preventDefault();
            alert('Please select a file to upload.');
            return;
        }
        
        if (!document.querySelector('input[name="question_count"]:checked')) {
            e.preventDefault();
            alert('Please select the number of questions.');
            return;
        }
        
        submitBtn.disabled = true;
        submitText.textContent = 'Extracting Text...';
        
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Extracting Text...';
    });

    checkCredits();
});
</script>
@endsection