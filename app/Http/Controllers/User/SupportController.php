<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SupportController extends Controller
{
    /**
     * Display support page
     */
    public function index()
    {
        return view('user.support.index');
    }

    /**
     * Send support message
     */
    public function send(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $user = Auth::user();

        try {
            // Send email to support team
            Mail::send('emails.support', [
                'user' => $user,
                'subject' => $request->subject,
                'message' => $request->message
            ], function ($mail) use ($request, $user) {
                $mail->to(config('mail.support_address', 'support@ecart.com'))
                     ->subject('Support Request: ' . $request->subject)
                     ->replyTo($user->email, $user->name);
            });

            // You can also save to database if needed
            // SupportTicket::create([...]);

            return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
        } catch (\Exception $e) {
            \Log::error('Support email failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send message. Please try again later.');
        }
    }
}