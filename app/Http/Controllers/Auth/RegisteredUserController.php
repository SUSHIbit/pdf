<?php
// app/Http/Controllers/Auth/RegisteredUserController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CreditTransaction;
use App\Providers\RouteServiceProvider;
use App\Rules\MalaysianPhoneNumber;
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
            'phone' => ['nullable', 'string', 'max:20', new MalaysianPhoneNumber],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // CRITICAL FIX: Check for pending upload BEFORE creating user
        $hasPendingUpload = session()->has('pending_upload');
        
        Log::info('Registration process started', [
            'email' => $request->email,
            'has_pending_upload' => $hasPendingUpload
        ]);

        DB::transaction(function () use ($request) {
            // Normalize phone number
            $phone = null;
            if ($request->phone) {
                $phone = $this->normalizePhoneNumber($request->phone);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $phone,
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

    /**
     * Normalize Malaysian phone number to international format
     */
    private function normalizePhoneNumber(string $phone): string
    {
        // Remove all non-digit characters except +
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        // Remove + sign temporarily
        $phone = ltrim($phone, '+');
        
        // If starts with 60, it's already in international format
        if (substr($phone, 0, 2) === '60') {
            return $phone;
        }
        
        // If starts with 0, replace with 60
        if (substr($phone, 0, 1) === '0') {
            return '60' . substr($phone, 1);
        }
        
        // Otherwise, assume it's a local number and add 60
        return '60' . $phone;
    }
}