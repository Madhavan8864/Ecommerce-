<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\ReturnRequest;
use App\Models\ReturnItem;
use Barryvdh\DomPDF\Facade\Pdf; // Add this import for PDF
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Add this import for DB transactions

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to view your orders.');
        }

        $query = Order::where('user_id', Auth::id())
            ->with(['orderItems.product'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        // Search by order number
        if ($request->has('search')) {
            $query->where('order_number', 'like', "%{$request->search}%");
        }

        $orders = $query->paginate(10);

        // Get order statistics
        $orderStats = [
            'total' => Order::where('user_id', Auth::id())->count(),
            'pending' => Order::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'processing' => Order::where('user_id', Auth::id())->where('status', 'processing')->count(),
            'shipped' => Order::where('user_id', Auth::id())->where('status', 'shipped')->count(),
            'delivered' => Order::where('user_id', Auth::id())->where('status', 'delivered')->count(),
            'cancelled' => Order::where('user_id', Auth::id())->where('status', 'cancelled')->count(),
        ];

        return view('user.orders.index', compact('orders', 'orderStats'));
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::where('user_id', Auth::id())
            ->with([
                'orderItems.product',
                'shippingAddress',
                'billingAddress',
                'payment'
            ])
            ->findOrFail($id);

        return view('user.orders.show', compact('order'));
    }

    public function track($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::where('user_id', Auth::id())
            ->with(['orderItems.product', 'payment'])
            ->findOrFail($id);

        // Simulate tracking updates (in real app, integrate with shipping carrier API)
        $trackingUpdates = $this->getTrackingUpdates($order);

        return view('user.orders.track', compact('order', 'trackingUpdates'));
    }

    public function cancel(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'reason' => 'required|string|max:500'
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->whereIn('status', ['pending', 'processing'])
            ->with('orderItems.product') // Add this to load the relationship
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancellation_reason' => $request->reason,
                'cancelled_at' => now()
            ]);

            // Restore product quantities
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                    $item->product->decrement('sold_count', $item->quantity);
                }
            }

            // Update payment status if exists
            if ($order->payment) {
                $order->payment->update(['status' => 'refunded']);
            }

            DB::commit();

            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Order cancelled successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to cancel order: ' . $e->getMessage());
        }
    }

    public function return(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
            'items' => 'required|array',
            'items.*.item_id' => 'required|exists:order_items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $order = Order::where('user_id', Auth::id())
            ->where('id', $id)
            ->where('status', 'delivered')
            ->where('delivered_at', '>=', now()->subDays(30)) // 30-day return policy
            ->firstOrFail();

        DB::beginTransaction();
        try {
            // Create return request
            $return = ReturnRequest::create([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'reason' => $request->reason,
                'status' => 'pending',
                'requested_at' => now()
            ]);

            // Create return items
            foreach ($request->items as $itemData) {
                $orderItem = OrderItem::findOrFail($itemData['item_id']);
                
                if ($orderItem->order_id != $order->id) {
                    continue;
                }

                ReturnItem::create([
                    'return_request_id' => $return->id,
                    'order_item_id' => $orderItem->id,
                    'quantity' => $itemData['quantity'],
                    'status' => 'pending'
                ]);
            }

            // Update order status
            $order->update(['status' => 'return_requested']);

            DB::commit();

            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Return request submitted successfully. We will contact you soon.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to submit return request: ' . $e->getMessage());
        }
    }

    public function downloadInvoice($id)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $order = Order::where('user_id', Auth::id())
            ->with(['orderItems.product', 'user', 'shippingAddress', 'billingAddress', 'payment'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('user.orders.invoice', compact('order'));

        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }

    public function reorder($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $order = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->findOrFail($id);

        $addedCount = 0;
        
        foreach ($order->orderItems as $item) {
            // Check if product is still available
            if (!$item->product || $item->product->quantity < 1 || !$item->product->is_active) {
                continue;
            }

            // Check if product already in cart
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $item->product_id)
                ->first();

            if ($cartItem) {
                $cartItem->increment('quantity', 1);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $item->product_id,
                    'quantity' => 1
                ]);
            }
            
            $addedCount++;
        }

        if ($addedCount > 0) {
            return redirect()->route('user.cart.index')
                ->with('success', $addedCount . ' product(s) added to cart for reorder.');
        } else {
            return redirect()->route('user.orders.show', $order->id)
                ->with('error', 'No products could be added to cart. They may be out of stock or unavailable.');
        }
    }

    private function getTrackingUpdates($order)
    {
        // This is simulated tracking data
        // In real application, integrate with shipping carrier API
        
        $baseDate = clone $order->created_at;
        $updates = [];
        
        // Order Placed
        $updates[] = [
            'status' => 'Order Placed',
            'description' => 'Your order has been received.',
            'date' => $baseDate->format('Y-m-d H:i:s'),
            'location' => 'Online Store',
            'icon' => 'check-circle',
            'completed' => true
        ];

        // Processing
        if (in_array($order->status, ['processing', 'shipped', 'delivered'])) {
            $processingDate = clone $baseDate;
            $processingDate->addHours(2);
            $updates[] = [
                'status' => 'Processing',
                'description' => 'Your order is being prepared for shipment.',
                'date' => $processingDate->format('Y-m-d H:i:s'),
                'location' => 'Warehouse',
                'icon' => 'package',
                'completed' => true
            ];
        } else {
            $updates[] = [
                'status' => 'Processing',
                'description' => 'Your order will be processed soon.',
                'date' => null,
                'location' => 'Pending',
                'icon' => 'package',
                'completed' => false
            ];
        }

        // Shipped
        if (in_array($order->status, ['shipped', 'delivered']) && $order->shipped_at) {
            $updates[] = [
                'status' => 'Shipped',
                'description' => 'Your order has been shipped.',
                'date' => $order->shipped_at->format('Y-m-d H:i:s'),
                'location' => 'Shipping Center',
                'icon' => 'truck',
                'completed' => true
            ];
            
            // In Transit
            if ($order->tracking_number) {
                $transitDate = clone $order->shipped_at;
                $transitDate->addHours(6);
                $updates[] = [
                    'status' => 'In Transit',
                    'description' => 'Your order is on the way.',
                    'date' => $transitDate->format('Y-m-d H:i:s'),
                    'location' => 'In Transit',
                    'icon' => 'map-pin',
                    'completed' => $order->status == 'delivered'
                ];
            }
        } else {
            $updates[] = [
                'status' => 'Shipped',
                'description' => 'Your order will be shipped soon.',
                'date' => null,
                'location' => 'Pending',
                'icon' => 'truck',
                'completed' => false
            ];
        }

        // Delivered
        if ($order->status == 'delivered' && $order->delivered_at) {
            $updates[] = [
                'status' => 'Delivered',
                'description' => 'Your order has been delivered.',
                'date' => $order->delivered_at->format('Y-m-d H:i:s'),
                'location' => 'Delivery Address',
                'icon' => 'home',
                'completed' => true
            ];
        } else {
            $updates[] = [
                'status' => 'Delivered',
                'description' => 'Your order will be delivered soon.',
                'date' => $order->estimated_delivery_date ? $order->estimated_delivery_date->format('Y-m-d') : null,
                'location' => 'Estimated: ' . ($order->estimated_delivery_date ? $order->estimated_delivery_date->format('d M Y') : 'TBD'),
                'icon' => 'home',
                'completed' => false
            ];
        }

        // Cancelled
        if ($order->status == 'cancelled' && $order->cancelled_at) {
            $updates[] = [
                'status' => 'Cancelled',
                'description' => 'Your order has been cancelled.',
                'date' => $order->cancelled_at->format('Y-m-d H:i:s'),
                'location' => $order->cancellation_reason ?? 'Online Store',
                'icon' => 'x-circle',
                'completed' => true
            ];
        }

        return $updates;
    }
}