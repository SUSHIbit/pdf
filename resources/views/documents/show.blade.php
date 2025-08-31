@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $document->original_name }}</h1>
                <div class="flex items-center space-x-4 text-sm text-gray-500">
                    <span>{{ $document->getFileSizeFormatted() }}</span>
                    <span>Processed {{ $document->updated_at->diffForHumans() }}</span>
                </div>
            </div>
            
            @if($document->questionSet)
                <a href="{{ route('documents.download', $document) }}" 
                   class="inline-flex items-center px-4 py-2 bg-cyan-600 hover:bg-cyan-700 text-white font-medium rounded-md transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z"/>
                    </svg>
                    Download Q&A
                </a>
            @endif
        </div>
    </div>

    @if($document->status === 'processing')
        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-blue-600 animate-spin mr-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-blue-800">Processing Document</h3>
                    <p class="text-sm text-blue-700">Your document is being processed. This may take a few minutes.</p>
                </div>
            </div>
        </div>
    @elseif($document->status === 'failed')
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-800">Processing Failed</h3>
                    <p class="text-sm text-red-700">There was an error processing your document. Please try uploading again.</p>
                </div>
            </div>
        </div>
    @endif

    @if($document->questionSet)
        <div class="space-y-6">
            @foreach($document->questionSet->questions_answers as $index => $qa)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            Question {{ $index + 1 }}
                        </h3>
                        <p class="text-gray-800">{{ $qa['question'] }}</p>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Answer</h4>
                        <p class="text-gray-700 leading-relaxed">{{ $qa['answer'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection