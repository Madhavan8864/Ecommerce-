<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            // Store intended URL for redirect after login
            if (!$request->is('login', 'register', 'password/*')) {
                session(['url.intended' => $request->fullUrl()]);
            }
            
            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();

        // Check if user account is active
        if (!$user->is_active) {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Your account has been deactivated. Please contact support.');
        }

        // Check if user has verified email (if required)
        if (config('app.require_email_verification') && !$user->hasVerifiedEmail()) {
            if (!$request->is('email/verify*', 'logout')) {
                return redirect()->route('verification.notice')
                    ->with('warning', 'Please verify your email address to continue.');
            }
        }

        // Check if user has completed profile (if required)
        if (config('app.require_profile_completion') && !$user->profile_completed) {
            if (!$request->is('complete-profile*', 'logout')) {
                return redirect()->route('complete.profile')
                    ->with('info', 'Please complete your profile to continue.');
            }
        }

        // Check for maintenance mode
        if (config('app.maintenance_mode') && !$user->hasRole('admin')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'System is under maintenance. Please try again later.'
                ], 503);
            }
            
            return response()->view('maintenance', [], 503);
        }

        // Update last activity
        $this->updateLastActivity($user);

        // Check for suspicious activity
        $this->checkSuspiciousActivity($request, $user);

        // Share user data with all views
        view()->share('currentUser', $user);

        return $next($request);
    }

    /**
     * Update user's last activity timestamp
     */
    protected function updateLastActivity($user)
    {
        // Update only if more than 5 minutes have passed since last update
        if (!$user->last_activity || now()->diffInMinutes($user->last_activity) > 5) {
            $user->update(['last_activity' => now()]);
        }
    }

    /**
     * Check for suspicious activity
     */
    protected function checkSuspiciousActivity(Request $request, $user)
    {
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Check if IP or User-Agent has changed since last login
        $lastLogin = \App\Models\LoginHistory::where('user_id', $user->id)
            ->latest('login_at')
            ->first();

        if ($lastLogin) {
            $ipChanged = $lastLogin->ip_address != $ipAddress;
            $uaChanged = $lastLogin->user_agent != $userAgent;

            if ($ipChanged || $uaChanged) {
                // Log suspicious activity
                \App\Models\SecurityLog::create([
                    'user_id' => $user->id,
                    'type' => 'suspicious_activity',
                    'ip_address' => $ipAddress,
                    'user_agent' => $userAgent,
                    'details' => json_encode([
                        'previous_ip' => $lastLogin->ip_address,
                        'previous_ua' => $lastLogin->user_agent,
                        'changes' => [
                            'ip' => $ipChanged,
                            'user_agent' => $uaChanged
                        ]
                    ]),
                    'created_at' => now()
                ]);

                // Send notification if multiple suspicious activities
                $recentSuspicious = \App\Models\SecurityLog::where('user_id', $user->id)
                    ->where('type', 'suspicious_activity')
                    ->where('created_at', '>', now()->subHours(24))
                    ->count();

                if ($recentSuspicious >= 3) {
                    // Send email notification to user
                    // \App\Notifications\SuspiciousActivityNotification::send($user, [
                    //     'ip' => $ipAddress,
                    //     'user_agent' => $userAgent,
                    //     'time' => now()
                    // ]);
                }
            }
        }
    }

    /**
     * Check if user has required verification for specific actions
     */
    protected function checkVerificationForAction(Request $request, $user)
    {
        $requiresPhoneVerification = [
            'user.checkout.*',
            'user.orders.*',
            'user.wallet.*'
        ];

        $currentRoute = $request->route()->getName();

        foreach ($requiresPhoneVerification as $routePattern) {
            if (\Illuminate\Support\Str::is($routePattern, $currentRoute)) {
                if (!$user->phone_verified_at) {
                    return redirect()->route('verify.phone')
                        ->with('warning', 'Phone verification required for this action.');
                }
                break;
            }
        }
    }

    /**
     * Handle subscription status
     */
    protected function checkSubscriptionStatus($user)
    {
        // If your app has subscription plans
        if ($user->subscription && $user->subscription->isExpired()) {
            if (!$request->is('user/subscription*', 'logout')) {
                return redirect()->route('user.subscription')
                    ->with('warning', 'Your subscription has expired. Please renew to continue.');
            }
        }
    }

    /**
     * Check rate limiting for user actions
     */
    protected function checkRateLimiting(Request $request)
    {
        $userId = Auth::id();
        $action = $request->route()->getName();
        
        $key = "rate_limit:{$userId}:{$action}";
        $limit = config("rate_limits.{$action}", 10); // Default 10 requests per minute
        $decay = 60; // 1 minute

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($key, $limit)) {
            $retryAfter = \Illuminate\Support\Facades\RateLimiter::availableIn($key);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Too many attempts. Please try again in ' . $retryAfter . ' seconds.'
                ], 429);
            }
            
            return back()->withErrors([
                'rate_limit' => 'Too many attempts. Please try again in ' . $retryAfter . ' seconds.'
            ]);
        }

        \Illuminate\Support\Facades\RateLimiter::hit($key, $decay);
    }
}