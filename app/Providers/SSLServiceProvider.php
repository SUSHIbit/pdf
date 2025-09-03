<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class SSLServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Only for local development environment
        if ($this->app->environment('local')) {
            // Override the default Guzzle HTTP client
            $this->app->bind(Client::class, function () {
                return new Client([
                    'verify' => false, // Disable SSL verification
                    'timeout' => 30,
                    'connect_timeout' => 10,
                ]);
            });
        }
    }

    public function boot(): void
    {
        //
    }
}