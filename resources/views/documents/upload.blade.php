@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Upload Document</h1>
        <p class="text-gray-600">Upload a PDF, DOCX, PPTX, or TXT file to extract text and generate questions.</p>
        @if(isset($hasPendingUpload) && $hasPendingUpload)
            <div class="mt-4 p-4 bg-cyan-50 border border-cyan-200 rounded-md">
                <p class="text-cyan-800 text-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                    Welcome! Your previously selected file has been restored. You can now upload it or select a different one.
                </p>
            </div>
        @elseif(session('from_landing'))
            <div class="mt-4 p-4 bg-cyan-50 border border-cyan-200 rounded-md">
                <p class="text-cyan-800 text-sm">
                    <svg class="w-4 h-4 inline mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                    Welcome! You can now upload your document to get started.
                </p>
            </div>
        @endif
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
        <h3 class="text-sm font-medium text-gray-900 mb-2">What happens next?</h3>
        <div class="text-sm text-gray-600">
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
    if (sessionStorage.getItem('pendingUpload')) {
        const fileData = JSON.parse(sessionStorage.getItem('pendingUploadFile') || '{}');
        if (fileData && fileData.name) {
            // Convert base64 back to file
            fetch(fileData.data)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], fileData.name, { type: fileData.type });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    fileInput.files = dataTransfer.files;
                    
                    selectedFile.textContent = 'Restored: ' + file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    selectedFile.classList.remove('hidden');
                    uploadText.textContent = 'Change file';
                })
                .catch(function(error) {
                    console.log('Error loading pending file:', error);
                });
            
            // Clear session storage
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
                selectedFile.classList.add('text-red-600');
                selectedFile.classList.remove('text-green-600');
                submitBtn.disabled = true;
            } else {
                selectedFile.classList.add('text-green-600');
                selectedFile.classList.remove('text-red-600');
                submitBtn.disabled = false;
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
        
        submitBtn.disabled = true;
        submitText.textContent = 'Extracting Text...';
        
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Extracting Text...';
    });
});
</script>
@endsection