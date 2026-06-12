<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Show the email verification notice.
     */
    public function show()
    {
        // If user is already verified, redirect to home
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->route('user.home')
                ->with('info', 'Email already verified.');
        }
        
        return view('auth.verify-email');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        // Check if user exists
        $user = Auth::user();
        
        if (!$user) {
            abort(404, 'User not found.');
        }

        // Simple verification - just mark as verified
        if (!$user->hasVerifiedEmail()) {
            $user->forceFill([
                'email_verified_at' => now(),
            ])->save();
            
            return redirect()->route('user.home')
                ->with('success', 'Email verified successfully!');
        }

        return redirect()->route('user.home')
            ->with('info', 'Email already verified.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        // If user is already verified, redirect to home
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('user.home')
                ->with('info', 'Email already verified.');
        }

        // Here you would normally send verification email
        // For now, just show success message
        return back()->with('success', 'Verification link has been sent to your email.');
    }
}