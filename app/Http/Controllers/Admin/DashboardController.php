<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get total statistics
        $totalRevenue = Order::where('status', 'completed')->sum('total_amount');
        $totalOrders = Order::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 'user')->count();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->latest()
            ->take(10)
            ->get();
        
        // FIXED: Added product.id to the select query
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select('products.id', 'products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'DESC')
            ->take(5)
            ->get();
        
        // Get monthly revenue data for chart with month names
        $monthlyRevenue = Order::where('status', 'completed')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('MONTHNAME(created_at) as month_name'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereYear('created_at', date('Y'))
            ->groupBy('month', 'month_name')
            ->orderBy('month')
            ->get();
        
        return view('admin.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalUsers',
            'recentOrders',
            'topProducts',
            'monthlyRevenue'
        ));
    }
}