<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->get('period', 'month');
        
        // Date ranges
        $dates = $this->getDateRange($period);
        
        // Sales Analytics
        $salesData = $this->getSalesAnalytics($dates);
        
        // Product Analytics
        $productData = $this->getProductAnalytics($dates);
        
        // Customer Analytics
        $customerData = $this->getCustomerAnalytics($dates);
        
        // Revenue Analytics
        $revenueData = $this->getRevenueAnalytics($dates);
        
        // Top Products
        $topProducts = $this->getTopProducts($dates);
        
        // Top Categories
        $topCategories = $this->getTopCategories($dates);
        
        // Sales by Day
        $salesByDay = $this->getSalesByDay($dates);
        
        return view('admin.analytics.index', compact(
            'salesData', 'productData', 'customerData', 'revenueData',
            'topProducts', 'topCategories', 'salesByDay', 'period'
        ));
    }
    
    private function getDateRange($period)
    {
        $end = Carbon::now();
        
        switch ($period) {
            case 'week':
                $start = Carbon::now()->subDays(7);
                break;
            case 'month':
                $start = Carbon::now()->subDays(30);
                break;
            case 'quarter':
                $start = Carbon::now()->subDays(90);
                break;
            case 'year':
                $start = Carbon::now()->subDays(365);
                break;
            default:
                $start = Carbon::now()->subDays(30);
        }
        
        return compact('start', 'end');
    }
    
    private function getSalesAnalytics($dates)
    {
        $totalOrders = Order::whereBetween('created_at', [$dates['start'], $dates['end']])->count();
        $completedOrders = Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', 'delivered')
            ->count();
        $cancelledOrders = Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', 'cancelled')
            ->count();
        
        $totalRevenue = Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        $averageOrderValue = $totalOrders > 0 
            ? $totalRevenue / $totalOrders 
            : 0;
        
        return compact('totalOrders', 'completedOrders', 'cancelledOrders', 'totalRevenue', 'averageOrderValue');
    }
    
    private function getProductAnalytics($dates)
    {
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $outOfStock = Product::where('quantity', 0)->count();
        $lowStock = Product::where('quantity', '<=', 10)->where('quantity', '>', 0)->count();
        
        $productsSold = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dates['start'], $dates['end']])
            ->where('orders.status', '!=', 'cancelled')
            ->sum('order_items.quantity');
        
        return compact('totalProducts', 'activeProducts', 'outOfStock', 'lowStock', 'productsSold');
    }
    
    private function getCustomerAnalytics($dates)
    {
        $totalCustomers = User::where('role', 'user')->count();
        $newCustomers = User::where('role', 'user')
            ->whereBetween('created_at', [$dates['start'], $dates['end']])
            ->count();
        
        $activeCustomers = User::where('role', 'user')
            ->whereHas('orders', function($q) use ($dates) {
                $q->whereBetween('created_at', [$dates['start'], $dates['end']]);
            })->count();
        
        $repeatCustomers = User::where('role', 'user')
            ->has('orders', '>', 1)
            ->count();
        
        return compact('totalCustomers', 'newCustomers', 'activeCustomers', 'repeatCustomers');
    }
    
    private function getRevenueAnalytics($dates)
    {
        $revenue = Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        $total = $revenue->sum('revenue');
        $average = $revenue->count() > 0 ? $total / $revenue->count() : 0;
        
        return compact('revenue', 'total', 'average');
    }
    
    private function getTopProducts($dates)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dates['start'], $dates['end']])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_sold', 'DESC')
            ->limit(10)
            ->get();
    }
    
    private function getTopCategories($dates)
    {
        return DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereBetween('orders.created_at', [$dates['start'], $dates['end']])
            ->where('orders.status', '!=', 'cancelled')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.total) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sold', 'DESC')
            ->get();
    }
    
    private function getSalesByDay($dates)
    {
        return Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
    
    public function getChartData(Request $request)
    {
        $period = $request->get('period', 'month');
        $dates = $this->getDateRange($period);
        
        $data = Order::whereBetween('created_at', [$dates['start'], $dates['end']])
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as orders'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}