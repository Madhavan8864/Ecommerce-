<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Check if admin already exists
        if (!User::where('email', 'admin@ecart.com')->exists()) {
            User::create([
                'name' => 'Admin User',
                'email' => 'admin@ecart.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
            
            $this->command->info('✅ Admin account created:');
            $this->command->info('   👤 Username: admin@ecart.com');
            $this->command->info('   🔑 Password: admin123');
            $this->command->info('   🔗 Login URL: ' . url('/login'));
        } else {
            $this->command->info('⚠️  Admin account already exists.');
        }
    }
}