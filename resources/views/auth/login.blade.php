@extends('layouts.guest')

@section('content')
    <!-- Session Status -->
    @if(session('status'))
        <div class="font-medium text-sm text-green-600 mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if(session('info'))
        <div class="font-medium text-sm text-cyan-600 mb-4 p-3 bg-cyan-50 border border-cyan-200 rounded-md">
            {{ session('info') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
            <input id="email" 
                   class="block mt-1 w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm" 
                   type="email" 
                   name="email" 
                   value="{{ old('email') }}" 
                   required 
                   autofocus 
                   autocomplete="username" />
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
            <input id="password" 
                   class="block mt-1 w-full border-gray-300 focus:border-cyan-500 focus:ring-cyan-500 rounded-md shadow-sm"
                   type="password"
                   name="password"
                   required 
                   autocomplete="current-password" />
            @error('password')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-cyan-600 shadow-sm focus:ring-cyan-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">Remember me</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-500" href="{{ route('password.request') }}">
                    Forgot your password?
                </a>
            @endif

            <button type="submit" class="ms-3 inline-flex items-center px-4 py-2 bg-cyan-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-cyan-700 focus:bg-cyan-700 active:bg-cyan-900 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Log in
            </button>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" class="font-medium text-cyan-600 hover:text-cyan-500">
                    Sign up
                </a>
            </p>
        </div>
    </form>
@endsection