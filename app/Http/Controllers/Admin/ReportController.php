<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function revenueReport(Request $request)
    {
        // Default to last 30 days if no date range specified
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        $query = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Get total revenue
        $totalRevenue = $query->sum('total_amount');
        
        // Get revenue by status
        $revenueByStatus = Order::select('status', DB::raw('SUM(total_amount) as revenue'))
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('status')
            ->get();
        
        // Get daily revenue for chart
        $dailyRevenue = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Get top selling products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'products.name',
                'products.sku',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->orderBy('total_quantity', 'DESC')
            ->take(10)
            ->get();
        
        // Get revenue by category
        $revenueByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'DESC')
            ->get();
        
        // Get new customers
        $newCustomers = User::where('role', 'user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
        
        return view('admin.reports.revenue', compact(
            'totalRevenue',
            'revenueByStatus',
            'dailyRevenue',
            'topProducts',
            'revenueByCategory',
            'newCustomers',
            'startDate',
            'endDate'
        ));
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        
        // Get sales statistics
        $totalOrders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->count();
        
        $completedOrders = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', 'delivered')
            ->count();
        
        $averageOrderValue = Order::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('status', '!=', 'cancelled')
            ->avg('total_amount');
        
        // Get sales by hour (for time-based analysis)
        $salesByHour = Order::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as revenue')
            )
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
        
        // Get top customers
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select(
                'users.name',
                'users.email',
                DB::raw('COUNT(orders.id) as order_count'),
                DB::raw('SUM(orders.total_amount) as total_spent')
            )
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->where('orders.status', '!=', 'cancelled')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'DESC')
            ->take(10)
            ->get();
        
        return view('admin.reports.sales', compact(
            'totalOrders',
            'completedOrders',
            'averageOrderValue',
            'salesByHour',
            'topCustomers',
            'startDate',
            'endDate'
        ));
    }

    public function inventoryReport()
    {
        // Get low stock products
        $lowStockProducts = Product::where('quantity', '<=', 10)
            ->where('quantity', '>', 0)
            ->orderBy('quantity')
            ->get();
        
        // Get out of stock products
        $outOfStockProducts = Product::where('quantity', 0)
            ->where('status', '!=', 'discontinued')
            ->get();
        
        // Get product stock value
        $stockValue = Product::sum(DB::raw('price * quantity'));
        
        // Get products by category stock
        $categoryStock = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(products.id) as product_count'),
                DB::raw('SUM(products.quantity) as total_quantity'),
                DB::raw('SUM(products.price * products.quantity) as stock_value')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('stock_value', 'DESC')
            ->get();
        
        return view('admin.reports.inventory', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'stockValue',
            'categoryStock'
        ));
    }

    public function exportReport(Request $request, $type)
    {
        // This method would handle exporting reports to CSV/Excel
        // Implementation depends on your export library (Laravel Excel, etc.)
        
        return redirect()->back()
            ->with('error', 'Export feature not implemented yet. Please check back soon!');
    }
}