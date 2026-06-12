<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear users table
        \DB::table('users')->truncate();
        
        // Enable foreign key checks
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Create admin user directly here
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
        $this->command->info('📧 Login with: admin@ecart.com');
        $this->command->info('🔑 Password: admin123');
        $this->command->info('🔗 Admin URL: ' . url('/admin/login'));
    }
}