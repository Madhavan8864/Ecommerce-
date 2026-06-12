<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Record logout time if user was logged in
        if ($user) {
            $this->recordLogoutHistory($user);
            
            // If user has 2FA enabled, clear any 2FA session data
            if ($user->two_factor_enabled) {
                $this->clearTwoFactorSession();
            }
        }

        // Logout user
        Auth::logout();

        // Invalidate session
        $request->session()->invalidate();

        // Regenerate CSRF token
        $request->session()->regenerateToken();

        // Redirect to home page
        return redirect()->route('home')
            ->with('success', 'You have been logged out successfully.');
    }

    public function logoutFromAllDevices(Request $request)
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Get current session ID
        $currentSessionId = Session::getId();

        // Delete all other sessions
        \DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', '!=', $currentSessionId)
            ->delete();

        // Record logout history
        $this->recordLogoutHistory($user);

        return response()->json([
            'success' => true,
            'message' => 'Logged out from all other devices.'
        ]);
    }

    public function logoutFromDevice($sessionId)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.'
            ], 401);
        }

        // Prevent logging out from current device
        if ($sessionId === Session::getId()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot logout from current device.'
            ], 400);
        }

        // Delete specific session
        \DB::table('sessions')
            ->where('user_id', $user->id)
            ->where('id', $sessionId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Device logged out successfully.'
        ]);
    }

    protected function recordLogoutHistory($user)
    {
        // Update last login history record with logout time
        \App\Models\LoginHistory::where('user_id', $user->id)
            ->whereNull('logout_at')
            ->latest('login_at')
            ->first()
            ?->update(['logout_at' => now()]);
    }

    protected function clearTwoFactorSession()
    {
        Session::forget([
            '2fa_user_id',
            '2fa_code',
            '2fa_expires_at',
            '2fa_remember'
        ]);
    }

    public function showConfirmationForm()
    {
        if (!Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.logout-confirm');
    }

    public function confirmLogout(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $user = Auth::user();

        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        return $this->logout($request);
    }

    public function autoLogout()
    {
        // This would be called by a scheduled task or middleware
        // to automatically logout inactive users
        
        $inactiveTime = config('session.lifetime') * 60; // in seconds
        
        \DB::table('sessions')
            ->where('last_activity', '<', now()->subSeconds($inactiveTime))
            ->delete();

        // You could also update login history records
        \App\Models\LoginHistory::whereNull('logout_at')
            ->where('login_at', '<', now()->subSeconds($inactiveTime))
            ->update(['logout_at' => now()]);
    }
}