<?php
// app/Http/Controllers/Auth/AuthenticatedSessionController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // CRITICAL FIX: Check for pending upload BEFORE session regeneration affects it
        $hasPendingUpload = session()->has('pending_upload');
        
        Log::info('Login process completed', [
            'user_id' => auth()->id(),
            'has_pending_upload' => $hasPendingUpload
        ]);

        // ENHANCED FIX: Handle pending upload redirect
        if ($hasPendingUpload) {
            Log::info('User has pending upload - redirecting to upload page', [
                'user_id' => auth()->id()
            ]);
            
            // Clear the pending upload flag and set from_landing flag
            session()->forget('pending_upload');
            session(['from_landing' => true]);
            
            return redirect()->route('documents.upload')
                ->with('from_landing', true)
                ->with('success', 'Welcome back! You can now upload your document.');
        }

        Log::info('Normal login - using intended redirect or dashboard', [
            'user_id' => auth()->id()
        ]);

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}