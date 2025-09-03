<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class CurlConfigurationProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Only apply configuration in local environment
        if ($this->app->environment('local') && env('CURL_CA_BUNDLE_DISABLE', false)) {
            
            // Configure Laravel's HTTP Client globally
            Http::globalOptions([
                'verify' => false,
                'timeout' => 30,
                'connect_timeout' => 10,
            ]);

            // Override Guzzle HTTP client for all instances
            $this->app->bind(\GuzzleHttp\Client::class, function ($app) {
                return new \GuzzleHttp\Client([
                    'verify' => false,
                    'timeout' => 30,
                    'connect_timeout' => 10,
                    'http_errors' => false,
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                ]);
            });

            // Set global cURL options if function exists
            if (function_exists('curl_setopt')) {
                // This will affect new cURL handles created
                ini_set('curl.cainfo', '');
                ini_set('openssl.cafile', '');
            }
        }
    }
}