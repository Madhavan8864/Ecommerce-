<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@ecart.com',
            'password' => Hash::make('admin123'),
            'phone' => '9876543210',
            'role' => 'admin',
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'profile_completed' => true,
            'is_active' => true,
            'address' => '123 Admin Street',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'zip_code' => '400001',
            'country' => 'India',
        ]);

        $this->command->info('✅ Admin user created successfully!');
        $this->command->info('📧 Email: admin@ecart.com');
        $this->command->info('🔑 Password: admin123');
    }
}