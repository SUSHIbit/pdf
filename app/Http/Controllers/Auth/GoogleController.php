<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Exception;

class GoogleController extends Controller
{
    /**
     * Redirect to Google OAuth
     */
    public function redirect()
    {
        try {
            Log::info('Starting Google OAuth redirect');
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Google OAuth redirect failed: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to connect to Google. Please try again or use email/password.');
        }
    }

    /**
     * Handle Google OAuth callback
     */
    public function callback()
    {
        try {
            Log::info('Google OAuth callback started');
            
            // Get Google user data
            $googleUser = Socialite::driver('google')->user();
            
            Log::info('Google user data received', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ]);
            
            // Check for pending upload
            $hasPendingUpload = session()->has('pending_upload');
            
            Log::info('Google OAuth callback processing', [
                'google_id' => $googleUser->getId(),
                'email' => $googleUser->getEmail(),
                'has_pending_upload' => $hasPendingUpload
            ]);

            // Find existing user by google_id or email
            $user = User::where('google_id', $googleUser->getId())
                       ->orWhere('email', $googleUser->getEmail())
                       ->first();

            if ($user) {
                Log::info('Existing user found', ['user_id' => $user->id]);
                
                // Update google_id if not set
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                    Log::info('Updated user with Google ID', ['user_id' => $user->id]);
                }
                
                // Mark email as verified since Google handles verification
                if (!$user->email_verified_at) {
                    $user->update(['email_verified_at' => now()]);
                    Log::info('Marked email as verified', ['user_id' => $user->id]);
                }
                
                Auth::login($user);
                
                Log::info('Existing user logged in via Google', [
                    'user_id' => $user->id,
                    'has_pending_upload' => $hasPendingUpload
                ]);
                
            } else {
                Log::info('Creating new user from Google OAuth');
                
                // New user - create account with Google info
                $user = DB::transaction(function () use ($googleUser) {
                    $newUser = User::create([
                        'name' => $googleUser->getName(),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'password' => Hash::make(Str::random(24)), // Random password since they use Google
                        'credits' => 5, // Welcome credits
                        'email_verified_at' => now(), // Google users are pre-verified
                    ]);

                    Log::info('New user created', ['user_id' => $newUser->id]);

                    // Log the welcome credits
                    CreditTransaction::create([
                        'user_id' => $newUser->id,
                        'type' => 'purchase',
                        'credits' => 5,
                        'amount' => null,
                        'description' => 'Welcome bonus - 5 free credits',
                    ]);

                    Log::info('Welcome credits added', ['user_id' => $newUser->id]);

                    return $newUser;
                });

                Auth::login($user);
                
                Log::info('New user created and logged in via Google OAuth', [
                    'user_id' => $user->id,
                    'has_pending_upload' => $hasPendingUpload
                ]);
            }

            // Handle pending upload redirect (same as regular auth)
            if ($hasPendingUpload) {
                Log::info('User has pending upload - redirecting to upload page', [
                    'user_id' => auth()->id()
                ]);
                
                session()->forget('pending_upload');
                session(['from_landing' => true]);
                
                return redirect()->route('documents.upload')
                    ->with('from_landing', true)
                    ->with('success', 'Welcome! You can now upload your document.');
            }

            // Normal redirect to dashboard
            Log::info('Redirecting to dashboard after successful Google OAuth');
            return redirect()->route('dashboard')
                ->with('success', 'Welcome back! You have been logged in successfully.');

        } catch (Exception $e) {
            Log::error('Google OAuth callback failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Unable to login with Google. Please try again or use email/password.');
        }
    }
}