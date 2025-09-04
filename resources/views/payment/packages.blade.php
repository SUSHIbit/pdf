@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 transition-colors duration-300">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                Choose Your Credit Package
            </h1>
            <p class="text-lg text-text-secondary dark:text-text-dark-secondary transition-colors">Select the perfect amount of credits for your document processing needs.</p>
            <div class="mt-4 flex items-center justify-center">
                <div class="flex items-center space-x-2 text-sm text-text-tertiary dark:text-text-dark-tertiary">
                    <svg class="w-4 h-4 text-success-500 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    <span>Secure payment via Stripe</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($packages as $package)
                <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border-2 {{ $package['popular'] ? 'border-primary-500 dark:border-primary-600 relative' : 'border-border dark:border-border-dark' }} overflow-hidden hover:shadow-lg transition-all duration-300 hover:scale-105 transform">
                    @if($package['popular'])
                        <div class="absolute top-0 left-0 right-0 bg-primary-600 dark:bg-primary-700 text-white text-center py-2 text-sm font-medium">
                            Most Popular
                        </div>
                    @endif
                    
                    <div class="p-6 {{ $package['popular'] ? 'pt-12' : '' }}">
                        <div class="text-center mb-6">
                            <h3 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-2">
                                {{ $package['credits'] }} Credits
                            </h3>
                            <div class="text-4xl font-bold text-text-primary dark:text-text-dark-primary mb-1">
                                ${{ number_format($package['price'], 2) }}
                            </div>
                            <p class="text-sm text-text-secondary dark:text-text-dark-secondary">
                                ${{ number_format($package['price'] / $package['credits'], 2) }} per credit
                            </p>
                            @if($package['credits'] >= 15)
                                <div class="mt-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success-100 dark:bg-success-900/30 text-success-800 dark:text-success-300">
                                    Best Value
                                </div>
                            @endif
                        </div>

                        <div class="space-y-3 mb-8">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span class="text-sm text-text-secondary dark:text-text-dark-secondary">Process {{ $package['credits'] }} documents</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span class="text-sm text-text-secondary dark:text-text-dark-secondary">Up to {{ $package['credits'] * 30 }} Q&A pairs total</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span class="text-sm text-text-secondary dark:text-text-dark-secondary">Downloadable results</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span class="text-sm text-text-secondary dark:text-text-dark-secondary">Credits never expire</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-success-500 dark:text-success-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span class="text-sm text-text-secondary dark:text-text-dark-secondary">Flashcards & MCQ format</span>
                            </div>
                        </div>

                        <form action="{{ route('payment.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="credits" value="{{ $package['credits'] }}">
                            <button type="submit" 
                                    class="w-full py-3 px-4 {{ $package['popular'] ? 'bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 shadow-lg' : 'bg-secondary-600 dark:bg-secondary-700 hover:bg-secondary-700 dark:hover:bg-secondary-600' }} text-white font-medium rounded-xl transition-all transform hover:scale-105">
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
            <div class="flex items-center justify-center space-x-6 text-sm text-text-secondary dark:text-text-dark-secondary">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-secondary-600 dark:text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                    </svg>
                    Secure SSL Encryption
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    Instant Credit Delivery
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                    </svg>
                    All Major Cards Accepted
                </div>
            </div>

            <p class="text-sm text-text-secondary dark:text-text-dark-secondary max-w-2xl mx-auto">
                Payment processed securely by <strong>Stripe</strong>. Your card information is never stored on our servers. 
                Credits are added to your account immediately after successful payment.
            </p>

            @if(config('app.env') === 'local')
                <div class="mt-6 p-4 bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg max-w-2xl mx-auto">
                    <h4 class="text-sm font-medium text-warning-800 dark:text-warning-300 mb-2">Test Mode - Use These Cards:</h4>
                    <div class="text-xs text-warning-700 dark:text-warning-400 space-y-1">
                        <div><strong>Success:</strong> 4242 4242 4242 4242 (any future date, any CVC)</div>
                        <div><strong>Decline:</strong> 4000 0000 0000 0002</div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Money Back Guarantee -->
        <div class="mt-8 text-center">
            <div class="inline-flex items-center px-4 py-2 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-full">
                <svg class="w-5 h-5 text-success-600 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span class="text-sm font-medium text-success-800 dark:text-success-300">30-day money-back guarantee</span>
            </div>
        </div>
    </div>
</div>
@endsection