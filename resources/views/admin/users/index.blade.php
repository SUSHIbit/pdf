@extends('admin.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-text-primary dark:text-text-dark-primary mb-2 transition-colors">
                    User Management
                </h1>
                <p class="text-text-secondary dark:text-text-dark-secondary transition-colors">
                    Manage user accounts and credits for your platform.
                </p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-xl border border-border dark:border-border-dark px-4 py-2">
                    <span class="text-sm font-medium text-text-primary dark:text-text-dark-primary">
                        Total Users: {{ $users->total() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark p-6 mb-8">
        <form method="GET" action="{{ route('admin.users.index') }}" class="space-y-4 sm:space-y-0 sm:flex sm:items-end sm:space-x-4">
            <!-- Search Input -->
            <div class="flex-1">
                <label for="search" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">Search Users</label>
                <input type="text" 
                       name="search" 
                       id="search"
                       value="{{ request('search') }}"
                       placeholder="Search by name, email, or phone..."
                       class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary placeholder-text-tertiary dark:placeholder-text-dark-tertiary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200">
            </div>

            <!-- Credits Filter -->
            <div class="w-full sm:w-auto">
                <label for="credits_filter" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">Credits Filter</label>
                <select name="credits_filter" 
                        id="credits_filter"
                        class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200">
                    <option value="">All Credits</option>
                    <option value="zero" {{ request('credits_filter') === 'zero' ? 'selected' : '' }}>Zero Credits</option>
                    <option value="low" {{ request('credits_filter') === 'low' ? 'selected' : '' }}>Low (1-5)</option>
                    <option value="medium" {{ request('credits_filter') === 'medium' ? 'selected' : '' }}>Medium (6-20)</option>
                    <option value="high" {{ request('credits_filter') === 'high' ? 'selected' : '' }}>High (20+)</option>
                </select>
            </div>

            <!-- Sort Options -->
            <div class="w-full sm:w-auto">
                <label for="sort" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">Sort By</label>
                <select name="sort" 
                        id="sort"
                        class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200">
                    <option value="created_at" {{ request('sort') === 'created_at' ? 'selected' : '' }}>Join Date</option>
                    <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Name</option>
                    <option value="email" {{ request('sort') === 'email' ? 'selected' : '' }}>Email</option>
                    <option value="credits" {{ request('sort') === 'credits' ? 'selected' : '' }}>Credits</option>
                </select>
            </div>

            <!-- Sort Order -->
            <div class="w-full sm:w-auto">
                <label for="order" class="block text-sm font-medium text-text-primary dark:text-text-dark-primary mb-2">Order</label>
                <select name="order" 
                        id="order"
                        class="block w-full px-4 py-3 bg-surface dark:bg-surface-dark border border-border dark:border-border-dark rounded-xl text-text-primary dark:text-text-dark-primary focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400 focus:border-transparent transition-all duration-200">
                    <option value="desc" {{ request('order') === 'desc' ? 'selected' : '' }}>Descending</option>
                    <option value="asc" {{ request('order') === 'asc' ? 'selected' : '' }}>Ascending</option>
                </select>
            </div>

            <!-- Search Button -->
            <div class="w-full sm:w-auto">
                <button type="submit" 
                        class="w-full sm:w-auto px-6 py-3 bg-primary-600 dark:bg-primary-700 hover:bg-primary-700 dark:hover:bg-primary-600 text-white font-medium rounded-xl transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-400">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Search
                </button>
            </div>

            <!-- Clear Filters Button -->
            @if(request()->hasAny(['search', 'credits_filter', 'sort', 'order']))
                <div class="w-full sm:w-auto">
                    <a href="{{ route('admin.users.index') }}" 
                       class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-secondary-100 dark:bg-secondary-800 hover:bg-secondary-200 dark:hover:bg-secondary-700 text-text-primary dark:text-text-dark-primary font-medium rounded-xl transition-all duration-200 transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Clear
                    </a>
                </div>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl shadow-sm border border-border dark:border-border-dark overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-border dark:divide-border-dark">
                <thead class="bg-background-secondary dark:bg-background-dark-secondary">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-text-dark-secondary uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-text-dark-secondary uppercase tracking-wider">
                            Contact
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-text-dark-secondary uppercase tracking-wider">
                            Credits
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-text-dark-secondary uppercase tracking-wider">
                            Documents
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary dark:text-text-dark-secondary uppercase tracking-wider">
                            Joined
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-text-secondary dark:text-text-dark-secondary uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-surface dark:bg-surface-dark divide-y divide-border dark:divide-border-dark">
                    @forelse($users as $user)
                        <tr class="hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-primary-600 dark:bg-primary-500 flex items-center justify-center">
                                            <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-text-primary dark:text-text-dark-primary">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-text-secondary dark:text-text-dark-secondary">
                                            ID: {{ $user->id }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-text-primary dark:text-text-dark-primary">{{ $user->email }}</div>
                                @if($user->phone)
                                    <div class="text-sm text-text-secondary dark:text-text-dark-secondary">{{ $user->formatted_phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->credits == 0) bg-error-100 dark:bg-error-900/30 text-error-800 dark:text-error-300
                                        @elseif($user->credits <= 5) bg-warning-100 dark:bg-warning-900/30 text-warning-800 dark:text-warning-300
                                        @elseif($user->credits <= 20) bg-primary-100 dark:bg-primary-800 text-primary-800 dark:text-primary-300
                                        @else bg-success-100 dark:bg-success-900/30 text-success-800 dark:text-success-300
                                        @endif">
                                        {{ $user->credits }} credits
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-primary dark:text-text-dark-primary">
                                {{ $user->documents_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary dark:text-text-dark-secondary">
                                {{ $user->created_at->format('M j, Y') }}
                                <div class="text-xs">{{ $user->created_at->diffForHumans() }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.users.show', $user) }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-primary-100 dark:bg-primary-800 hover:bg-primary-200 dark:hover:bg-primary-700 text-primary-700 dark:text-primary-300 text-sm font-medium rounded-lg transition-colors">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-text-secondary dark:text-text-dark-secondary">
                                    <svg class="mx-auto h-12 w-12 text-text-tertiary dark:text-text-dark-tertiary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-lg font-medium">No users found</p>
                                    <p class="text-sm">Try adjusting your search criteria</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="lg:hidden divide-y divide-border dark:divide-border-dark">
            @forelse($users as $user)
                <div class="p-6 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="h-12 w-12 rounded-full bg-primary-600 dark:bg-primary-500 flex items-center justify-center">
                                <span class="text-sm font-medium text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-text-primary dark:text-text-dark-primary truncate">
                                    {{ $user->name }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($user->credits == 0) bg-error-100 dark:bg-error-900/30 text-error-800 dark:text-error-300
                                    @elseif($user->credits <= 5) bg-warning-100 dark:bg-warning-900/30 text-warning-800 dark:text-warning-300
                                    @elseif($user->credits <= 20) bg-primary-100 dark:bg-primary-800 text-primary-800 dark:text-primary-300
                                    @else bg-success-100 dark:bg-success-900/30 text-success-800 dark:text-success-300
                                    @endif">
                                    {{ $user->credits }}
                                </span>
                            </div>
                            <p class="text-sm text-text-secondary dark:text-text-dark-secondary truncate">{{ $user->email }}</p>
                            <div class="mt-2 flex items-center justify-between text-xs text-text-tertiary dark:text-text-dark-tertiary">
                                <span>{{ $user->documents_count }} documents</span>
                                <span>{{ $user->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('admin.users.show', $user) }}" 
                           class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary-100 dark:bg-primary-800 hover:bg-primary-200 dark:hover:bg-primary-700 text-primary-700 dark:text-primary-300 text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View Details
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="text-text-secondary dark:text-text-dark-secondary">
                        <svg class="mx-auto h-12 w-12 text-text-tertiary dark:text-text-dark-tertiary mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-lg font-medium">No users found</p>
                        <p class="text-sm">Try adjusting your search criteria</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-8">
            <div class="bg-surface/80 dark:bg-surface-dark/80 backdrop-blur-sm rounded-2xl border border-border dark:border-border-dark p-6">
                {{ $users->links() }}
            </div>
        </div>
    @endif
</div>
@endsection