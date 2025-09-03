<?php

namespace App\Http\Controllers;

use App\Services\PaymentService;
use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display credit packages
     */
    public function packages()
    {
        $packages = [
            ['credits' => 5, 'price' => 9.99, 'popular' => false],
            ['credits' => 15, 'price' => 24.99, 'popular' => true],
            ['credits' => 30, 'price' => 39.99, 'popular' => false],
        ];

        return view('payment.packages', compact('packages'));
    }

    /**
     * Create Stripe checkout session
     */
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

        try {
            Log::info('Starting checkout process', [
                'user_id' => auth()->id(),
                'credits' => $credits,
                'amount' => $amount
            ]);

            $session = $this->paymentService->createCheckoutSession($credits, $amount);

            return redirect($session->url);

        } catch (\Exception $e) {
            Log::error('Checkout failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'credits' => $credits
            ]);

            return redirect()->route('payment.packages')
                ->with('error', 'Unable to process payment. Please try again.');
        }
    }

    /**
     * Handle successful payment
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            Log::warning('Payment success called without session ID');
            return redirect()->route('payment.packages')
                ->with('error', 'Payment session not found.');
        }

        try {
            Log::info('Processing payment success', [
                'session_id' => $sessionId,
                'user_id' => auth()->id()
            ]);

            $session = $this->paymentService->getSession($sessionId);
            
            // Validate session belongs to current user
            if (!$this->paymentService->validateUserSession($session)) {
                Log::warning('Session validation failed', [
                    'session_id' => $sessionId,
                    'session_user_id' => $session->metadata->user_id ?? 'unknown',
                    'current_user_id' => auth()->id()
                ]);
                
                return redirect()->route('dashboard')
                    ->with('error', 'Payment verification failed.');
            }
            
            // Check payment status
            if ($session->payment_status !== 'paid') {
                Log::warning('Payment not completed', [
                    'session_id' => $sessionId,
                    'payment_status' => $session->payment_status
                ]);
                
                return redirect()->route('payment.packages')
                    ->with('error', 'Payment was not completed. Please try again.');
            }

            $credits = (int) $session->metadata->credits;
            $userId = (int) $session->metadata->user_id;
            
            // Check if this session was already processed
            $existingTransaction = CreditTransaction::where('stripe_session_id', $sessionId)->first();
            if ($existingTransaction) {
                Log::info('Session already processed', ['session_id' => $sessionId]);
                
                return redirect()->route('dashboard')
                    ->with('success', "Welcome back! Your {$credits} credits are already in your account.");
            }
            
            // Process the payment
            DB::transaction(function () use ($credits, $userId, $sessionId, $session) {
                // Add credits to user
                User::where('id', $userId)->increment('credits', $credits);
                
                // Log transaction
                CreditTransaction::create([
                    'user_id' => $userId,
                    'type' => 'purchase',
                    'credits' => $credits,
                    'amount' => $session->amount_total / 100, // Convert from cents
                    'stripe_session_id' => $sessionId,
                    'description' => "Credit purchase - {$credits} credits",
                ]);
                
                Log::info('Credits added successfully', [
                    'user_id' => $userId,
                    'credits_added' => $credits,
                    'session_id' => $sessionId
                ]);
            });

            return redirect()->route('dashboard')
                ->with('success', "Payment successful! {$credits} credits have been added to your account.");

        } catch (\Exception $e) {
            Log::error('Payment success processing failed: ' . $e->getMessage(), [
                'session_id' => $sessionId,
                'user_id' => auth()->id()
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'There was an issue processing your payment. Please contact support if credits were not added.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        Log::info('Payment cancelled by user', ['user_id' => auth()->id()]);
        
        return redirect()->route('payment.packages')
            ->with('error', 'Payment was cancelled. No charges were made.');
    }
}