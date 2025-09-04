@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 transition-colors duration-300">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">Upload Document</h1>
            <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">Upload a PDF, DOCX, PPTX, or TXT file to extract text and generate questions.</p>
            @if(session('from_landing'))
                <div class="mt-4 p-4 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-xl">
                    <p class="text-primary-800 dark:text-primary-300 text-sm">
                        <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                        </svg>
                        Welcome! Your previously selected file should be restored automatically.
                    </p>
                </div>
            @endif
        </div>

        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-colors">
            <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <!-- File Upload Section -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                        Select Document
                    </label>
                    
                    <div id="dropZone" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed border-border dark:border-border-dark rounded-xl hover:border-primary-400 dark:hover:border-primary-500 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-all cursor-pointer group">
                        <div class="space-y-1 text-center">
                            <div class="mx-auto w-12 h-12 bg-primary-100 dark:bg-primary-800 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="flex text-sm text-text-secondary dark:text-text-dark-secondary">
                                <label for="document" class="relative cursor-pointer bg-transparent rounded-md font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 focus-within:outline-none">
                                    <span id="uploadText">Upload a file</span>
                                    <input id="document" name="document" type="file" accept=".pdf,.docx,.doc,.txt,.pptx" class="sr-only" required>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">PDF, DOCX, PPTX, or TXT up to 10MB</p>
                            <div id="selectedFile" class="hidden mt-2 text-sm text-success-600 dark:text-success-400 font-medium"></div>
                        </div>
                    </div>
                    
                    @error('document')
                        <p class="mt-2 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" id="submitBtn"
                            class="inline-flex items-center px-6 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 disabled:bg-primary-300 dark:disabled:bg-primary-800 text-white font-medium rounded-xl shadow-sm transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"/>
                        </svg>
                        <span id="submitText">Upload & Extract Text</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Section -->
        <div class="mt-6 bg-surface/60 dark:bg-surface-dark/60 backdrop-blur-sm rounded-xl p-4 border border-border dark:border-border-dark">
            <h3 class="text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">What happens next?</h3>
            <div class="text-sm text-text-secondary dark:text-text-dark-secondary">
                <ol class="list-decimal list-inside space-y-1">
                    <li>Your document will be uploaded and text will be extracted</li>
                    <li>You'll choose between Q&A questions or flashcards</li>
                    <li>Select how many items to generate (10, 20, or 30)</li>
                    <li>AI will create your study materials with explanations</li>
                    <li>Take the interactive quiz or study with flashcards</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('document');
    const uploadText = document.getElementById('uploadText');
    const selectedFile = document.getElementById('selectedFile');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const form = document.getElementById('uploadForm');

    // Check for pending upload from session storage
    if (sessionStorage.getItem('pendingUpload') === 'true') {
        const fileData = JSON.parse(sessionStorage.getItem('pendingUploadFile') || '{}');
        if (fileData && fileData.name && fileData.data) {
            console.log('Restoring file from sessionStorage:', fileData.name);
            
            fetch(fileData.data)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], fileData.name, { type: fileData.type });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                    
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    selectedFile.textContent = '✅ Restored: ' + file.name + ' (' + fileSize + ' MB)';
                    selectedFile.classList.remove('hidden');
                    selectedFile.classList.add('text-success-600', 'dark:text-success-400');
                    uploadText.textContent = 'Change file';
                })
                .catch(function(error) {
                    console.error('Error restoring file:', error);
                    selectedFile.textContent = '⚠️ Error restoring file. Please select again.';
                    selectedFile.classList.remove('hidden');
                    selectedFile.classList.add('text-warning-600', 'dark:text-warning-400');
                });
            
            sessionStorage.removeItem('pendingUpload');
            sessionStorage.removeItem('pendingUploadFile');
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
                selectedFile.classList.add('text-error-600', 'dark:text-error-400');
                selectedFile.classList.remove('text-success-600', 'dark:text-success-400');
                submitBtn.disabled = true;
            } else {
                selectedFile.classList.add('text-success-600', 'dark:text-success-400');
                selectedFile.classList.remove('text-error-600', 'dark:text-error-400');
                submitBtn.disabled = false;
            }
        }
    });

    // Drag and drop
    dropZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        dropZone.classList.add('border-primary-400', 'dark:border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
    });

    dropZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-primary-400', 'dark:border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
    });

    dropZone.addEventListener('drop', function(e) {
        e.preventDefault();
        dropZone.classList.remove('border-primary-400', 'dark:border-primary-500', 'bg-primary-50', 'dark:bg-primary-900/20');
        
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
        
        submitBtn.disabled = true;
        submitText.textContent = 'Extracting Text...';
        
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Extracting Text...';
    });
});
</script>
@endsection