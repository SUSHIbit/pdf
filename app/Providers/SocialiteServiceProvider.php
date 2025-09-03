<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\SocialiteServiceProvider as BaseSocialiteServiceProvider;

class SocialiteServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register the base Socialite service provider
        $this->app->register(BaseSocialiteServiceProvider::class);
    }

    public function boot(): void
    {
        // Configure Guzzle HTTP client for local development
        if ($this->app->environment('local')) {
            $this->app->resolving(\GuzzleHttp\Client::class, function ($client, $app) {
                return new \GuzzleHttp\Client([
                    'verify' => false, // Disable SSL verification for local development
                    'timeout' => 30,
                ]);
            });
        }
    }
}