<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $user->load(['addresses', 'orders' => function($query) {
            $query->latest()->take(5);
        }]);

        return view('user.profile', compact('user'));
    }

    public function update(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $avatarPath;
        }

        $user->update($validated);

        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required'
        ]);

        $user = Auth::user();

        // Verify current password
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Current password is incorrect.');
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('user.profile')
            ->with('success', 'Password updated successfully!');
    }

    public function updateAddress(Request $request, $id = null)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'type' => 'required|in:shipping,billing,both',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean'
        ]);

        $user = Auth::user();

        if ($id) {
            // Update existing address
            $address = $user->addresses()->findOrFail($id);
            $address->update($validated);
        } else {
            // Create new address
            $validated['user_id'] = $user->id;
            \App\Models\Address::create($validated);
        }

        // If this is set as default, unset others
        if ($request->is_default) {
            $user->addresses()
                ->where('id', '!=', $id ?: 0)
                ->where('type', $validated['type'])
                ->update(['is_default' => false]);
        }

        return redirect()->route('user.profile')
            ->with('success', 'Address ' . ($id ? 'updated' : 'added') . ' successfully!');
    }

    public function deleteAddress($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $address = $user->addresses()->findOrFail($id);

        // Check if address is used in orders
        $usedInOrders = \App\Models\Order::where('user_id', $user->id)
            ->where(function($query) use ($id) {
                $query->where('shipping_address_id', $id)
                      ->orWhere('billing_address_id', $id);
            })
            ->exists();

        if ($usedInOrders) {
            return redirect()->back()
                ->with('error', 'Cannot delete address that is used in existing orders.');
        }

        $address->delete();

        return redirect()->route('user.profile')
            ->with('success', 'Address deleted successfully!');
    }

    public function notifications()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $notifications = $user->notifications()
            ->latest()
            ->paginate(20);

        return view('user.notifications', compact('notifications'));
    }

    public function markNotificationAsRead($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();

        return redirect()->back()
            ->with('success', 'Notification marked as read.');
    }

    public function markAllNotificationsAsRead()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return redirect()->back()
            ->with('success', 'All notifications marked as read.');
    }

    public function deleteNotification($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notification deleted.');
    }

    public function activity()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Get user activities (you need to implement activity logging)
        $activities = \App\Models\UserActivity::where('user_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('user.activity', compact('activities'));
    }
}