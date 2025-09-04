@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-primary-50 via-primary-100 to-primary-200 dark:from-primary-950 dark:via-primary-900 dark:to-primary-800 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-3xl sm:text-4xl font-bold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                Choose Your Credit Package
            </h1>
            <p class="text-lg text-text-secondary dark:text-text-dark-secondary transition-colors max-w-2xl mx-auto">
                Select the perfect amount of credits for your document processing needs.
            </p>
            <div class="mt-6 flex items-center justify-center">
                <div class="flex items-center space-x-2 text-sm text-text-tertiary dark:text-text-dark-tertiary bg-surface dark:bg-surface-dark px-4 py-2 rounded-full border border-border dark:border-border-dark">
                    <svg class="w-4 h-4 text-success-500 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                    </svg>
                    <span class="font-medium">Secure payment via ToyibPay</span>
                </div>
            </div>
        </div>

        <!-- Trial Pack Section -->
        @if($trialPack)
        <div class="mb-16">
            <!-- Trial Pack Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-primary-100 dark:bg-primary-800 rounded-full mb-4 transition-colors">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                    Trial Pack (First-Time Only)
                </h2>
                <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">
                    Perfect for trying out our service at an incredible value
                </p>
            </div>
            
            <!-- Trial Pack Card -->
            <div class="max-w-sm mx-auto">
                <div class="relative bg-surface dark:bg-surface-dark rounded-2xl shadow-xl border-2 border-primary-200 dark:border-primary-700 overflow-hidden hover:shadow-2xl transition-all duration-300 hover:scale-105 transform">
                    <!-- Special Badge -->
                    <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800 text-white text-center py-2 text-sm font-bold">
                        🚀 FIRST-TIME ONLY
                    </div>
                    
                    <div class="p-8 pt-16">
                        <!-- Pricing Section -->
                        <div class="text-center mb-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 dark:bg-primary-800 rounded-full mb-4 transition-colors">
                                <span class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $trialPack['credits'] }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                                {{ $trialPack['credits'] }} Credits
                            </h3>
                            <div class="text-4xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                                RM{{ number_format($trialPack['price'], 2) }}
                            </div>
                            <p class="text-text-secondary dark:text-text-dark-secondary text-sm transition-colors">
                                RM{{ number_format($trialPack['per_credit_price'], 2) }} per credit
                            </p>
                            <div class="mt-3 inline-flex items-center px-3 py-1 bg-success-100 dark:bg-success-900/30 border border-success-200 dark:border-success-800 rounded-full">
                                <svg class="w-3 h-3 text-success-600 dark:text-success-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                </svg>
                                <span class="text-xs font-medium text-success-800 dark:text-success-300">Amazing Value</span>
                            </div>
                        </div>

                        <!-- Features List -->
                        <div class="space-y-3 mb-8">
                            <div class="flex items-center text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                                <div class="flex-shrink-0 w-5 h-5 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-3 h-3 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                </div>
                                <span>Process {{ $trialPack['credits'] }} documents</span>
                            </div>
                            <div class="flex items-center text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                                <div class="flex-shrink-0 w-5 h-5 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-3 h-3 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                </div>
                                <span>Up to {{ $trialPack['credits'] * 30 }} Q&A pairs total</span>
                            </div>
                            <div class="flex items-center text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                                <div class="flex-shrink-0 w-5 h-5 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-3 h-3 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                    </svg>
                                </div>
                                <span>Only purchasable once per student account</span>
                            </div>
                        </div>

                        <!-- Purchase Button -->
                        <form action="{{ route('payment.checkout') }}" method="POST">
                            @csrf
                            <input type="hidden" name="credits" value="{{ $trialPack['credits'] }}">
                            <input type="hidden" name="package_type" value="trial">
                            <button type="submit" 
                                    class="w-full py-4 px-6 bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800 hover:from-primary-700 hover:to-primary-800 dark:hover:from-primary-600 dark:hover:to-primary-700 text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                <div class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                    </svg>
                                    Get Trial Pack
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Standard Packages Section -->
        <div>
            <!-- Standard Pack Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-12 h-12 bg-secondary-100 dark:bg-secondary-800 rounded-full mb-4 transition-colors">
                    <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"/>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                    Standard Packs (Repeatable)
                </h2>
                <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">
                    Perfect for regular users and power learners
                </p>
            </div>

            <!-- Standard Packages Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                @foreach($standardPackages as $index => $package)
                    <div class="relative bg-surface dark:bg-surface-dark rounded-2xl shadow-lg border-2 {{ $package['popular'] ?? false ? 'border-secondary-300 dark:border-secondary-600 ring-2 ring-secondary-200 dark:ring-secondary-700' : ($package['best_value'] ?? false ? 'border-success-300 dark:border-success-600 ring-2 ring-success-200 dark:ring-success-700' : 'border-border dark:border-border-dark') }} overflow-hidden hover:shadow-xl transition-all duration-300 hover:scale-105 transform">
                        
                        <!-- Popular/Best Value Badge -->
                        @if($package['popular'] ?? false)
                            <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-secondary-600 to-secondary-700 dark:from-secondary-700 dark:to-secondary-800 text-white text-center py-2 text-sm font-bold">
                                🔥 MOST POPULAR
                            </div>
                        @elseif($package['best_value'] ?? false)
                            <div class="absolute top-0 left-0 right-0 bg-gradient-to-r from-success-600 to-success-700 dark:from-success-700 dark:to-success-800 text-white text-center py-2 text-sm font-bold">
                                💎 BEST VALUE
                            </div>
                        @endif
                        
                        <div class="p-6 {{ ($package['popular'] ?? false) || ($package['best_value'] ?? false) ? 'pt-12' : '' }}">
                            <!-- Pricing Section -->
                            <div class="text-center mb-6">
                                <div class="inline-flex items-center justify-center w-16 h-16 {{ $package['popular'] ?? false ? 'bg-secondary-100 dark:bg-secondary-800' : ($package['best_value'] ?? false ? 'bg-success-100 dark:bg-success-800' : 'bg-primary-100 dark:bg-primary-800') }} rounded-full mb-4 transition-colors">
                                    <span class="text-2xl font-bold {{ $package['popular'] ?? false ? 'text-secondary-600 dark:text-secondary-400' : ($package['best_value'] ?? false ? 'text-success-600 dark:text-success-400' : 'text-primary-600 dark:text-primary-400') }}">{{ $package['credits'] }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                                    {{ $package['credits'] }} Credits
                                </h3>
                                <div class="text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-1 transition-colors">
                                    RM{{ number_format($package['price'], 2) }}
                                </div>
                                <p class="text-text-secondary dark:text-text-dark-secondary text-sm transition-colors">
                                    RM{{ number_format($package['per_credit_price'], 2) }} per credit
                                </p>
                                @if($package['per_credit_price'] <= 0.60)
                                    <div class="mt-3 inline-flex items-center px-3 py-1 bg-success-100 dark:bg-success-900/30 border border-success-200 dark:border-success-800 rounded-full">
                                        <svg class="w-3 h-3 text-success-600 dark:text-success-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-success-800 dark:text-success-300">Great Savings</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Features List -->
                            <div class="space-y-3 mb-8">
                                @php
                                    $features = [
                                        "Process {$package['credits']} documents",
                                        "Up to " . ($package['credits'] * 30) . " Q&A pairs total",
                                        "Downloadable results",
                                        "Credits never expire",
                                        "Flashcards & MCQ format",
                                        "Can be purchased multiple times"
                                    ];
                                @endphp
                                
                                @foreach($features as $feature)
                                    <div class="flex items-center text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">
                                        <div class="flex-shrink-0 w-5 h-5 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center mr-3">
                                            <svg class="w-3 h-3 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                                            </svg>
                                        </div>
                                        <span>{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Purchase Button -->
                            <form action="{{ route('payment.checkout') }}" method="POST">
                                @csrf
                                <input type="hidden" name="credits" value="{{ $package['credits'] }}">
                                <input type="hidden" name="package_type" value="standard">
                                <button type="submit" 
                                        class="w-full py-4 px-6 {{ $package['popular'] ?? false ? 'bg-gradient-to-r from-secondary-600 to-secondary-700 dark:from-secondary-700 dark:to-secondary-800 hover:from-secondary-700 hover:to-secondary-800 dark:hover:from-secondary-600 dark:hover:to-secondary-700' : ($package['best_value'] ?? false ? 'bg-gradient-to-r from-success-600 to-success-700 dark:from-success-700 dark:to-success-800 hover:from-success-700 hover:to-success-800 dark:hover:from-success-600 dark:hover:to-success-700' : 'bg-gradient-to-r from-primary-600 to-primary-700 dark:from-primary-700 dark:to-primary-800 hover:from-primary-700 hover:to-primary-800 dark:hover:from-primary-600 dark:hover:to-primary-700') }} text-white font-bold rounded-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <div class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
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
        </div>

        <!-- Payment Information Section -->
        <div class="mt-16 text-center space-y-6">
            <!-- Security Features -->
            <div class="bg-surface dark:bg-surface-dark rounded-2xl p-6 border border-border dark:border-border-dark shadow-sm max-w-4xl mx-auto">
                <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-4 transition-colors">
                    Secure & Trusted Payment
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-secondary-100 dark:bg-secondary-800 rounded-full flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-secondary-600 dark:text-secondary-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-text-primary dark:text-text-dark-primary transition-colors">Secure SSL Encryption</span>
                        <span class="text-text-secondary dark:text-text-dark-secondary transition-colors">Bank-level security</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-success-100 dark:bg-success-800 rounded-full flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-text-primary dark:text-text-dark-primary transition-colors">Instant Credit Delivery</span>
                        <span class="text-text-secondary dark:text-text-dark-secondary transition-colors">Credits added immediately</span>
                    </div>
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-800 rounded-full flex items-center justify-center mb-3 transition-colors">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                            </svg>
                        </div>
                        <span class="font-medium text-text-primary dark:text-text-dark-primary transition-colors">Malaysian Banking & E-Wallets</span>
                        <span class="text-text-secondary dark:text-text-dark-secondary transition-colors">All major payment methods</span>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <p class="text-sm text-text-secondary dark:text-text-dark-secondary max-w-3xl mx-auto transition-colors">
                Payment processed securely by <strong>ToyibPay</strong>. Your banking information is never stored on our servers. 
                Credits are added to your account immediately after successful payment. All prices shown are in Malaysian Ringgit (RM).
            </p>

            <!-- Money Back Guarantee -->
            <div class="inline-flex items-center px-6 py-3 bg-success-50 dark:bg-success-900/20 border border-success-200 dark:border-success-800 rounded-full transition-colors">
                <svg class="w-5 h-5 text-success-600 dark:text-success-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span class="text-sm font-medium text-success-800 dark:text-success-300">30-day money-back guarantee</span>
            </div>
        </div>
    </div>
</div>
@endsection