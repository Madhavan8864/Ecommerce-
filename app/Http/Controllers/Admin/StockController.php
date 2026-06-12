<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Display stock management dashboard.
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand']);
        
        // Filter by stock status
        if ($request->has('stock_status') && $request->stock_status != 'all') {
            switch ($request->stock_status) {
                case 'low':
                    $query->where('quantity', '<=', 10)->where('quantity', '>', 0);
                    break;
                case 'out':
                    $query->where('quantity', 0);
                    break;
                case 'in':
                    $query->where('quantity', '>', 10);
                    break;
            }
        }
        
        // Filter by category
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category_id', $request->category);
        }
        
        // Search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        $products = $query->paginate(20);
        
        // Statistics
        $stats = [
            'total_products' => Product::count(),
            'total_stock' => Product::sum('quantity'),
            'low_stock' => Product::where('quantity', '<=', 10)->where('quantity', '>', 0)->count(),
            'out_of_stock' => Product::where('quantity', 0)->count(),
            'total_value' => Product::sum(DB::raw('price * quantity')),
            'in_stock' => Product::where('quantity', '>', 10)->count(),
        ];
        
        $categories = \App\Models\Category::all();
        
        return view('admin.stock.index', compact('products', 'stats', 'categories'));
    }

    /**
     * Display stock movement history.
     */
    public function movements(Request $request)
    {
        $query = StockMovement::with(['product', 'user']);
        
        // Filter by type
        if ($request->has('type') && $request->type != 'all') {
            $query->where('type', $request->type);
        }
        
        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        
        // Search by product
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        $movements = $query->latest()->paginate(20);
        
        $stats = [
            'total_in' => StockMovement::where('type', 'in')->sum('quantity'),
            'total_out' => StockMovement::where('type', 'out')->sum('quantity'),
            'total_adjustments' => StockMovement::where('type', 'adjustment')->count(),
        ];
        
        return view('admin.stock.movements', compact('movements', 'stats'));
    }

    /**
     * Show low stock alerts.
     */
    public function alerts()
    {
        $lowStock = Product::where('quantity', '<=', 10)
            ->where('quantity', '>', 0)
            ->with(['category', 'brand'])
            ->get();
        
        $outOfStock = Product::where('quantity', 0)
            ->with(['category', 'brand'])
            ->get();
        
        $criticalStock = Product::where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->count();
        
        return view('admin.stock.alerts', compact('lowStock', 'outOfStock', 'criticalStock'));
    }

    /**
     * Adjust stock quantity.
     */
    public function adjust(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'reason' => 'required|string|max:255',
            'type' => 'required|in:addition,removal,adjustment'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $oldQuantity = $product->quantity;
        $newQuantity = $oldQuantity + $request->quantity;

        // Prevent negative stock
        if ($newQuantity < 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stock cannot be negative. Current stock: ' . $oldQuantity
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Update product quantity
            $product->update(['quantity' => $newQuantity]);

            // Record stock movement
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'type' => $request->type,
                'quantity' => abs($request->quantity),
                'old_quantity' => $oldQuantity,
                'new_quantity' => $newQuantity,
                'reason' => $request->reason,
                'reference_type' => 'manual_adjustment'
            ]);

            // Update product status based on stock
            if ($newQuantity <= 0) {
                $product->update(['status' => 'out_of_stock']);
            } elseif ($product->status == 'out_of_stock' && $newQuantity > 0) {
                $product->update(['status' => 'in_stock']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully!',
                'new_quantity' => $newQuantity,
                'product_name' => $product->name
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to adjust stock: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk stock update.
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255'
        ]);

        $successCount = 0;
        $failedProducts = [];

        DB::beginTransaction();

        try {
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                $oldQuantity = $product->quantity;
                $newQuantity = $item['quantity'];
                $quantityDiff = $newQuantity - $oldQuantity;

                if ($quantityDiff != 0) {
                    $product->update(['quantity' => $newQuantity]);

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => auth()->id(),
                        'type' => $quantityDiff > 0 ? 'addition' : 'removal',
                        'quantity' => abs($quantityDiff),
                        'old_quantity' => $oldQuantity,
                        'new_quantity' => $newQuantity,
                        'reason' => 'Bulk update: ' . $request->reason,
                        'reference_type' => 'bulk_update'
                    ]);

                    $successCount++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Updated {$successCount} products successfully!",
                'failed' => $failedProducts
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Bulk update failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export stock report.
     */
    public function export(Request $request)
    {
        $products = Product::with(['category', 'brand'])->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="stock-report-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($products) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'SKU',
                'Product Name',
                'Category',
                'Brand',
                'Current Stock',
                'Price',
                'Stock Value',
                'Status',
                'Last Updated'
            ]);

            // Data rows
            foreach ($products as $product) {
                fputcsv($file, [
                    $product->sku,
                    $product->name,
                    $product->category->name ?? 'N/A',
                    $product->brand->name ?? 'N/A',
                    $product->quantity,
                    $product->price,
                    $product->quantity * $product->price,
                    $product->stock_status,
                    $product->updated_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get stock movement chart data.
     */
    public function getChartData()
    {
        $movements = StockMovement::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(CASE WHEN type = "in" THEN quantity ELSE 0 END) as stock_in'),
            DB::raw('SUM(CASE WHEN type = "out" THEN quantity ELSE 0 END) as stock_out')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $movements
        ]);
    }
}