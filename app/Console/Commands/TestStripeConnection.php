<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stripe\Stripe;
use Stripe\Account;

class TestStripeConnection extends Command
{
    protected $signature = 'stripe:test';
    protected $description = 'Test Stripe API connection';

    public function handle()
    {
        $this->info('Testing Stripe connection...');

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            
            // Test the connection by retrieving account info
            $account = Account::retrieve();
            
            $this->info('✅ Stripe connection successful!');
            $this->line('Account ID: ' . $account->id);
            $this->line('Display Name: ' . $account->display_name);
            $this->line('Country: ' . $account->country);
            $this->line('Currency: ' . $account->default_currency);
            
            if ($account->details_submitted) {
                $this->info('✅ Account is fully set up');
            } else {
                $this->warn('⚠️  Account setup may be incomplete');
            }
            
            $this->newLine();
            $this->info('Test cards you can use:');
            $this->line('Success: 4242 4242 4242 4242');
            $this->line('Decline: 4000 0000 0000 0002');
            $this->line('Use any future date and any 3-digit CVC');

        } catch (\Exception $e) {
            $this->error('❌ Stripe connection failed!');
            $this->error('Error: ' . $e->getMessage());
            
            $this->newLine();
            $this->warn('Check your Stripe keys in .env file:');
            $this->line('STRIPE_KEY=' . substr(config('services.stripe.key'), 0, 20) . '...');
            $this->line('STRIPE_SECRET=' . substr(config('services.stripe.secret'), 0, 20) . '...');
        }

        return 0;
    }
}