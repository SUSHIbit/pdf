@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Upload Document</h1>
        <p class="text-gray-600">Upload a PDF, DOCX, or TXT file to generate multiple choice questions.</p>
    </div>

    @if(auth()->user()->credits < 1)
        <div class="bg-amber-50 border border-amber-200 rounded-md p-4 mb-6">
            <div class="flex">
                <svg class="w-5 h-5 text-amber-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-amber-800">Insufficient Credits</h3>
                    <p class="text-sm text-amber-700 mt-1">You need at least 1 credit to process a document.</p>
                    <a href="{{ route('payment.packages') }}" 
                       class="text-sm font-medium text-amber-800 underline hover:text-amber-900 mt-2 inline-block">
                        Buy Credits →
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <form id="uploadForm" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
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
                                <input id="document" name="document" type="file" accept=".pdf,.docx,.doc,.txt" class="sr-only" required>
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PDF, DOCX, or TXT up to 10MB</p>
                        <div id="selectedFile" class="hidden mt-2 text-sm text-green-600 font-medium"></div>
                    </div>
                </div>
                
                @error('document')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" id="submitBtn"
                        class="inline-flex items-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 disabled:bg-gray-400 text-white font-medium rounded-md transition-colors"
                        @if(auth()->user()->credits < 1) disabled @endif>
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z"/>
                    </svg>
                    <span id="submitText">Process Document (1 Credit)</span>
                </button>
            </div>
        </form>
    </div>

    <!-- File Requirements -->
    <div class="mt-6 bg-gray-50 rounded-md p-4">
        <h3 class="text-sm font-medium text-gray-900 mb-2">File Requirements</h3>
        <ul class="text-sm text-gray-600 space-y-1">
            <li>• Supported formats: PDF, DOCX, DOC, TXT</li>
            <li>• Maximum file size: 10MB</li>
            <li>• Processing cost: 1 credit per document</li>
            <li>• Generated: Up to 10 multiple choice questions</li>
        </ul>
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

    // Click to upload
    dropZone.addEventListener('click', function() {
        fileInput.click();
    });

    // File selection handler
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            selectedFile.textContent = `Selected: ${file.name} (${(file.size / 1024 / 1024).toFixed(2)} MB)`;
            selectedFile.classList.remove('hidden');
            uploadText.textContent = 'Change file';
            
            // Check file size
            if (file.size > 10 * 1024 * 1024) {
                selectedFile.textContent = `File too large: ${file.name} - Maximum 10MB allowed`;
                selectedFile.classList.add('text-red-600');
                selectedFile.classList.remove('text-green-600');
            } else {
                selectedFile.classList.add('text-green-600');
                selectedFile.classList.remove('text-red-600');
            }
        }
    });

    // Drag and drop handlers
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
            fileInput.files = files;
            fileInput.dispatchEvent(new Event('change'));
        }
    });

    // Form submission handler
    form.addEventListener('submit', function(e) {
        if (!fileInput.files[0]) {
            e.preventDefault();
            alert('Please select a file to upload.');
            return;
        }
        
        submitBtn.disabled = true;
        submitText.textContent = 'Processing...';
        
        // Show processing animation
        submitBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing Document...
        `;
    });
});
</script>
@endsection