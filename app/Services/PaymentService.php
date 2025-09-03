<?php

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create a Stripe checkout session
     */
    public function createCheckoutSession(int $credits, float $amount): Session
    {
        try {
            Log::info('Creating Stripe checkout session', [
                'credits' => $credits,
                'amount' => $amount,
                'user_id' => auth()->id()
            ]);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => "AI Document Credits ({$credits} credits)",
                            'description' => "Process {$credits} documents and generate Q&A pairs",
                        ],
                        'unit_amount' => (int)($amount * 100), // Convert to cents
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('payment.success', [], true) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payment.cancel', [], true),
                'metadata' => [
                    'credits' => (string)$credits,
                    'user_id' => (string)auth()->id(),
                    'user_email' => auth()->user()->email,
                ],
                'customer_email' => auth()->user()->email,
                'billing_address_collection' => 'auto',
            ]);

            Log::info('Stripe checkout session created successfully', [
                'session_id' => $session->id,
                'url' => $session->url
            ]);

            return $session;

        } catch (ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage(), [
                'error_type' => $e->getError()->type,
                'error_code' => $e->getError()->code,
                'user_id' => auth()->id()
            ]);
            throw new \Exception('Payment system error. Please try again.');
        }
    }

    /**
     * Retrieve a Stripe session
     */
    public function getSession(string $sessionId): Session
    {
        try {
            Log::info('Retrieving Stripe session', ['session_id' => $sessionId]);

            $session = Session::retrieve($sessionId);

            Log::info('Stripe session retrieved successfully', [
                'session_id' => $sessionId,
                'payment_status' => $session->payment_status,
                'customer_email' => $session->customer_email
            ]);

            return $session;

        } catch (ApiErrorException $e) {
            Log::error('Failed to retrieve Stripe session: ' . $e->getMessage(), [
                'session_id' => $sessionId,
                'error_type' => $e->getError()->type
            ]);
            throw new \Exception('Payment verification failed.');
        }
    }

    /**
     * Validate that a session belongs to the current user
     */
    public function validateUserSession(Session $session): bool
    {
        $sessionUserId = $session->metadata->user_id ?? null;
        $currentUserId = (string)auth()->id();
        
        return $sessionUserId === $currentUserId;
    }
}