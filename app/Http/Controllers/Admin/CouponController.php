<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of coupons.
     */
    public function index(Request $request)
    {
        $query = Coupon::query();
        
        // Search by code
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('code', 'like', "%{$search}%");
        }
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            if ($request->status == 'active') {
                $query->where('status', 'active')
                      ->where(function($q) {
                          $q->whereNull('expires_at')
                            ->orWhere('expires_at', '>', now());
                      });
            } elseif ($request->status == 'expired') {
                $query->where('expires_at', '<', now());
            } else {
                $query->where('status', $request->status);
            }
        }
        
        // Filter by type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        $coupons = $query->latest()->paginate(20);
        
        // Statistics
        $stats = [
            'total' => Coupon::count(),
            'active' => Coupon::where('status', 'active')
                        ->where(function($q) {
                            $q->whereNull('expires_at')
                              ->orWhere('expires_at', '>', now());
                        })->count(),
            'expired' => Coupon::where('expires_at', '<', now())->count(),
            'used' => Coupon::sum('used_count'),
        ];
        
        return view('admin.coupons.index', compact('coupons', 'stats'));
    }

    /**
     * Show the form for creating a new coupon.
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:coupons|max:50',
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);
        
        // Set used_count to 0 for new coupons
        $validated['used_count'] = 0;

        Coupon::create($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully!');
    }

    /**
     * Display the specified coupon.
     */
    public function show(Coupon $coupon)
    {
        // Load coupon usage statistics
        $usageStats = [
            'total_used' => $coupon->used_count,
            'remaining' => $coupon->usage_limit ? $coupon->usage_limit - $coupon->used_count : 'Unlimited',
            'is_valid' => $coupon->isValid(),
            'orders' => $coupon->orders()->with('user')->latest()->paginate(10)
        ];
        
        return view('admin.coupons.show', compact('coupon', 'usageStats'));
    }

    /**
     * Show the form for editing the specified coupon.
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'type' => 'required|in:percentage,fixed,free_shipping',
            'value' => 'required|numeric|min:0',
            'min_amount' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive'
        ]);

        // Convert code to uppercase
        $validated['code'] = strtoupper($validated['code']);

        $coupon->update($validated);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully!');
    }

    /**
     * Remove the specified coupon from storage.
     */
    public function destroy(Coupon $coupon)
    {
        // Check if coupon has been used in orders
        if ($coupon->used_count > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete coupon that has been used in orders.'
            ], 400);
        }

        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully!'
        ]);
    }

    /**
     * Toggle coupon status (active/inactive).
     */
    public function toggleStatus(Coupon $coupon)
    {
        $newStatus = $coupon->status == 'active' ? 'inactive' : 'active';
        
        $coupon->update([
            'status' => $newStatus
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon status updated to ' . $newStatus . '!',
            'status' => $newStatus
        ]);
    }

    /**
     * Generate a unique coupon code.
     */
    public function generateCode()
    {
        $length = 8;
        $code = strtoupper(Str::random($length));
        
        // Ensure code is unique
        while (Coupon::where('code', $code)->exists()) {
            $code = strtoupper(Str::random($length));
        }

        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }

    /**
     * Validate a coupon code (AJAX endpoint).
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        if (!$coupon->isValid($request->subtotal)) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon is not applicable.'
            ]);
        }

        $discount = $coupon->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'coupon' => $coupon,
            'discount' => $discount,
            'message' => 'Coupon applied successfully!'
        ]);
    }

    /**
     * Export coupons to CSV.
     */
    public function export()
    {
        $coupons = Coupon::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="coupons-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($coupons) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, [
                'ID',
                'Code',
                'Type',
                'Value',
                'Min Amount',
                'Max Discount',
                'Usage Limit',
                'Per User Limit',
                'Used Count',
                'Start Date',
                'Expiry Date',
                'Status',
                'Description',
                'Created At'
            ]);

            // Add data rows
            foreach ($coupons as $coupon) {
                fputcsv($file, [
                    $coupon->id,
                    $coupon->code,
                    $coupon->type,
                    $coupon->value,
                    $coupon->min_amount ?? 0,
                    $coupon->max_discount ?? 0,
                    $coupon->usage_limit ?? 'Unlimited',
                    $coupon->per_user_limit ?? 'Unlimited',
                    $coupon->used_count,
                    $coupon->starts_at ? $coupon->starts_at->format('Y-m-d H:i:s') : '',
                    $coupon->expires_at ? $coupon->expires_at->format('Y-m-d H:i:s') : '',
                    $coupon->status,
                    $coupon->description ?? '',
                    $coupon->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Duplicate a coupon.
     */
    public function duplicate(Coupon $coupon)
    {
        $newCoupon = $coupon->replicate();
        $newCoupon->code = $coupon->code . '-COPY';
        $newCoupon->used_count = 0;
        $newCoupon->status = 'inactive';
        
        // Ensure unique code
        $counter = 1;
        while (Coupon::where('code', $newCoupon->code)->exists()) {
            $newCoupon->code = $coupon->code . '-COPY-' . $counter;
            $counter++;
        }
        
        $newCoupon->save();

        return redirect()->route('admin.coupons.edit', $newCoupon->id)
            ->with('success', 'Coupon duplicated successfully!');
    }

    /**
     * Bulk action on coupons.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:coupons,id'
        ]);

        $count = 0;
        $coupons = Coupon::whereIn('id', $request->ids)->get();

        foreach ($coupons as $coupon) {
            switch ($request->action) {
                case 'activate':
                    if ($coupon->status != 'active') {
                        $coupon->update(['status' => 'active']);
                        $count++;
                    }
                    break;
                    
                case 'deactivate':
                    if ($coupon->status != 'inactive') {
                        $coupon->update(['status' => 'inactive']);
                        $count++;
                    }
                    break;
                    
                case 'delete':
                    if ($coupon->used_count == 0) {
                        $coupon->delete();
                        $count++;
                    }
                    break;
            }
        }

        $message = $count > 0 
            ? "{$count} coupons {$request->action}d successfully!" 
            : "No coupons were {$request->action}d.";

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count
        ]);
    }

    /**
     * Get coupon statistics for dashboard.
     */
    public function getStats()
    {
        $totalCoupons = Coupon::count();
        $activeCoupons = Coupon::where('status', 'active')
                            ->where(function($q) {
                                $q->whereNull('expires_at')
                                  ->orWhere('expires_at', '>', now());
                            })->count();
        
        $totalUsage = Coupon::sum('used_count');
        $totalDiscount = 0; // You would need to calculate this from orders
        
        $recentCoupons = Coupon::latest()->take(5)->get();

        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $totalCoupons,
                'active' => $activeCoupons,
                'usage' => $totalUsage,
                'discount' => $totalDiscount
            ],
            'recent' => $recentCoupons
        ]);
    }

    /**
     * Check if coupon is valid for current cart.
     */
    public function checkValidity(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|numeric|min:0',
            'user_id' => 'nullable|exists:users,id'
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        // Check basic validity
        if (!$coupon->isValid($request->subtotal)) {
            return response()->json([
                'valid' => false,
                'message' => 'Coupon is not applicable to this order.'
            ]);
        }

        // Check per-user limit if user is specified
        if ($request->user_id && $coupon->per_user_limit) {
            $userUsage = Order::where('user_id', $request->user_id)
                            ->where('coupon_code', $coupon->code)
                            ->count();
            
            if ($userUsage >= $coupon->per_user_limit) {
                return response()->json([
                    'valid' => false,
                    'message' => 'You have already used this coupon maximum times.'
                ]);
            }
        }

        $discount = $coupon->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'discount' => $discount,
                'description' => $coupon->description
            ],
            'message' => 'Coupon is valid!'
        ]);
    }
}