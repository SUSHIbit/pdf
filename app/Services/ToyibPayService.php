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
                'category_code' => $this->categoryCode,
                'base_url' => $this->baseUrl,
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

            Log::info('ToyibPay bill data being sent', [
                'bill_data' => $billData,
                'url' => $this->baseUrl . '/index.php/api/createBill'
            ]);

            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->asForm()
                ->post($this->baseUrl . '/index.php/api/createBill', $billData);

            Log::info('ToyibPay API response received', [
                'status_code' => $response->status(),
                'headers' => $response->headers(),
                'body' => $response->body(),
                'successful' => $response->successful()
            ]);

            if (!$response->successful()) {
                Log::error('ToyibPay HTTP request failed', [
                    'status_code' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('ToyibPay request failed with status ' . $response->status() . ': ' . $response->body());
            }

            $responseData = $response->json();
            
            Log::info('ToyibPay parsed response', [
                'response_data' => $responseData,
                'is_array' => is_array($responseData),
                'count' => is_array($responseData) ? count($responseData) : 'not_array'
            ]);

            // Check if response is valid
            if (!is_array($responseData) || empty($responseData)) {
                throw new \Exception('Invalid response format from ToyibPay: ' . json_encode($responseData));
            }

            $firstResponse = $responseData[0] ?? null;
            
            if (!$firstResponse) {
                throw new \Exception('Empty response array from ToyibPay');
            }

            Log::info('ToyibPay first response item', [
                'first_response' => $firstResponse
            ]);

            // Check for error status
            if (isset($firstResponse['status']) && $firstResponse['status'] === 'error') {
                $errorMsg = $firstResponse['msg'] ?? 'Unknown error from ToyibPay';
                Log::error('ToyibPay returned error status', [
                    'error_msg' => $errorMsg,
                    'full_response' => $firstResponse
                ]);
                throw new \Exception('ToyibPay error: ' . $errorMsg);
            }

            // Check for BillCode - try different possible field names
            $billCode = null;
            $possibleBillCodeFields = ['BillCode', 'billCode', 'bill_code', 'billcode'];
            
            foreach ($possibleBillCodeFields as $field) {
                if (isset($firstResponse[$field]) && !empty($firstResponse[$field])) {
                    $billCode = $firstResponse[$field];
                    Log::info("Found bill code in field: {$field}", ['bill_code' => $billCode]);
                    break;
                }
            }

            if (!$billCode) {
                Log::error('No bill code found in ToyibPay response', [
                    'available_fields' => array_keys($firstResponse),
                    'full_response' => $firstResponse
                ]);
                throw new \Exception('No bill code returned from ToyibPay. Available fields: ' . implode(', ', array_keys($firstResponse)));
            }

            $result = [
                'bill_code' => $billCode,
                'bill_url' => $this->baseUrl . '/' . $billCode,
                'bill_external_ref' => $billData['billExternalReferenceNo']
            ];

            Log::info('ToyibPay bill created successfully', $result);

            return $result;

        } catch (\Exception $e) {
            Log::error('ToyibPay bill creation failed: ' . $e->getMessage(), [
                'exception_trace' => $e->getTraceAsString(),
                'user_id' => $userId,
                'credits' => $credits,
                'amount' => $amount
            ]);
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

            $response = Http::timeout(30)
                ->retry(3, 1000)
                ->asForm()
                ->post($this->baseUrl . '/index.php/api/getBill', [
                    'billCode' => $billCode
                ]);

            Log::info('ToyibPay getBill response', [
                'status_code' => $response->status(),
                'body' => $response->body(),
                'successful' => $response->successful()
            ]);

            if (!$response->successful()) {
                Log::error('ToyibPay getBill HTTP request failed', [
                    'status_code' => $response->status(),
                    'body' => $response->body(),
                    'bill_code' => $billCode
                ]);
                throw new \Exception('Failed to get bill status from ToyibPay API');
            }

            $responseData = $response->json();

            Log::info('ToyibPay bill status retrieved', [
                'bill_code' => $billCode,
                'response' => $responseData
            ]);

            return $responseData[0] ?? [];

        } catch (\Exception $e) {
            Log::error('Failed to get ToyibPay bill status: ' . $e->getMessage(), [
                'bill_code' => $billCode,
                'error_trace' => $e->getTraceAsString()
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