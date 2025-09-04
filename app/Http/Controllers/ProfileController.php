<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $updateData = $request->validated();
        
        // Normalize phone number if provided
        if (!empty($updateData['phone'])) {
            $updateData['phone'] = $this->normalizePhoneNumber($updateData['phone']);
        }

        $request->user()->fill($updateData);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
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