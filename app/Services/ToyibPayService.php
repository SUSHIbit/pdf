<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToyibPayService
{
    private string $categoryCode;
    private string $secretKey;
    private string $baseUrl;

    public function __construct()
    {
        $this->categoryCode = config('services.toyyibpay.category_code');
        $this->secretKey = config('services.toyyibpay.secret_key');
        $this->baseUrl = config('services.toyyibpay.base_url');
    }

    /**
     * Create a bill for payment
     */
    public function createBill(int $credits, float $amount, int $userId): array
    {
        try {
            $user = auth()->user();
            
            Log::info('Creating ToyibPay bill', [
                'credits' => $credits,
                'amount' => $amount,
                'user_id' => $userId,
            ]);

            // Use user's phone or provide a default Malaysian number
            $phoneNumber = $user->phone ?? '60123456789';
            
            // Ensure phone number has proper format (remove + and ensure it starts with 60)
            $phoneNumber = ltrim($phoneNumber, '+');
            if (!str_starts_with($phoneNumber, '60')) {
                // If it starts with 0, replace with 60
                if (str_starts_with($phoneNumber, '0')) {
                    $phoneNumber = '60' . substr($phoneNumber, 1);
                } else {
                    // Otherwise assume it's a local number and add 60
                    $phoneNumber = '60' . $phoneNumber;
                }
            }

            $billData = [
                'userSecretKey' => $this->secretKey,
                'categoryCode' => $this->categoryCode,
                'billName' => "{$credits} Credits",
                'billDescription' => "AI Document Credits - Process {$credits} documents",
                'billPriceSetting' => 1,
                'billPayorInfo' => 1,
                'billAmount' => $amount * 100, // Convert to sen
                'billReturnUrl' => route('payment.success'),
                'billCallbackUrl' => route('toyyibpay.callback'),
                'billExternalReferenceNo' => 'AIDOC_' . $userId . '_' . time(),
                'billTo' => $user->name,
                'billEmail' => $user->email,
                'billPhone' => $phoneNumber,
                'billSplitPayment' => 0,
                'billSplitPaymentArgs' => '',
                'billPaymentChannel' => '0',
                'billContentEmail' => "Thank you for purchasing {$credits} AI Document Credits!",
                'billChargeToCustomer' => 1,
            ];

            $response = Http::asForm()
                ->timeout(30)
                ->post($this->baseUrl . '/index.php/api/createBill', $billData);

            if (!$response->successful()) {
                throw new \Exception('ToyibPay request failed: ' . $response->body());
            }

            $responseData = $response->json();

            if (isset($responseData[0]['status']) && $responseData[0]['status'] === 'error') {
                throw new \Exception('ToyibPay error: ' . ($responseData[0]['msg'] ?? 'Unknown error'));
            }

            if (!isset($responseData[0]['BillCode'])) {
                throw new \Exception('No bill code returned from ToyibPay');
            }

            return [
                'bill_code' => $responseData[0]['BillCode'],
                'bill_url' => $this->baseUrl . '/' . $responseData[0]['BillCode'],
                'bill_external_ref' => $billData['billExternalReferenceNo']
            ];

        } catch (\Exception $e) {
            Log::error('ToyibPay bill creation failed: ' . $e->getMessage());
            throw new \Exception('Payment system error: ' . $e->getMessage());
        }
    }

    /**
     * Get bill status
     */
    public function getBillStatus(string $billCode): array
    {
        try {
            Log::info('Getting ToyibPay bill status', ['bill_code' => $billCode]);

            $response = Http::asForm()
                ->timeout(30)
                ->post($this->baseUrl . '/index.php/api/getBill', [
                    'billCode' => $billCode
                ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get bill status');
            }

            $responseData = $response->json();

            Log::info('ToyibPay bill status retrieved', [
                'bill_code' => $billCode,
                'response' => $responseData
            ]);

            return $responseData[0] ?? [];

        } catch (\Exception $e) {
            Log::error('Failed to get ToyibPay bill status: ' . $e->getMessage(), [
                'bill_code' => $billCode
            ]);
            throw $e;
        }
    }

    /**
     * Verify callback signature
     */
    public function verifyCallback(array $callbackData): bool
    {
        // ToyibPay callback verification logic
        // This depends on ToyibPay's specific signature verification method
        // For now, we'll do basic validation
        $requiredFields = ['billcode', 'order_id', 'status_id', 'msg'];
        
        foreach ($requiredFields as $field) {
            if (!isset($callbackData[$field])) {
                Log::warning('ToyibPay callback missing required field', [
                    'missing_field' => $field,
                    'callback_data' => $callbackData
                ]);
                return false;
            }
        }

        return true;
    }

    /**
     * Check if payment is successful
     */
    public function isPaymentSuccessful(array $billData): bool
    {
        // Status 1 = Successful payment
        // Status 2 = Pending payment  
        // Status 3 = Failed payment
        return isset($billData['billpaymentStatus']) && $billData['billpaymentStatus'] == '1';
    }
}