<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user');
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        $customers = $query->latest()->paginate(20);
        
        // Statistics for cards
        $stats = [
            'total' => User::where('role', 'user')->count(),
            'active' => User::where('role', 'user')->where('is_active', true)->count(),
            'inactive' => User::where('role', 'user')->where('is_active', false)->count(),
            'verified' => User::where('role', 'user')->whereNotNull('email_verified_at')->count(),
            'unverified' => User::where('role', 'user')->whereNull('email_verified_at')->count(),
            'today' => User::where('role', 'user')->whereDate('created_at', today())->count(),
            'month' => User::where('role', 'user')->whereMonth('created_at', now()->month)->count(),
        ];
        
        return view('admin.customers.index', compact('customers', 'stats'));
    }

    public function delete(User $user)
    {
        if ($user->role !== 'user') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid customer'
            ], 404);
        }
        
        // Check if customer has orders
        if ($user->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete customer with existing orders.'
            ], 400);
        }
        
        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Customer deleted successfully!'
        ]);
    }

    public function export()
    {
        $customers = User::where('role', 'user')->get();
        
        $csvData = "ID,Name,Email,Phone,Status,Verified,Registered Date,Total Orders,Total Spent\n";
        
        foreach ($customers as $customer) {
            $csvData .= implode(',', [
                $customer->id,
                '"' . $customer->name . '"',
                $customer->email,
                $customer->phone ?? 'N/A',
                $customer->is_active ? 'Active' : 'Inactive',
                $customer->email_verified_at ? 'Yes' : 'No',
                $customer->created_at->format('Y-m-d'),
                $customer->orders()->count(),
                $customer->totalSpent()
            ]) . "\n";
        }
        
        $filename = 'customers-' . date('Y-m-d') . '.csv';
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}