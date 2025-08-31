<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function packages()
    {
        $packages = [
            ['credits' => 5, 'price' => 9.99, 'popular' => false],
            ['credits' => 15, 'price' => 24.99, 'popular' => true],
            ['credits' => 30, 'price' => 39.99, 'popular' => false],
        ];

        return view('payment.packages', compact('packages'));
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'credits' => 'required|integer|in:5,15,30',
        ]);

        $packages = [
            5 => 9.99,
            15 => 24.99,
            30 => 39.99,
        ];

        $credits = $request->credits;
        $amount = $packages[$credits];

        $session = $this->paymentService->createCheckoutSession($credits, $amount);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Payment session not found.');
        }

        $session = $this->paymentService->getSession($sessionId);
        
        if ($session->payment_status === 'paid') {
            $credits = (int) $session->metadata->credits;
            $userId = (int) $session->metadata->user_id;
            
            // Use database transaction to add credits
            DB::transaction(function () use ($credits, $userId, $sessionId) {
                // Add credits to user
                User::where('id', $userId)->increment('credits', $credits);
                
                // Log transaction
                CreditTransaction::create([
                    'user_id' => $userId,
                    'type' => 'purchase',
                    'credits' => $credits,
                    'amount' => null,
                    'stripe_session_id' => $sessionId,
                    'description' => "Credit purchase - {$credits} credits",
                ]);
            });

            return redirect()
                ->route('dashboard')
                ->with('success', "Payment successful! {$credits} credits added to your account.");
        }

        return redirect()->route('dashboard')->with('error', 'Payment was not completed.');
    }

    public function cancel()
    {
        return redirect()
            ->route('payment.packages')
            ->with('error', 'Payment was cancelled.');
    }
}