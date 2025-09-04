<?php

namespace App\Http\Controllers;

use App\Services\ToyibPayService;
use App\Models\User;
use App\Models\CreditTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private ToyibPayService $toyibPayService;

    public function __construct(ToyibPayService $toyibPayService)
    {
        $this->toyibPayService = $toyibPayService;
    }

    /**
     * Display credit packages
     */
    public function packages()
    {
        $user = auth()->user();
        
        // Trial pack (first-time only)
        $trialPack = null;
        if (!$user->trial_pack_used) {
            $trialPack = [
                'credits' => 5,
                'price' => 3.00,
                'type' => 'trial',
                'description' => 'First-Time Only',
                'per_credit_price' => 0.60
            ];
        }

        // Standard packages (repeatable)
        $standardPackages = [
            [
                'credits' => 10, 
                'price' => 8.00, 
                'type' => 'standard',
                'popular' => false,
                'per_credit_price' => 0.80
            ],
            [
                'credits' => 25, 
                'price' => 15.00, 
                'type' => 'standard',
                'popular' => true,
                'per_credit_price' => 0.60
            ],
            [
                'credits' => 50, 
                'price' => 25.00, 
                'type' => 'standard',
                'popular' => false,
                'best_value' => true,
                'per_credit_price' => 0.50
            ],
        ];

        return view('payment.packages', compact('trialPack', 'standardPackages'));
    }

    /**
     * Create ToyibPay bill
     */
    public function checkout(Request $request)
    {
        // Updated validation to be more flexible
        $request->validate([
            'credits' => 'required|integer|min:1',
            'package_type' => 'required|string|in:trial,standard',
        ]);

        $user = auth()->user();
        $credits = (int) $request->credits;
        $packageType = $request->package_type;

        Log::info('Checkout attempt', [
            'user_id' => $user->id,
            'credits' => $credits,
            'package_type' => $packageType,
            'trial_pack_used' => $user->trial_pack_used
        ]);

        // Validate trial pack usage
        if ($packageType === 'trial') {
            if ($user->trial_pack_used) {
                Log::warning('Trial pack already used', ['user_id' => $user->id]);
                return redirect()->route('payment.packages')
                    ->with('error', 'Trial pack can only be purchased once per account.');
            }
            if ($credits !== 5) {
                Log::warning('Invalid trial pack credits', [
                    'user_id' => $user->id,
                    'credits' => $credits
                ]);
                return redirect()->route('payment.packages')
                    ->with('error', 'Invalid trial pack selection.');
            }
        }

        // Define pricing - more flexible approach
        $pricing = [
            'trial' => [
                5 => 3.00
            ],
            'standard' => [
                10 => 8.00,
                25 => 15.00,
                50 => 25.00,
            ]
        ];

        // Check if the combination is valid
        if (!isset($pricing[$packageType][$credits])) {
            Log::warning('Invalid package combination', [
                'user_id' => $user->id,
                'credits' => $credits,
                'package_type' => $packageType,
                'available_pricing' => $pricing
            ]);
            return redirect()->route('payment.packages')
                ->with('error', 'Invalid package selection.');
        }

        $amount = $pricing[$packageType][$credits];

        try {
            Log::info('Starting ToyibPay checkout process', [
                'user_id' => $user->id,
                'credits' => $credits,
                'amount' => $amount,
                'package_type' => $packageType
            ]);

            $billData = $this->toyibPayService->createBill($credits, $amount, $user->id);

            // Store bill information in session for verification
            session([
                'toyyibpay_bill_code' => $billData['bill_code'],
                'toyyibpay_credits' => $credits,
                'toyyibpay_amount' => $amount,
                'toyyibpay_package_type' => $packageType,
                'toyyibpay_user_id' => $user->id,
            ]);

            Log::info('ToyibPay bill created, redirecting to payment', [
                'bill_code' => $billData['bill_code'],
                'bill_url' => $billData['bill_url']
            ]);

            return redirect($billData['bill_url']);

        } catch (\Exception $e) {
            Log::error('ToyibPay checkout failed: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'credits' => $credits,
                'package_type' => $packageType,
                'error_trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('payment.packages')
                ->with('error', 'Unable to process payment. Please try again. Error details: ' . $e->getMessage());
        }
    }

    /**
     * Handle successful payment return
     */
    public function success(Request $request)
    {
        $billCode = $request->get('billcode') ?? session('toyyibpay_bill_code');
        
        if (!$billCode) {
            Log::warning('Payment success called without bill code');
            return redirect()->route('payment.packages')
                ->with('error', 'Payment session not found.');
        }

        try {
            Log::info('Processing ToyibPay payment success', [
                'bill_code' => $billCode,
                'user_id' => auth()->id()
            ]);

            // Get bill status from ToyibPay
            $billData = $this->toyibPayService->getBillStatus($billCode);
            
            if (!$this->toyibPayService->isPaymentSuccessful($billData)) {
                Log::warning('Payment not completed', [
                    'bill_code' => $billCode,
                    'bill_status' => $billData['billpaymentStatus'] ?? 'unknown'
                ]);
                
                return redirect()->route('payment.packages')
                    ->with('error', 'Payment was not completed. Please try again.');
            }

            // Get payment details from session
            $credits = session('toyyibpay_credits');
            $amount = session('toyyibpay_amount');
            $packageType = session('toyyibpay_package_type');
            $userId = session('toyyibpay_user_id');
            
            if (!$credits || !$amount || !$userId) {
                Log::error('Missing payment session data', [
                    'bill_code' => $billCode,
                    'session_data' => [
                        'credits' => $credits,
                        'amount' => $amount,
                        'user_id' => $userId,
                        'package_type' => $packageType
                    ]
                ]);
                
                return redirect()->route('payment.packages')
                    ->with('error', 'Payment verification failed. Please contact support.');
            }

            // Check if this bill was already processed
            $existingTransaction = CreditTransaction::where('toyyibpay_bill_code', $billCode)->first();
            if ($existingTransaction) {
                Log::info('Bill already processed', ['bill_code' => $billCode]);
                
                // Clear session data
                session()->forget([
                    'toyyibpay_bill_code', 'toyyibpay_credits', 'toyyibpay_amount', 
                    'toyyibpay_package_type', 'toyyibpay_user_id'
                ]);
                
                return redirect()->route('dashboard')
                    ->with('success', "Welcome back! Your {$credits} credits are already in your account.");
            }
            
            // Process the payment
            DB::transaction(function () use ($credits, $userId, $billCode, $amount, $packageType) {
                $user = User::findOrFail($userId);
                
                // Add credits to user
                $user->increment('credits', $credits);
                
                // Mark trial pack as used if applicable
                if ($packageType === 'trial') {
                    $user->update(['trial_pack_used' => true]);
                }
                
                // Log transaction
                CreditTransaction::create([
                    'user_id' => $userId,
                    'type' => 'purchase',
                    'credits' => $credits,
                    'amount' => $amount,
                    'toyyibpay_bill_code' => $billCode,
                    'description' => $packageType === 'trial' 
                        ? "Trial pack - {$credits} credits" 
                        : "Credit purchase - {$credits} credits",
                ]);
                
                Log::info('Credits added successfully via ToyibPay', [
                    'user_id' => $userId,
                    'credits_added' => $credits,
                    'bill_code' => $billCode,
                    'package_type' => $packageType
                ]);
            });

            // Clear session data
            session()->forget([
                'toyyibpay_bill_code', 'toyyibpay_credits', 'toyyibpay_amount', 
                'toyyibpay_package_type', 'toyyibpay_user_id'
            ]);

            return redirect()->route('dashboard')
                ->with('success', "Payment successful! {$credits} credits have been added to your account.");

        } catch (\Exception $e) {
            Log::error('Payment success processing failed: ' . $e->getMessage(), [
                'bill_code' => $billCode,
                'user_id' => auth()->id(),
                'error_trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('dashboard')
                ->with('error', 'There was an issue processing your payment. Please contact support if credits were not added.');
        }
    }

    /**
     * Handle cancelled payment
     */
    public function cancel(Request $request)
    {
        Log::info('Payment cancelled by user', ['user_id' => auth()->id()]);
        
        // Clear session data
        session()->forget([
            'toyyibpay_bill_code', 'toyyibpay_credits', 'toyyibpay_amount', 
            'toyyibpay_package_type', 'toyyibpay_user_id'
        ]);
        
        return redirect()->route('payment.packages')
            ->with('error', 'Payment was cancelled. No charges were made.');
    }
}