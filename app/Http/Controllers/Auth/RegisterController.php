<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.home');
        }
        
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'date_of_birth' => 'nullable|date|before_or_equal:' . now()->subYears(13)->format('Y-m-d'),
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'password' => 'required|string|min:6|confirmed',
            'two_factor_enabled' => 'nullable|boolean',
            'terms' => 'required|accepted',
        ], [
            'terms.accepted' => 'You must accept the terms and conditions.',
            'date_of_birth.before_or_equal' => 'You must be at least 13 years old to register.',
            'phone.unique' => 'This phone number is already registered.',
            'email.unique' => 'This email address is already registered.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Prepare user data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_active' => true,
            'email_verified_at' => now(), // Auto verify email
        ];

        // Add optional fields if provided
        if ($request->filled('date_of_birth')) {
            $userData['date_of_birth'] = $request->date_of_birth;
        }

        if ($request->filled('gender')) {
            $userData['gender'] = $request->gender;
        }

        if ($request->filled('address')) {
            $userData['address'] = $request->address;
        }

        if ($request->filled('city')) {
            $userData['city'] = $request->city;
        }

        if ($request->filled('state')) {
            $userData['state'] = $request->state;
        }

        if ($request->filled('zip_code')) {
            $userData['zip_code'] = $request->zip_code;
        }

        if ($request->filled('country')) {
            $userData['country'] = $request->country;
        }

        // Set profile completion status
        $userData['profile_completed'] = $this->calculateProfileCompletion($request);

        // Two-factor authentication
        if ($request->filled('two_factor_enabled') && $request->two_factor_enabled == 1) {
            $userData['two_factor_enabled'] = true;
            $userData['two_factor_type'] = 'email'; // Default to email
        }

        // Create user
        $user = User::create($userData);

        // Login user automatically
        Auth::login($user);

        // Send welcome email (optional)
        // $this->sendWelcomeEmail($user);

        // Redirect based on profile completion
        if (!$user->profile_completed) {
            return redirect()->route('user.profile.edit')
                ->with('success', 'Account created successfully! Please complete your profile.');
        }

        return redirect()->route('user.home')
            ->with('success', 'Account created successfully! Welcome to eCart Electronics.');
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion(Request $request): bool
    {
        $requiredFields = ['name', 'email'];
        $optionalFields = ['phone', 'date_of_birth', 'gender', 'address', 'city', 'state', 'zip_code', 'country'];
        
        $filledFields = 0;
        $totalFields = count($optionalFields);
        
        foreach ($optionalFields as $field) {
            if ($request->filled($field)) {
                $filledFields++;
            }
        }
        
        // Consider profile completed if at least 50% of optional fields are filled
        return ($filledFields / $totalFields) >= 0.5;
    }

    /**
     * Send welcome email (optional)
     */
    private function sendWelcomeEmail(User $user)
    {
        // Implement email sending logic here
        // You can use Laravel Mail or any email service
    }
}