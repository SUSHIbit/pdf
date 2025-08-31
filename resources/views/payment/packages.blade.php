@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Credit Package</h1>
        <p class="text-lg text-gray-600">Select the perfect amount of credits for your document processing needs.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($packages as $package)
            <div class="bg-white rounded-lg shadow-sm border-2 {{ $package['popular'] ? 'border-cyan-500' : 'border-gray-200' }} relative overflow-hidden">
                @if($package['popular'])
                    <div class="absolute top-0 left-0 right-0 bg-cyan-500 text-white text-center py-2 text-sm font-medium">
                        Most Popular
                    </div>
                @endif
                
                <div class="p-6 {{ $package['popular'] ? 'pt-12' : '' }}">
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">
                            {{ $package['credits'] }} Credits
                        </h3>
                        <div class="text-4xl font-bold text-gray-900 mb-1">
                            ${{ number_format($package['price'], 2) }}
                        </div>
                        <p class="text-sm text-gray-500">
                            ${{ number_format($package['price'] / $package['credits'], 2) }} per credit
                        </p>
                    </div>

                    <div class="space-y-3 mb-8">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Process {{ $package['credits'] }} documents</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Up to 10 Q&A pairs each</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Downloadable results</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Credits never expire</span>
                        </div>
                    </div>

                    <form action="{{ route('payment.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="credits" value="{{ $package['credits'] }}">
                        <button type="submit" 
                                class="w-full py-3 px-4 {{ $package['popular'] ? 'bg-cyan-600 hover:bg-cyan-700' : 'bg-gray-600 hover:bg-gray-700' }} text-white font-medium rounded-md transition-colors">
                            Purchase Credits
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-12 text-center">
        <p class="text-sm text-gray-500">
            Secure payment powered by Stripe. Your credits will be added immediately after purchase.
        </p>
    </div>
</div>
@endsection