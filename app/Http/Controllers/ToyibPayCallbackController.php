<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CreditTransaction;
use App\Services\ToyibPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ToyibPayCallbackController extends Controller
{
    private ToyibPayService $toyibPayService;

    public function __construct(ToyibPayService $toyibPayService)
    {
        $this->toyibPayService = $toyibPayService;
    }

    /**
     * Handle ToyibPay callback
     */
    public function handle(Request $request)
    {
        $callbackData = $request->all();
        
        Log::info('ToyibPay callback received', [
            'callback_data' => $callbackData
        ]);

        try {
            // Verify callback data
            if (!$this->toyibPayService->verifyCallback($callbackData)) {
                Log::warning('ToyibPay callback verification failed', [
                    'callback_data' => $callbackData
                ]);
                return response('Invalid callback', 400);
            }

            $billCode = $callbackData['billcode'];
            $statusId = $callbackData['status_id'];
            
            // Get bill details from ToyibPay
            $billData = $this->toyibPayService->getBillStatus($billCode);
            
            // Only process successful payments
            if ($statusId != '1' || !$this->toyibPayService->isPaymentSuccessful($billData)) {
                Log::info('ToyibPay callback - payment not successful', [
                    'bill_code' => $billCode,
                    'status_id' => $statusId
                ]);
                return response('OK', 200);
            }

            // Check if already processed
            $existingTransaction = CreditTransaction::where('toyyibpay_bill_code', $billCode)->first();
            if ($existingTransaction) {
                Log::info('ToyibPay callback - bill already processed', [
                    'bill_code' => $billCode
                ]);
                return response('OK', 200);
            }

            // Extract user info from external reference
            $externalRef = $billData['billExternalReferenceNo'] ?? '';
            if (!preg_match('/AIDOC_(\d+)_\d+/', $externalRef, $matches)) {
                Log::error('ToyibPay callback - invalid external reference', [
                    'bill_code' => $billCode,
                    'external_ref' => $externalRef
                ]);
                return response('Invalid reference', 400);
            }

            $userId = (int) $matches[1];
            $amount = ($billData['billAmount'] ?? 0) / 100; // Convert from sen to RM
            
            // Determine credits and package type based on amount
            $packageInfo = $this->determinePackageFromAmount($amount);
            if (!$packageInfo) {
                Log::error('ToyibPay callback - unknown package amount', [
                    'bill_code' => $billCode,
                    'amount' => $amount
                ]);
                return response('Unknown package', 400);
            }

            // Process the payment
            DB::transaction(function () use ($userId, $billCode, $amount, $packageInfo) {
                $user = User::findOrFail($userId);
                
                // Add credits
                $user->increment('credits', $packageInfo['credits']);
                
                // Mark trial pack as used if applicable
                if ($packageInfo['type'] === 'trial') {
                    $user->update(['trial_pack_used' => true]);
                }
                
                // Log transaction
                CreditTransaction::create([
                    'user_id' => $userId,
                    'type' => 'purchase',
                    'credits' => $packageInfo['credits'],
                    'amount' => $amount,
                    'toyyibpay_bill_code' => $billCode,
                    'description' => "Callback: {$packageInfo['description']} - {$packageInfo['credits']} credits",
                ]);

                Log::info('ToyibPay callback - credits added successfully', [
                    'user_id' => $userId,
                    'credits' => $packageInfo['credits'],
                    'bill_code' => $billCode,
                    'package_type' => $packageInfo['type']
                ]);
            });

            return response('OK', 200);

        } catch (\Exception $e) {
            Log::error('ToyibPay callback processing failed', [
                'error' => $e->getMessage(),
                'callback_data' => $callbackData
            ]);
            
            return response('Error processing callback', 500);
        }
    }

    /**
     * Determine package details from amount
     */
    private function determinePackageFromAmount(float $amount): ?array
    {
        $packages = [
            3.00 => ['credits' => 5, 'type' => 'trial', 'description' => 'Trial pack'],
            8.00 => ['credits' => 10, 'type' => 'standard', 'description' => 'Standard pack'],
            15.00 => ['credits' => 25, 'type' => 'standard', 'description' => 'Standard pack'],
            25.00 => ['credits' => 50, 'type' => 'standard', 'description' => 'Standard pack'],
        ];

        return $packages[$amount] ?? null;
    }
}