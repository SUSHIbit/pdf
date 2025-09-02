<?php
// app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CreditTransaction;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // CRITICAL FIX: Check for pending upload BEFORE creating user
        $hasPendingUpload = session()->has('pending_upload');
        
        Log::info('Registration process started', [
            'email' => $request->email,
            'has_pending_upload' => $hasPendingUpload
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'credits' => 5,
            ]);

            // Log the welcome credits
            CreditTransaction::create([
                'user_id' => $user->id,
                'type' => 'purchase',
                'credits' => 5,
                'amount' => null,
                'description' => 'Welcome bonus - 5 free credits',
            ]);

            event(new Registered($user));
            Auth::login($user);
        });

        // ENHANCED FIX: Check for pending upload and handle redirect
        if ($hasPendingUpload) {
            Log::info('User has pending upload - redirecting to upload page', [
                'user_id' => auth()->id()
            ]);
            
            // Clear the pending upload flag and set from_landing flag
            session()->forget('pending_upload');
            session(['from_landing' => true]);
            
            return redirect()->route('documents.upload')
                ->with('from_landing', true)
                ->with('success', 'Welcome! Your account has been created with 5 free credits. You can now upload your document.');
        }

        Log::info('Normal registration - redirecting to dashboard', [
            'user_id' => auth()->id()
        ]);

        return redirect(RouteServiceProvider::HOME)
            ->with('success', 'Welcome! Your account has been created with 5 free credits.');
    }
}