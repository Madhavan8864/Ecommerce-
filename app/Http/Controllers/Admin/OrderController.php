<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\OrderTrackingUpdatedMail;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderItems.product']);
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        
        // Search by order ID or customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->latest()->paginate(20);
        
        $orderStats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];
        
        return view('admin.orders.index', compact('orders', 'orderStats'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'payment', 'shippingAddress', 'billingAddress']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled'
        ]);
        
        $oldStatus = $order->status;
        $newStatus = $request->status;
        
        // Update order status with timestamps
        $data = ['status' => $newStatus];
        
        if ($newStatus == 'shipped' && $oldStatus != 'shipped') {
            $data['shipped_at'] = now();
        } elseif ($newStatus == 'delivered' && $oldStatus != 'delivered') {
            $data['delivered_at'] = now();
        } elseif ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
            $data['cancelled_at'] = now();
        }
        
        $order->update($data);
        
        // Update product quantities if order is cancelled
        if ($newStatus == 'cancelled' && $oldStatus != 'cancelled') {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->increment('quantity', $item->quantity);
                }
            }
        }
        
        // Reduce product quantities if order moves from pending/cancelled to processing
        if (($newStatus == 'processing' || $newStatus == 'shipped') && 
            ($oldStatus == 'pending' || $oldStatus == 'cancelled')) {
            foreach ($order->orderItems as $item) {
                if ($item->product) {
                    $item->product->decrement('quantity', $item->quantity);
                }
            }
        }
        
        // Send email notification to user about status change
        try {
            Mail::to($order->user->email)->send(new OrderStatusUpdatedMail($order, $oldStatus, $newStatus));
        } catch (\Exception $e) {
            \Log::error('Failed to send order status email: ' . $e->getMessage());
        }
        
        return redirect()->back()
            ->with('success', 'Order status updated to ' . ucfirst($newStatus) . ' successfully!');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:255',
            'shipping_carrier' => 'nullable|string|max:100'
        ]);
        
        $order->update($request->only(['tracking_number', 'tracking_url', 'shipping_carrier']));
        
        // Send email notification to user about tracking info
        try {
            Mail::to($order->user->email)->send(new OrderTrackingUpdatedMail($order));
        } catch (\Exception $e) {
            \Log::error('Failed to send tracking email: ' . $e->getMessage());
        }
        
        return redirect()->back()
            ->with('success', 'Tracking information updated successfully!');
    }

    public function printInvoice(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'payment', 'shippingAddress', 'billingAddress']);
        return view('admin.orders.invoice', compact('order'));
    }
}