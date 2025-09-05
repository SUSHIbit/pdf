@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
            Admin Dashboard
        </h1>
        <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">
            Welcome back, {{ auth('admin')->user()->name }}! Here's an overview of your system.
        </p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-all hover:shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-primary-100 dark:bg-primary-800 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">Total Users</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ number_format($stats['total_users']) }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-success-600 dark:text-success-400 font-medium">+{{ $stats['new_users_today'] }}</span>
                    <span class="text-text-tertiary dark:text-text-dark-tertiary ml-1">today</span>
                </div>
            </div>
        </div>

        <!-- Total Documents -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-all hover:shadow-lg">
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
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">Documents Processed</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ number_format($stats['total_documents']) }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-success-600 dark:text-success-400 font-medium">+{{ $stats['documents_processed_today'] }}</span>
                    <span class="text-text-tertiary dark:text-text-dark-tertiary ml-1">today</span>
                </div>
            </div>
        </div>

        <!-- Credits Issued -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-all hover:shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-success-100 dark:bg-success-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">Credits Issued</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ number_format($stats['total_credits_issued']) }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-text-tertiary dark:text-text-dark-tertiary">{{ number_format($stats['total_credits_used']) }} used</span>
                </div>
            </div>
        </div>

        <!-- New Users This Week -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 transition-all hover:shadow-lg">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-warning-100 dark:bg-warning-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-5 w-0 flex-1">
                    <dl>
                        <dt class="text-sm font-medium text-text-secondary dark:text-text-dark-secondary truncate">New This Week</dt>
                        <dd class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">{{ number_format($stats['new_users_this_week']) }}</dd>
                    </dl>
                </div>
            </div>
            <div class="mt-4">
                <div class="flex items-center text-sm">
                    <span class="text-text-tertiary dark:text-text-dark-tertiary">{{ number_format($stats['new_users_this_month']) }} this month</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Weekly Statistics and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Weekly Statistics Table -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-4">Weekly Statistics</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-background dark:bg-background-dark rounded-lg">
                    <span class="text-sm font-medium text-text-primary dark:text-text-dark-primary">New Users Today</span>
                    <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $stats['new_users_today'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-background dark:bg-background-dark rounded-lg">
                    <span class="text-sm font-medium text-text-primary dark:text-text-dark-primary">New Users This Week</span>
                    <span class="text-sm font-bold text-primary-600 dark:text-primary-400">{{ $stats['new_users_this_week'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-background dark:bg-background-dark rounded-lg">
                    <span class="text-sm font-medium text-text-primary dark:text-text-dark-primary">Documents Today</span>
                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $stats['documents_processed_today'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-background dark:bg-background-dark rounded-lg">
                    <span class="text-sm font-medium text-text-primary dark:text-text-dark-primary">Documents This Week</span>
                    <span class="text-sm font-bold text-purple-600 dark:text-purple-400">{{ $stats['documents_processed_this_week'] }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-background dark:bg-background-dark rounded-lg">
                    <span class="text-sm font-medium text-text-primary dark:text-text-dark-primary">Credits Usage Rate</span>
                    <span class="text-sm font-bold text-success-600 dark:text-success-400">
                        {{ $stats['total_credits_issued'] > 0 ? round(($stats['total_credits_used'] / $stats['total_credits_issued']) * 100, 1) : 0 }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary mb-4">Quick Actions</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-3 bg-primary-50 dark:bg-primary-900/20 hover:bg-primary-100 dark:hover:bg-primary-900/30 rounded-lg transition-colors group">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary">Manage Users</p>
                        <p class="text-xs text-text-secondary dark:text-text-dark-secondary">View and manage user accounts</p>
                    </div>
                    <svg class="w-4 h-4 text-text-tertiary dark:text-text-dark-tertiary group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                    </svg>
                </a>

                <a href="{{ route('admin.users.index', ['credits_filter' => 'zero']) }}" class="flex items-center p-3 bg-warning-50 dark:bg-warning-900/20 hover:bg-warning-100 dark:hover:bg-warning-900/30 rounded-lg transition-colors group">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-warning-600 dark:text-warning-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary">Users with Zero Credits</p>
                        <p class="text-xs text-text-secondary dark:text-text-dark-secondary">Check users who need credits</p>
                    </div>
                    <svg class="w-4 h-4 text-text-tertiary dark:text-text-dark-tertiary group-hover:text-warning-600 dark:group-hover:text-warning-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                    </svg>
                </a>

                <a href="{{ route('home') }}" class="flex items-center p-3 bg-success-50 dark:bg-success-900/20 hover:bg-success-100 dark:hover:bg-success-900/30 rounded-lg transition-colors group">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-success-600 dark:text-success-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary">View Main Site</p>
                        <p class="text-xs text-text-secondary dark:text-text-dark-secondary">Go to public website</p>
                    </div>
                    <svg class="w-4 h-4 text-text-tertiary dark:text-text-dark-tertiary group-hover:text-success-600 dark:group-hover:text-success-400 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"/>
                    </svg>
                </a>

                <div class="flex items-center p-3 bg-info-50 dark:bg-info-900/20 rounded-lg">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-info-600 dark:text-info-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary">System Status</p>
                        <p class="text-xs text-text-secondary dark:text-text-dark-secondary">All systems operational</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-success-100 dark:bg-success-900/30 text-success-800 dark:text-success-300">
                        Online
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Users -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">View all</a>
            </div>
            <div class="space-y-3">
                @forelse($recentUsers as $user)
                    <div class="flex items-center space-x-3 p-3 bg-background dark:bg-background-dark rounded-lg">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary-600 dark:bg-primary-500 rounded-full flex items-center justify-center">
                                <span class="text-xs font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary truncate">{{ $user->name }}</p>
                            <p class="text-xs text-text-secondary dark:text-text-dark-secondary truncate">{{ $user->email }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary">{{ $user->credits }} credits</p>
                            <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">{{ $user->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-text-secondary dark:text-text-dark-secondary text-center py-4">No users found</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Documents -->
        <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-text-primary dark:text-text-dark-primary">Recent Documents</h3>
            </div>
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
                            <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary truncate">{{ $document->getDisplayName() }}</p>
                            <p class="text-xs text-text-secondary dark:text-text-dark-secondary truncate">by {{ $document->user->name }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary">{{ $document->getFormatDisplay() }}</p>
                            <p class="text-xs text-text-tertiary dark:text-text-dark-tertiary">{{ $document->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-text-secondary dark:text-text-dark-secondary text-center py-4">No documents found</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection