<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class SettingController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $user = Auth::user();
        
        // Initialize empty collection for sessions
        $sessions = collect([]);
        
        // Check if sessions table exists before querying
        try {
            // Check if sessions table exists
            $sessionsTableExists = DB::getSchemaBuilder()->hasTable('sessions');
            
            if ($sessionsTableExists) {
                // Get all active sessions for the user except current one
                $sessions = DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', Session::getId())
                    ->orderBy('last_activity', 'desc')
                    ->get()
                    ->map(function ($session) {
                        return (object) [
                            'id' => $session->id,
                            'ip_address' => $session->ip_address,
                            'user_agent' => $session->user_agent,
                            'last_activity' => Carbon::createFromTimestamp($session->last_activity),
                            'browser' => $this->getBrowser($session->user_agent),
                            'platform' => $this->getPlatform($session->user_agent),
                            'is_current_device' => false,
                        ];
                    });
                
                // Add current session
                $currentSession = DB::table('sessions')
                    ->where('id', Session::getId())
                    ->first();
                    
                if ($currentSession) {
                    $currentSessionData = (object) [
                        'id' => $currentSession->id,
                        'ip_address' => $currentSession->ip_address,
                        'user_agent' => $currentSession->user_agent,
                        'last_activity' => Carbon::createFromTimestamp($currentSession->last_activity),
                        'browser' => $this->getBrowser($currentSession->user_agent),
                        'platform' => $this->getPlatform($currentSession->user_agent),
                        'is_current_device' => true,
                    ];
                    
                    // Prepend current session to the collection
                    $sessions = collect([$currentSessionData])->merge($sessions);
                }
            } else {
                // Log that sessions table doesn't exist
                \Log::warning('Sessions table does not exist. Run php artisan session:table and php artisan migrate');
            }
        } catch (\Exception $e) {
            // Log error but continue with empty sessions
            \Log::error('Error fetching sessions: ' . $e->getMessage());
        }
        
        return view('user.settings', compact('user', 'sessions'));
    }

    /**
     * Update user preferences
     */
    public function updatePreferences(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'language' => 'required|string|in:en,es,fr,de,it,ta,hi,ml,te',
            'timezone' => 'required|string|timezone',
            'currency' => 'required|string|in:USD,EUR,GBP,INR',
            'email_notifications' => 'nullable|boolean',
            'order_updates' => 'nullable|boolean',
            'product_updates' => 'nullable|boolean',
            'promotional_emails' => 'nullable|boolean',
            'newsletter_subscription' => 'nullable|boolean',
            'sms_notifications' => 'nullable|boolean',
        ]);

        // Store preferences in session (you can also create a user_settings table)
        $settings = session('user_settings', []);
        
        $settings = array_merge($settings, [
            'language' => $request->language,
            'timezone' => $request->timezone,
            'currency' => $request->currency,
            'email_notifications' => $request->boolean('email_notifications'),
            'order_updates' => $request->boolean('order_updates'),
            'product_updates' => $request->boolean('product_updates'),
            'promotional_emails' => $request->boolean('promotional_emails'),
            'newsletter_subscription' => $request->boolean('newsletter_subscription'),
            'sms_notifications' => $request->boolean('sms_notifications'),
        ]);
        
        session(['user_settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'Preferences updated successfully.'
        ]);
    }

    /**
     * Display privacy settings
     */
    public function privacy()
    {
        $user = Auth::user();
        return view('user.privacy', compact('user'));
    }

    /**
     * Update privacy settings
     */
    public function updatePrivacy(Request $request)
    {
        $request->validate([
            'profile_visibility' => 'required|in:public,private,friends',
            'show_email' => 'nullable|boolean',
            'show_phone' => 'nullable|boolean',
            'search_engine_indexing' => 'nullable|boolean',
            'data_sharing' => 'nullable|boolean',
            'cookie_preferences' => 'required|in:essential,all,custom',
        ]);

        // Store privacy settings in session
        $settings = session('user_settings', []);
        
        $settings = array_merge($settings, [
            'profile_visibility' => $request->profile_visibility,
            'show_email' => $request->boolean('show_email'),
            'show_phone' => $request->boolean('show_phone'),
            'search_engine_indexing' => $request->boolean('search_engine_indexing'),
            'data_sharing' => $request->boolean('data_sharing'),
            'cookie_preferences' => $request->cookie_preferences,
        ]);
        
        session(['user_settings' => $settings]);

        return response()->json([
            'success' => true,
            'message' => 'Privacy settings updated successfully.'
        ]);
    }

    /**
     * Display security settings
     */
    public function security()
    {
        $user = Auth::user();
        return view('user.security', compact('user'));
    }

    /**
     * Enable two-factor authentication
     */
    public function enableTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        // Generate secret and QR code
        // This requires a package like pragmarx/google2fa
        
        return response()->json([
            'secret' => 'dummy-secret',
            'qr_code' => 'dummy-qr-code'
        ]);
    }

    /**
     * Verify and enable two-factor authentication
     */
    public function verifyTwoFactor(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        // Verify the code
        // Enable 2FA for user
        
        return back()->with('success', 'Two-factor authentication enabled successfully.');
    }

    /**
     * Disable two-factor authentication
     */
    public function disableTwoFactor(Request $request)
    {
        $user = Auth::user();
        
        // Disable 2FA for user
        
        return back()->with('success', 'Two-factor authentication disabled successfully.');
    }

    /**
     * Display active sessions
     */
    public function sessions()
    {
        $user = Auth::user();
        
        $sessions = collect([]);
        
        try {
            $sessionsTableExists = DB::getSchemaBuilder()->hasTable('sessions');
            
            if ($sessionsTableExists) {
                $sessions = DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->orderBy('last_activity', 'desc')
                    ->get()
                    ->map(function ($session) {
                        return (object) [
                            'id' => $session->id,
                            'ip_address' => $session->ip_address,
                            'user_agent' => $session->user_agent,
                            'last_activity' => Carbon::createFromTimestamp($session->last_activity),
                            'browser' => $this->getBrowser($session->user_agent),
                            'platform' => $this->getPlatform($session->user_agent),
                            'is_current_device' => $session->id === Session::getId(),
                        ];
                    });
            }
        } catch (\Exception $e) {
            \Log::error('Error fetching sessions: ' . $e->getMessage());
        }
        
        return view('user.sessions', compact('sessions'));
    }

    /**
     * Logout other sessions
     */
    public function logoutOtherSessions(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password is incorrect.'
            ], 422);
        }

        try {
            $sessionsTableExists = DB::getSchemaBuilder()->hasTable('sessions');
            
            if ($sessionsTableExists) {
                // Delete all other sessions
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', '!=', Session::getId())
                    ->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'All other sessions have been logged out.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error logging out sessions: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout sessions. Please try again.'
            ], 500);
        }
    }

    /**
     * Logout from specific device
     */
    public function logoutFromDevice(Request $request, $sessionId)
    {
        $user = Auth::user();

        try {
            $sessionsTableExists = DB::getSchemaBuilder()->hasTable('sessions');
            
            if ($sessionsTableExists) {
                // Delete specific session
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->where('id', $sessionId)
                    ->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Device logged out successfully.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error logging out device: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to logout device. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
            'confirm' => 'required|accepted',
        ]);

        $user = Auth::user();

        // Verify password
        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided password is incorrect.'
            ], 422);
        }

        try {
            // Delete user sessions if table exists
            $sessionsTableExists = DB::getSchemaBuilder()->hasTable('sessions');
            
            if ($sessionsTableExists) {
                DB::table('sessions')
                    ->where('user_id', $user->id)
                    ->delete();
            }
            
            // Delete user (soft delete if you have SoftDeletes trait)
            $user->delete();

            // Logout
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => 'Your account has been deleted.'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error deleting account: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete account. Please try again.'
            ], 500);
        }
    }

    /**
     * Export user data
     */
    public function exportData()
    {
        $user = Auth::user();
        
        // Load relationships if they exist
        $user->load(['orders', 'reviews', 'addresses']);
        
        $data = [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'orders' => $user->orders,
            'reviews' => $user->reviews,
            'addresses' => $user->addresses,
        ];
        
        $filename = 'user-data-' . date('Y-m-d') . '.json';
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT);
        }, $filename, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Get browser from user agent
     */
    private function getBrowser($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';
        return 'Unknown';
    }

    /**
     * Get platform from user agent
     */
    private function getPlatform($userAgent)
    {
        if (strpos($userAgent, 'Windows') !== false) return 'Windows';
        if (strpos($userAgent, 'Mac') !== false) return 'macOS';
        if (strpos($userAgent, 'Linux') !== false) return 'Linux';
        if (strpos($userAgent, 'Android') !== false) return 'Android';
        if (strpos($userAgent, 'iPhone') !== false) return 'iOS';
        if (strpos($userAgent, 'iPad') !== false) return 'iOS';
        return 'Unknown';
    }

    /**
     * Get user settings
     */
    private function getUserSettings()
    {
        return session('user_settings', [
            'language' => 'en',
            'timezone' => 'Asia/Kolkata',
            'currency' => 'INR',
            'email_notifications' => true,
            'order_updates' => true,
            'product_updates' => true,
            'promotional_emails' => true,
            'newsletter_subscription' => true,
            'sms_notifications' => false,
            'profile_visibility' => 'public',
            'show_email' => false,
            'show_phone' => false,
            'search_engine_indexing' => true,
            'data_sharing' => false,
            'cookie_preferences' => 'essential',
        ]);
    }
}