<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminUser::create([
            'name' => 'Admin Gorgon',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'is_active' => true,
        ]);

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@gmail.com');
        $this->command->info('Password: 123456789');
    }
}