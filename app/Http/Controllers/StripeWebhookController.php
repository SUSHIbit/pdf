<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeWebhookController extends Controller
{
    /**
     * Handle Stripe webhook events
     */
    public function handle(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        $payload = $request->getContent();
        $sig_header = $request->header('stripe-signature');
        $endpoint_secret = config('services.stripe.webhook_secret');

        try {
            // Verify webhook signature (if webhook secret is configured)
            if ($endpoint_secret) {
                $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            } else {
                // For development/testing without webhook secret
                $event = json_decode($payload, true);
            }

            Log::info('Stripe webhook received', [
                'event_type' => $event['type'],
                'event_id' => $event['id']
            ]);

            // Handle the event
            switch ($event['type']) {
                case 'checkout.session.completed':
                    $this->handleCheckoutCompleted($event['data']['object']);
                    break;
                    
                case 'payment_intent.succeeded':
                    Log::info('Payment succeeded', ['payment_intent' => $event['data']['object']['id']]);
                    break;
                    
                case 'payment_intent.payment_failed':
                    Log::warning('Payment failed', ['payment_intent' => $event['data']['object']['id']]);
                    break;
                    
                default:
                    Log::info('Unhandled webhook event type', ['type' => $event['type']]);
            }

            return response()->json(['status' => 'success']);

        } catch (SignatureVerificationException $e) {
            Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        } catch (\Exception $e) {
            Log::error('Webhook processing failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Handle completed checkout session
     */
    private function handleCheckoutCompleted($session)
    {
        Log::info('Processing checkout completion webhook', [
            'session_id' => $session['id'],
            'payment_status' => $session['payment_status']
        ]);

        // Only process if payment is actually completed
        if ($session['payment_status'] !== 'paid') {
            Log::warning('Checkout session not paid', [
                'session_id' => $session['id'],
                'payment_status' => $session['payment_status']
            ]);
            return;
        }

        $credits = (int) $session['metadata']['credits'];
        $userId = (int) $session['metadata']['user_id'];
        $sessionId = $session['id'];

        // Check if already processed
        $existingTransaction = CreditTransaction::where('stripe_session_id', $sessionId)->first();
        if ($existingTransaction) {
            Log::info('Webhook: Session already processed', ['session_id' => $sessionId]);
            return;
        }

        try {
            DB::transaction(function () use ($credits, $userId, $sessionId, $session) {
                // Add credits to user
                User::where('id', $userId)->increment('credits', $credits);
                
                // Log transaction
                CreditTransaction::create([
                    'user_id' => $userId,
                    'type' => 'purchase',
                    'credits' => $credits,
                    'amount' => $session['amount_total'] / 100, // Convert from cents
                    'stripe_session_id' => $sessionId,
                    'description' => "Webhook: Credit purchase - {$credits} credits",
                ]);

                Log::info('Webhook: Credits added successfully', [
                    'user_id' => $userId,
                    'credits' => $credits,
                    'session_id' => $sessionId
                ]);
            });

        } catch (\Exception $e) {
            Log::error('Webhook: Failed to process payment', [
                'session_id' => $sessionId,
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}