@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Navigation -->
    <div class="mb-6">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-4">
                <li>
                    <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-500">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L9 5.414V17a1 1 0 102 0V5.414l5.293 5.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <a href="{{ route('dashboard') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">Dashboard</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                        </svg>
                        <span class="ml-4 text-sm font-medium text-gray-500">{{ $folder->name }}</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Folder Header -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $folder->name }}</h1>
                        @if($folder->description)
                            <p class="text-gray-600 mt-1">{{ $folder->description }}</p>
                        @endif
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mt-2">
                            <span>{{ $documents->total() }} documents</span>
                            <span>{{ $folder->getCompletedDocumentCount() }} completed</span>
                            <span>{{ $folder->getTotalQuestionCount() }} total questions</span>
                            <span>Created {{ $folder->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <a href="{{ route('folders.edit', $folder) }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 transition-colors">
                        Edit Folder
                    </a>
                    <button class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition-colors delete-folder-btn"
                            data-id="{{ $folder->id }}" 
                            data-name="{{ $folder->name }}">
                        Delete Folder
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents in Folder -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Documents in this folder</h2>
        </div>

        @if($documents->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($documents as $document)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    @if($document->status === 'completed')
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                        </div>
                                    @elseif($document->status === 'processing')
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    @elseif($document->status === 'text_extracted')
                                        <div class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-4 h-4 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 0v12h8V4H6z"/>
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        @if($document->title)
                                            {{ $document->title }}
                                            <span class="text-xs text-gray-500">({{ $document->original_name }})</span>
                                        @else
                                            {{ $document->original_name }}
                                        @endif
                                    </p>
                                    <div class="flex items-center space-x-4 text-xs text-gray-500 mt-1">
                                        <span>{{ strtoupper($document->file_type) }}</span>
                                        <span>{{ $document->getFileSizeFormatted() }}</span>
                                        <span>{{ $document->question_count }} questions</span>
                                        <span>{{ $document->created_at->diffForHumans() }}</span>
                                        <span class="capitalize">
                                            @if($document->status === 'text_extracted')
                                                Ready for Processing
                                            @else
                                                {{ str_replace('_', ' ', $document->status) }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center space-x-2">
                            @if($document->status === 'completed' && $document->questionSet)
                                <a href="{{ route('documents.show', $document) }}" 
                                   class="text-cyan-600 hover:text-cyan-700 text-sm font-medium">
                                    Take Quiz
                                </a>
                                <a href="{{ route('documents.download', $document) }}" 
                                   class="text-gray-600 hover:text-gray-700" title="Download">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                                    </svg>
                                </a>
                            @elseif($document->status === 'text_extracted')
                                <a href="{{ route('documents.preview', $document) }}" 
                                   class="text-amber-600 hover:text-amber-700 text-sm font-medium">
                                    Choose Questions
                                </a>
                            @elseif($document->status === 'processing')
                                <span class="text-sm text-blue-600">Processing...</span>
                            @elseif($document->status === 'failed')
                                <span class="text-sm text-red-600">Failed</span>
                            @endif

                            <!-- Remove from Folder -->
                            <button class="text-orange-600 hover:text-orange-700 remove-from-folder-btn"
                                    data-document-id="{{ $document->id }}"
                                    title="Remove from folder">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                                </svg>
                            </button>

                            <!-- Edit/Delete -->
                            <a href="{{ route('documents.edit', $document) }}" 
                               class="text-gray-600 hover:text-gray-700" title="Edit Title">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                </svg>
                            </a>
                            
                            <button class="text-red-600 hover:text-red-700 delete-btn" 
                                    data-id="{{ $document->id }}" 
                                    data-name="{{ $document->title ?? $document->original_name }}"
                                    title="Delete Document">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9zM4 5a2 2 0 012-2h8a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($documents->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $documents->links() }}
                </div>
            @endif
        @else
            <div class="px-6 py-12 text-center">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No documents in this folder</h3>
                <p class="text-gray-600 mb-6">Add documents to this folder from the main dashboard.</p>
                <a href="{{ route('dashboard') }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-md transition-colors">
                    Go to Dashboard
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Delete Document Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">Delete Document</h3>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete "<span id="deleteDocName"></span>"?
            </p>
            <div class="flex justify-end space-x-3">
                <button id="cancelDelete" class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Folder Modal -->
<div id="deleteFolderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg p-6 max-w-sm w-full">
            <h3 class="text-lg font-semibold mb-4">Delete Folder</h3>
            <p class="text-gray-600 mb-6">
                Are you sure you want to delete "<span id="deleteFolderName"></span>"? Documents will be moved back to Recent Documents.
            </p>
            <div class="flex justify-end space-x-3">
                <button id="cancelDeleteFolder" class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                    Cancel
                </button>
                <form id="deleteFolderForm" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Delete Folder
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete Document Modal
    const deleteModal = document.getElementById('deleteModal');
    const deleteForm = document.getElementById('deleteForm');
    const deleteDocName = document.getElementById('deleteDocName');
    const cancelDelete = document.getElementById('cancelDelete');

    document.querySelectorAll('.delete-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const docId = this.getAttribute('data-id');
            const docName = this.getAttribute('data-name');
            
            deleteDocName.textContent = docName;
            deleteForm.action = '/documents/' + docId;
            deleteModal.classList.remove('hidden');
        });
    });

    cancelDelete.addEventListener('click', function() {
        deleteModal.classList.add('hidden');
    });

    // Delete Folder Modal
    const deleteFolderModal = document.getElementById('deleteFolderModal');
    const deleteFolderForm = document.getElementById('deleteFolderForm');
    const deleteFolderName = document.getElementById('deleteFolderName');
    const cancelDeleteFolder = document.getElementById('cancelDeleteFolder');

    document.querySelectorAll('.delete-folder-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const folderId = this.getAttribute('data-id');
            const folderName = this.getAttribute('data-name');
            
            deleteFolderName.textContent = folderName;
            deleteFolderForm.action = '/folders/' + folderId;
            deleteFolderModal.classList.remove('hidden');
        });
    });

    cancelDeleteFolder.addEventListener('click', function() {
        deleteFolderModal.classList.add('hidden');
    });

    // Remove from folder functionality
    document.querySelectorAll('.remove-from-folder-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const documentId = this.getAttribute('data-document-id');
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/move-document';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.innerHTML = '<input type="hidden" name="_token" value="' + csrfToken + '">' +
                            '<input type="hidden" name="document_id" value="' + documentId + '">' +
                            '<input type="hidden" name="folder_id" value="">';
            
            document.body.appendChild(form);
            form.submit();
        });
    });

    // Close modals on click outside
    [deleteModal, deleteFolderModal].forEach(function(modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    });

    // Close modals on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            deleteModal.classList.add('hidden');
            deleteFolderModal.classList.add('hidden');
        }
    });
});
</script>
@endsection