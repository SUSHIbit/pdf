@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Choose Your Credit Package</h1>
        <p class="text-lg text-gray-600">Select the perfect amount of credits for your document processing needs.</p>
        <div class="mt-4 flex items-center justify-center">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
                <span>Secure payment via Stripe</span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        @foreach($packages as $package)
            <div class="bg-white rounded-lg shadow-sm border-2 {{ $package['popular'] ? 'border-cyan-500 relative' : 'border-gray-200' }} overflow-hidden hover:shadow-lg transition-shadow">
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
                        @if($package['credits'] >= 15)
                            <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Best Value
                            </div>
                        @endif
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
                            <span class="text-sm text-gray-700">Up to {{ $package['credits'] * 30 }} Q&A pairs total</span>
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
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                            <span class="text-sm text-gray-700">Flashcards & MCQ format</span>
                        </div>
                    </div>

                    <form action="{{ route('payment.checkout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="credits" value="{{ $package['credits'] }}">
                        <button type="submit" 
                                class="w-full py-3 px-4 {{ $package['popular'] ? 'bg-cyan-600 hover:bg-cyan-700 shadow-lg' : 'bg-gray-600 hover:bg-gray-700' }} text-white font-medium rounded-md transition-all transform hover:scale-105">
                            <div class="flex items-center justify-center">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                </svg>
                                Purchase Credits
                            </div>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Payment Info -->
    <div class="mt-12 text-center space-y-4">
        <div class="flex items-center justify-center space-x-6 text-sm text-gray-500">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                </svg>
                Secure SSL Encryption
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                </svg>
                Instant Credit Delivery
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                </svg>
                All Major Cards Accepted
            </div>
        </div>

        <p class="text-sm text-gray-500 max-w-2xl mx-auto">
            Payment processed securely by <strong>Stripe</strong>. Your card information is never stored on our servers. 
            Credits are added to your account immediately after successful payment.
        </p>

        <!-- Test Card Info (only show in development) -->
        @if(config('app.env') === 'local')
            <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-lg max-w-2xl mx-auto">
                <h4 class="text-sm font-medium text-yellow-800 mb-2">Test Mode - Use These Cards:</h4>
                <div class="text-xs text-yellow-700 space-y-1">
                    <div><strong>Success:</strong> 4242 4242 4242 4242 (any future date, any CVC)</div>
                    <div><strong>Decline:</strong> 4000 0000 0000 0002</div>
                </div>
            </div>
        @endif
    </div>

    <!-- Money Back Guarantee -->
    <div class="mt-8 text-center">
        <div class="inline-flex items-center px-4 py-2 bg-green-50 border border-green-200 rounded-full">
            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
            </svg>
            <span class="text-sm font-medium text-green-800">30-day money-back guarantee</span>
        </div>
    </div>
</div>
@endsection