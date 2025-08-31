<?php 

namespace App\Services;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    public function createCheckoutSession(int $credits, float $amount): Session
    {
        return Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => "AI Document Credits ({$credits} credits)",
                    ],
                    'unit_amount' => (int)($amount * 100), // Convert to cents
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('payment.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('payment.cancel'),
            'metadata' => [
                'credits' => (string)$credits,
                'user_id' => (string)auth()->id(),
            ],
        ]);
    }

    public function getSession(string $sessionId): Session
    {
        return Session::retrieve($sessionId);
    }
}