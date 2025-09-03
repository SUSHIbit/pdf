<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Stripe\Exception\ApiErrorException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        // Handle Stripe API errors
        $this->renderable(function (ApiErrorException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Payment processing error',
                    'message' => 'Unable to process payment. Please try again.'
                ], 422);
            }

            return redirect()->route('payment.packages')
                ->with('error', 'Payment processing error. Please try again.');
        });
    }
}