<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.user']);
        
        // Filter by payment status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by payment method
        if ($request->has('method') && $request->method != 'all') {
            $query->where('payment_method', $request->method);
        }
        
        // Filter by date range
        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }
        
        $payments = $query->latest()->paginate(20);
        
        $paymentStats = [
            'total' => Payment::count(),
            'completed' => Payment::where('status', 'completed')->count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'failed' => Payment::where('status', 'failed')->count(),
            'refunded' => Payment::where('status', 'refunded')->count(),
        ];
        
        return view('admin.payments.index', compact('payments', 'paymentStats'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['order.user', 'order.orderItems.product']);
        return view('admin.payments.show', compact('payment'));
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed,refunded'
        ]);
        
        $oldStatus = $payment->status;
        $newStatus = $request->status;
        
        // Update payment status
        $payment->update(['status' => $newStatus]);
        
        // If payment is marked as completed, also update order status to processing
        if ($newStatus == 'completed' && $payment->order) {
            $payment->order->update(['status' => 'processing']);
        }
        
        // If payment is refunded, update order status to cancelled and restore product quantities
        if ($newStatus == 'refunded' && $payment->order) {
            $payment->order->update(['status' => 'cancelled']);
            
            // Restore product quantities
            foreach ($payment->order->orderItems as $item) {
                $item->product->increment('quantity', $item->quantity);
            }
        }
        
        return redirect()->back()
            ->with('success', 'Payment status updated successfully!');
    }

    public function refundPayment(Request $request, Payment $payment)
    {
        if ($payment->status != 'completed') {
            return redirect()->back()
                ->with('error', 'Only completed payments can be refunded!');
        }
        
        $request->validate([
            'refund_amount' => 'required|numeric|min:0.01|max:' . $payment->amount,
            'refund_reason' => 'required|string|max:500'
        ]);
        
        // Here you would integrate with payment gateway for actual refund
        // This is a simulation
        
        $payment->update([
            'status' => 'refunded',
            'refund_amount' => $request->refund_amount,
            'refund_reason' => $request->refund_reason,
            'refunded_at' => now()
        ]);
        
        // Update order status to cancelled
        if ($payment->order) {
            $payment->order->update(['status' => 'cancelled']);
            
            // Restore product quantities
            foreach ($payment->order->orderItems as $item) {
                $item->product->increment('quantity', $item->quantity);
            }
        }
        
        return redirect()->back()
            ->with('success', 'Payment refunded successfully!');
    }
}