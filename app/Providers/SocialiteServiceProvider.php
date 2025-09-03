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
        // Configuration is now handled by CurlConfigurationProvider
        // This provider now only registers Socialite
    }
}