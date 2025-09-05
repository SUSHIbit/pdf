@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center space-x-2 mb-2">
                    <a href="{{ route('admin.users.index') }}" class="text-text-secondary dark:text-text-dark-secondary hover:text-text-primary dark:hover:text-text-dark-primary transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-text-dark-primary transition-colors">
                        User Details
                    </h1>
                </div>
                <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">
                    Manage {{ $user->name }}'s account and credits.
                </p>
            </div>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button x-data="" 
                        x-on:click.prevent="$dispatch('open-modal', 'add-credits')"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Credits
                </button>
                <button x-data="" 
                        x-on:click.prevent="$dispatch('open-modal', 'update-credits')"
                        class="inline-flex items-center px-4 py-2 bg-secondary-100 dark:bg-secondary-800 hover:bg-secondary-200 dark:hover:bg-secondary-700 text-text-primary dark:text-text-dark-primary font-medium rounded-xl transition-all duration-200 transform hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Credits
                </button>
            </div>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                <div class="h-20 w-20 rounded-full bg-primary-600 dark:bg-primary-500 flex items-center justify-center">
                    <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                </div>
            </div>
            
            <!-- User Info -->
            <div class="flex-1">
                <h2 class="text-xl font-bold text-text-primary dark:text-text-dark-primary">{{ $user->name }}</h2>
                <p class="text-text-secondary dark:text-text-dark-secondary">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="text-text-secondary dark:text-text-dark-secondary">{{ $user->formatted_phone }}</p>
                @endif
                <div class="mt-2 flex items-center space-x-4 text-sm text-text-tertiary dark:text-text-dark-tertiary">
                    <span>ID: {{ $user->id }}</span>
                    <span>•</span>
                    <span>Joined {{ $user->created_at->format('M j, Y') }}</span>
                    <span>•</span>
                    <span>{{ $user->created_at->diffForHumans() }}</span>
                </div>
            </div>

            <!-- Current Credits -->
            <div class="flex-shrink-0">
                <div class="text-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-xl">
                    <div class="text-2xl font-bold text-primary-600 dark:text-primary-400">{{ $user->credits }}</div>
                    <div class="text-sm text-text-secondary dark:text-text-dark-secondary">Current Credits</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">Total Documents</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ $stats['total_documents'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-success-100 dark:bg-success-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">Completed</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ $stats['completed_documents'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-800 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">Credits Purchased</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ $stats['total_credits_purchased'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-warning-100 dark:bg-warning-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">Credits Used</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ $stats['total_credits_used'] }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Transactions -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-4">Recent Credit Transactions</h3>
            <div class="space-y-3">
                @forelse($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-3 bg-background dark:bg-background-dark rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                @if($transaction->type === 'purchase')
                                    <div class="w-8 h-8 bg-success-100 dark:bg-success-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 bg-error-100 dark:bg-error-900/30 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-error-600 dark:text-error-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5 10a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1z"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary truncate">
                                    {{ $transaction->description }}
                                </p>
                                <p class="text-xs text-text-secondary dark:text-text-dark-secondary">
                                    {{ $transaction->created_at->format('M j, Y g:i A') }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-medium {{ $transaction->type === 'purchase' ? 'text-success-600 dark:text-success-400' : 'text-error-600 dark:text-error-400' }}">
                                {{ $transaction->type === 'purchase' ? '+' : '' }}{{ $transaction->credits }}
                            </p>
                            @if($transaction->amount)
                                <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">
                                    RM {{ number_format($transaction->amount, 2) }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-text-secondary dark:text-text-dark-secondary text-center py-4">No transactions found</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-4">Recent Documents</h3>
            <div class="space-y-3">
                @forelse($recentDocuments as $document)
                    <div class="flex items-center space-x-3 p-3 bg-background dark:bg-background-dark rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary truncate">
                                {{ $document->getDisplayName() }}
                            </p>
                            <div class="flex items-center space-x-2 text-xs text-text-secondary dark:text-text-dark-secondary">
                                <span>{{ $document->getFormatDisplay() }}</span>
                                <span>•</span>
                                <span>{{ $document->getItemsText() }}</span>
                                <span>•</span>
                                <span>{{ $document->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                @if($document->status === 'completed') bg-success-100 dark:bg-success-900/30 text-success-800 dark:text-success-300
                                @elseif($document->status === 'processing') bg-warning-100 dark:bg-warning-900/30 text-warning-800 dark:text-warning-300
                                @elseif($document->status === 'failed') bg-error-100 dark:bg-error-900/30 text-error-800 dark:text-error-300
                                @else bg-primary-100 dark:bg-primary-800 text-primary-800 dark:text-primary-300
                                @endif">
                                {{ ucfirst($document->status) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <p class="text-text-secondary dark:text-text-dark-secondary text-center py-4">No documents found</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Add Credits Modal -->
<div x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail == 'add-credits' ? show = true : null"
     x-on:close-modal.window="$event.detail == 'add-credits' ? show = false : null"
     x-show="show"
     class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 p-4 transition-colors"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl max-w-md w-full p-6 border border-border dark:border-border-dark transition-colors animate-scale-in"
             x-on:click.away="show = false">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-primary-100 dark:bg-primary-800 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary transition-colors">Add Credits</h3>
                    <p class="text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">Add credits to {{ $user->name }}'s account</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.add-credits', $user) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="credits" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">Credits to Add</label>
                        <input type="number" 
                               name="credits" 
                               id="credits"
                               min="1" 
                               max="1000"
                               required
                               class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                               placeholder="Enter number of credits">
                    </div>

                    <div>
                        <label for="reason" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">Reason</label>
                        <input type="text" 
                               name="reason" 
                               id="reason"
                               required
                               class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                               placeholder="Reason for adding credits">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            x-on:click="show = false"
                            class="px-4 py-2 text-text-secondary dark:text-text-dark-secondary border border-border dark:border-border-dark rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors">
                        Add Credits
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Update Credits Modal -->
<div x-data="{ show: false }" 
     x-on:open-modal.window="$event.detail == 'update-credits' ? show = true : null"
     x-on:close-modal.window="$event.detail == 'update-credits' ? show = false : null"
     x-show="show"
     class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 p-4 transition-colors"
     style="display: none;">
    <div class="flex items-center justify-center min-h-screen">
        <div class="bg-surface dark:bg-surface-dark rounded-2xl shadow-xl max-w-md w-full p-6 border border-border dark:border-border-dark transition-colors animate-scale-in"
             x-on:click.away="show = false">
            <div class="flex items-center mb-4">
                <div class="flex-shrink-0 w-10 h-10 bg-warning-100 dark:bg-warning-900/30 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary transition-colors">Update Credits</h3>
                    <p class="text-sm text-text-secondary dark:text-text-dark-secondary transition-colors">Set new credit amount for {{ $user->name }}</p>
                </div>
            </div>

            <div class="mb-4 p-3 bg-warning-50 dark:bg-warning-900/20 border border-warning-200 dark:border-warning-800 rounded-lg">
                <p class="text-sm text-warning-800 dark:text-warning-300">
                    Current credits: <strong>{{ $user->credits }}</strong>
                </p>
            </div>

            <form method="POST" action="{{ route('admin.users.update-credits', $user) }}">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="new_credits" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">New Credit Amount</label>
                        <input type="number" 
                               name="credits" 
                               id="new_credits"
                               min="0" 
                               max="1000"
                               value="{{ $user->credits }}"
                               required
                               class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                               placeholder="Enter new credit amount">
                    </div>

                    <div>
                        <label for="update_reason" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">Reason</label>
                        <input type="text" 
                               name="reason" 
                               id="update_reason"
                               required
                               class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200"
                               placeholder="Reason for updating credits">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                            x-on:click="show = false"
                            class="px-4 py-2 text-text-secondary dark:text-text-dark-secondary border border-border dark:border-border-dark rounded-xl hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-warning-600 hover:bg-warning-700 text-white rounded-xl transition-colors">
                        Update Credits
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection