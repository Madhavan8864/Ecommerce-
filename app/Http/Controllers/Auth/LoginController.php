<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetOtp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('user.home');
        }
        
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // Rate limiting
        $this->checkTooManyFailedAttempts($request);

        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
            'remember' => 'nullable|boolean',
        ]);

        // Check if user exists
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            RateLimiter::hit($this->throttleKey($request));
            throw ValidationException::withMessages([
                'email' => 'The provided credentials are incorrect.',
            ]);
        }

        // Check if user is active
        if (!$user->is_active) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been deactivated. Please contact support.',
            ]);
        }

        // Attempt login
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'is_active' => true
        ], $request->boolean('remember'))) {
            
            $request->session()->regenerate();
            
            // Clear login attempts
            RateLimiter::clear($this->throttleKey($request));
            
            // Update last login
            $user->updateLastLogin();
            
            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
            return redirect()->route('user.home')
                ->with('success', 'Welcome back, ' . $user->name . '!');
        }

        // Increment failed attempts
        RateLimiter::hit($this->throttleKey($request));
        
        throw ValidationException::withMessages([
            'email' => 'The provided credentials are incorrect.',
        ]);
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Redirect to Google for authentication
     */
    public function loginWithSocial($provider)
    {
        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Unable to login with ' . ucfirst($provider) . '. Please try again.');
        }
    }

    /**
     * Handle Google callback
     */
    public function socialCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
            
            // Check if user already exists
            $user = User::where('email', $socialUser->email)->first();
            
            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $socialUser->name,
                    'email' => $socialUser->email,
                    'password' => Hash::make(Str::random(16)),
                    'email_verified_at' => now(), // Auto verify email since Google verified it
                    'provider' => $provider,
                    'provider_id' => $socialUser->id,
                    'avatar' => $socialUser->avatar,
                    'is_active' => true,
                    'role' => 'user',
                    'profile_completed' => false,
                ]);
                
                // Send welcome email
                $this->sendWelcomeEmail($user);
            } else {
                // Update existing user with provider info
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->id,
                    'avatar' => $socialUser->avatar,
                ]);
            }
            
            // Login the user
            Auth::login($user, true);
            
            // Update last login
            $user->updateLastLogin();
            
            $request = request();
            $request->session()->regenerate();
            
            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('success', 'Welcome back, ' . $user->name . '!');
            }
            
            return redirect()->route('user.home')
                ->with('success', 'Successfully logged in with ' . ucfirst($provider) . '!');
            
        } catch (\Exception $e) {
            \Log::error('Social login error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Unable to login with ' . ucfirst($provider) . '. Please try again.');
        }
    }

    /**
     * Send welcome email to new user
     */
    private function sendWelcomeEmail($user)
    {
        try {
            $data = [
                'name' => $user->name,
                'email' => $user->email,
                'year' => date('Y')
            ];
            
            Mail::send('emails.welcome', $data, function($message) use ($user) {
                $message->to($user->email)
                        ->subject('Welcome to eCart Electronics!');
                $message->from(
                    config('mail.from.address'),
                    config('mail.from.name')
                );
            });
        } catch (\Exception $e) {
            \Log::error('Welcome email failed: ' . $e->getMessage());
        }
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to email with proper error handling
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        try {
            // Generate 6-digit OTP
            $otp = sprintf("%06d", mt_rand(1, 999999));
            
            // Delete old OTPs for this email
            PasswordResetOtp::where('email', $request->email)->delete();
            
            // Save new OTP
            PasswordResetOtp::create([
                'email' => $request->email,
                'otp' => $otp,
                'created_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // Send OTP via email with error checking
            $mailSent = $this->sendOtpEmail($request->email, $otp);
            
            if (!$mailSent) {
                return back()->with('error', 'Failed to send OTP email. Please check your email configuration or try again later.')
                             ->withInput();
            }

            // Store email in session for verification
            session(['reset_email' => $request->email]);

            return redirect()->route('password.verify.otp')
                ->with('success', 'OTP has been sent to your email. Please check your inbox.');

        } catch (\Exception $e) {
            \Log::error('OTP sending failed: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while sending OTP. Please try again.')
                         ->withInput();
        }
    }

    /**
     * Show OTP verification form
     */
    public function showOtpVerificationForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('password.request')
                ->with('error', 'Session expired. Please try again.');
        }

        return view('auth.verify-otp');
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Session expired. Please try again.');
        }

        // Check OTP
        $otpRecord = PasswordResetOtp::where('email', $email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Invalid or expired OTP. Please try again.');
        }

        // OTP verified - mark as verified in session
        session(['otp_verified' => true]);
        
        // Delete used OTP
        $otpRecord->delete();

        return redirect()->route('password.reset.form')
            ->with('success', 'OTP verified successfully. Now you can reset your password.');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm()
    {
        if (!session('reset_email') || !session('otp_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Please verify OTP first.');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');

        if (!$email || !session('otp_verified')) {
            return redirect()->route('password.request')
                ->with('error', 'Session expired. Please try again.');
        }

        // Update password
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear sessions
        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')
            ->with('success', 'Password reset successfully. Please login with your new password.');
    }

    /**
     * Resend OTP
     */
    public function resendOtp(Request $request)
    {
        $email = session('reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('error', 'Session expired. Please try again.');
        }

        try {
            // Generate new OTP
            $otp = sprintf("%06d", mt_rand(1, 999999));
            
            // Delete old OTPs
            PasswordResetOtp::where('email', $email)->delete();
            
            // Save new OTP
            PasswordResetOtp::create([
                'email' => $email,
                'otp' => $otp,
                'created_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // Send OTP via email
            $mailSent = $this->sendOtpEmail($email, $otp);
            
            if (!$mailSent) {
                return back()->with('error', 'Failed to resend OTP. Please try again.');
            }

            return back()->with('success', 'New OTP has been sent to your email.');

        } catch (\Exception $e) {
            \Log::error('OTP resend failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to resend OTP. Please try again.');
        }
    }

    /**
     * Send OTP Email with detailed error handling and return status
     */
    /**
 * Send OTP Email with detailed error handling and return status
 */
private function sendOtpEmail($email, $otp)
{
    $user = User::where('email', $email)->first();
    
    $data = [
        'name' => $user ? $user->name : 'User',
        'otp' => $otp,
        'expiry' => '10 minutes',
        'year' => date('Y')
    ];

    try {
        // Log all configuration
        \Log::channel('single')->info('=== MAIL DEBUG START ===');
        \Log::channel('single')->info('Time: ' . now());
        \Log::channel('single')->info('To Email: ' . $email);
        \Log::channel('single')->info('Mail Driver: ' . config('mail.default'));
        \Log::channel('single')->info('Mail Host: ' . config('mail.mailers.smtp.host'));
        \Log::channel('single')->info('Mail Port: ' . config('mail.mailers.smtp.port'));
        \Log::channel('single')->info('Mail Username: ' . (config('mail.mailers.smtp.username') ? 'SET' : 'NOT SET'));
        \Log::channel('single')->info('Mail Password: ' . (config('mail.mailers.smtp.password') ? 'SET' : 'NOT SET'));
        \Log::channel('single')->info('Mail Encryption: ' . config('mail.mailers.smtp.encryption'));
        \Log::channel('single')->info('Mail From: ' . config('mail.from.address'));
        \Log::channel('single')->info('Mail From Name: ' . config('mail.from.name'));

        // Send email using Laravel's Mail facade (Symfony Mailer)
        Mail::send('emails.password-otp', $data, function($message) use ($email) {
            $message->to($email)
                    ->subject('Password Reset OTP - eCart Electronics');
            $message->from(
                config('mail.from.address'),
                config('mail.from.name')
            );
        });

        \Log::channel('single')->info('✅ Email sent successfully to: ' . $email);
        \Log::channel('single')->info('=== MAIL DEBUG END ===');
        
        return true;

    } catch (\Symfony\Component\Mailer\Exception\TransportException $e) {
        \Log::channel('single')->error('❌ SMTP Connection Error: ' . $e->getMessage());
        \Log::channel('single')->error('This usually means:');
        \Log::channel('single')->error('- Wrong host/port');
        \Log::channel('single')->error('- Firewall blocking');
        \Log::channel('single')->error('- Wrong username/password');
        \Log::channel('single')->error('- Gmail requires App Password (16 digits)');
        return false;
        
    } catch (\Exception $e) {
        \Log::channel('single')->error('❌ General Error: ' . $e->getMessage());
        \Log::channel('single')->error('Trace: ' . $e->getTraceAsString());
        return false;
    }
}

    /**
     * Debug mail configuration
     */
    public function debugMailConfig()
    {
        try {
            $config = [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'username' => config('mail.mailers.smtp.username') ? 'SET' : 'NOT SET',
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from_address' => config('mail.from.address'),
                'from_name' => config('mail.from.name'),
            ];
            
            return response()->json($config);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }

    /**
     * Test mail sending
     */
    public function testMail()
    {
        try {
            $result = $this->sendOtpEmail(config('mail.from.address'), '123456');
            
            if ($result) {
                return "Test email sent successfully! Check your inbox.";
            } else {
                return "Failed to send test email. Check logs for details.";
            }
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(Request $request): string
    {
        return strtolower($request->input('email')) . '|' . $request->ip();
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function checkTooManyFailedAttempts(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => 'Too many login attempts. Please try again in ' . $seconds . ' seconds.',
        ]);
    }
}