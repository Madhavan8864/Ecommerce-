<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmationMail;

class CheckoutController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to checkout.');
        }

        // Get cart items
        $cartItems = Cart::where('user_id', Auth::id())
            ->with(['product' => function($query) {
                $query->where('is_active', true)
                      ->where('status', 'in_stock');
            }])
            ->get();

        // Filter out any products that are not active or out of stock
        $cartItems = $cartItems->filter(function($item) {
            return $item->product !== null;
        });

        if ($cartItems->isEmpty()) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Your cart is empty.');
        }

        // Check stock availability
        $outOfStockItems = [];
        foreach ($cartItems as $item) {
            if ($item->product->quantity < $item->quantity) {
                $outOfStockItems[] = $item->product->name . ' (Available: ' . $item->product->quantity . ')';
            }
        }

        if (!empty($outOfStockItems)) {
            return redirect()->route('user.cart.index')
                ->with('error', 'Some products are out of stock or have insufficient quantity: ' . implode(', ', $outOfStockItems));
        }

        // Calculate totals
        $subtotal = 0;
        $totalItems = 0;
        
        foreach ($cartItems as $item) {
            $itemPrice = $item->product->discount_price ?? $item->product->price;
            $subtotal += $itemPrice * $item->quantity;
            $totalItems += $item->quantity;
        }

        // Calculate shipping (free for orders over ₹1000)
        $shipping = $subtotal >= 1000 ? 0 : 50;
        
        // Calculate tax (8% GST)
        $tax = $subtotal * 0.08;
        
        // Calculate total
        $total = $subtotal + $shipping + $tax;

        // Get user addresses
        $user = Auth::user();
        $addresses = Address::where('user_id', Auth::id())->get();

        // Check if user has default addresses
        $defaultShippingAddress = $addresses->where('type', 'shipping')->where('is_default', true)->first();
        $defaultBillingAddress = $addresses->where('type', 'billing')->where('is_default', true)->first();
        
        // If no default address, get first address
        if (!$defaultShippingAddress && $addresses->count() > 0) {
            $defaultShippingAddress = $addresses->first();
        }
        
        if (!$defaultBillingAddress && $addresses->count() > 0) {
            $defaultBillingAddress = $addresses->first();
        }

        // Get applied coupon from session
        $appliedCoupon = Session::get('applied_coupon');

        return view('user.checkout', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'tax',
            'total',
            'totalItems',
            'user',
            'addresses',
            'defaultShippingAddress',
            'defaultBillingAddress',
            'appliedCoupon'
        ));
    }

    public function process(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $request->validate([
            'shipping_address_id' => 'required|exists:addresses,id',
            'billing_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod,card,paypal,stripe,razorpay',
            'notes' => 'nullable|string|max:500',
            'coupon_code' => 'nullable|string'
        ]);

        // Start transaction
        DB::beginTransaction();

        try {
            // Get cart items
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();

            if ($cartItems->isEmpty()) {
                throw new \Exception('Your cart is empty.');
            }

            // Calculate totals
            $subtotal = 0;
            $orderItemsData = [];
            
            foreach ($cartItems as $item) {
                if (!$item->product) {
                    throw new \Exception('Some products are no longer available.');
                }
                
                $itemPrice = $item->product->discount_price ?? $item->product->price;
                $subtotal += $itemPrice * $item->quantity;
                
                // Check stock
                if ($item->product->quantity < $item->quantity) {
                    throw new \Exception('Product "' . $item->product->name . '" is out of stock or has insufficient quantity.');
                }
                
                // Store item data for later use
                $orderItemsData[] = [
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                    'price' => $itemPrice,
                    'total' => $itemPrice * $item->quantity
                ];
            }

            $shipping = $subtotal >= 1000 ? 0 : 50;
            $tax = $subtotal * 0.08;
            $total = $subtotal + $shipping + $tax;

            // Apply coupon discount if exists
            $discountAmount = 0;
            $appliedCoupon = Session::get('applied_coupon');
            
            if ($appliedCoupon) {
                if ($appliedCoupon['type'] == 'percentage') {
                    $discountAmount = ($subtotal * $appliedCoupon['value']) / 100;
                } elseif ($appliedCoupon['type'] == 'fixed') {
                    $discountAmount = $appliedCoupon['value'];
                } elseif ($appliedCoupon['type'] == 'shipping') {
                    $shipping = 0;
                }
                
                $total = $subtotal - $discountAmount + $shipping + $tax;
            }

            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(uniqid());

            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'shipping_address_id' => $request->shipping_address_id,
                'billing_address_id' => $request->billing_address_id,
                'subtotal' => $subtotal,
                'shipping_cost' => $shipping,
                'tax' => $tax,
                'total_amount' => $total,
                'discount_amount' => $discountAmount,
                'coupon_code' => $appliedCoupon['code'] ?? null,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            // Create order items and update product quantities
            foreach ($cartItems as $item) {
                $itemPrice = $item->product->discount_price ?? $item->product->price;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $itemPrice,
                    'total' => $itemPrice * $item->quantity
                ]);

                // Update product quantity and sold count
                $item->product->decrement('quantity', $item->quantity);
                $item->product->increment('sold_count', $item->quantity);
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $total,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method == 'cod' ? 'pending' : 'pending',
                'transaction_id' => null,
                'payment_details' => null,
                'currency' => 'INR'
            ]);

            // Clear cart
            Cart::where('user_id', Auth::id())->delete();
            
            // Clear coupon from session
            Session::forget('applied_coupon');

            DB::commit();

            // ============================================
            // SEND ORDER CONFIRMATION EMAIL
            // ============================================
            try {
                // Load the order with all necessary relationships for email
                $order->load(['user', 'shippingAddress', 'billingAddress', 'orderItems.product']);
                
                // Send email
                Mail::to($order->user->email)->send(new OrderConfirmationMail($order));
                
                // Log success
                \Log::info('Order confirmation email sent successfully to: ' . $order->user->email . ' for order: ' . $order->order_number);
                
            } catch (\Exception $e) {
                // Log email error but don't stop the order process
                \Log::error('Failed to send order confirmation email: ' . $e->getMessage());
                \Log::error('Order ID: ' . $order->id . ', Order Number: ' . $order->order_number);
            }

            // Handle payment based on method
            if ($request->payment_method == 'cod') {
                return redirect()->route('user.orders.show', $order->id)
                    ->with('success', 'Order placed successfully! A confirmation email has been sent to your email address. Payment will be collected on delivery.');
            } else {
                // Redirect to payment gateway
                return $this->processOnlinePayment($order, $payment, $request->payment_method);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Order failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function processOnlinePayment($order, $payment, $method)
    {
        // This is a simplified version. In real application, integrate with payment gateways
        
        switch ($method) {
            case 'razorpay':
                // Store order ID in session for Razorpay callback
                Session::put('payment_order_id', $order->id);
                return redirect()->route('payment.razorpay')->with('info', 'Please complete your payment. A confirmation email will be sent after successful payment.');
                
            case 'stripe':
                Session::put('payment_order_id', $order->id);
                return redirect()->route('payment.stripe')->with('info', 'Please complete your payment. A confirmation email will be sent after successful payment.');
                
            case 'paypal':
                Session::put('payment_order_id', $order->id);
                return redirect()->route('payment.paypal')->with('info', 'Please complete your payment. A confirmation email will be sent after successful payment.');
                
            case 'card':
                // For demo purposes, mark as completed
                $payment->update([
                    'status' => 'completed',
                    'transaction_id' => 'DEMO-' . uniqid(),
                    'paid_at' => now()
                ]);
                
                $order->update([
                    'payment_status' => 'completed',
                    'status' => 'processing'
                ]);
                
                // Send another email for payment confirmation (optional)
                try {
                    // You can create a PaymentConfirmationMail class if needed
                    // Mail::to($order->user->email)->send(new PaymentConfirmationMail($order));
                } catch (\Exception $e) {
                    \Log::error('Failed to send payment confirmation email: ' . $e->getMessage());
                }
                
                return redirect()->route('user.orders.show', $order->id)
                    ->with('success', 'Payment successful! A confirmation email has been sent to your email address. Order is being processed.');
                
            default:
                return redirect()->route('user.orders.show', $order->id)
                    ->with('info', 'Order placed successfully. Please complete payment. A confirmation email will be sent after payment confirmation.');
        }
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string'
        ]);

        // In real application, you would validate coupon from database
        // This is a simplified version for demo
        
        $coupons = [
            'SAVE10' => ['type' => 'percentage', 'value' => 10, 'min_amount' => 500],
            'SAVE20' => ['type' => 'percentage', 'value' => 20, 'min_amount' => 1000],
            'FREESHIP' => ['type' => 'shipping', 'value' => 0, 'min_amount' => 0],
            'FLAT50' => ['type' => 'fixed', 'value' => 50, 'min_amount' => 200],
            'WELCOME' => ['type' => 'percentage', 'value' => 15, 'min_amount' => 0],
            'FIRST100' => ['type' => 'fixed', 'value' => 100, 'min_amount' => 500],
        ];

        $couponCode = strtoupper($request->coupon_code);
        
        if (!isset($coupons[$couponCode])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.'
            ]);
        }

        $coupon = $coupons[$couponCode];

        // Get cart total for validation
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->product->discount_price ?? $item->product->price;
            $subtotal += $itemPrice * $item->quantity;
        }

        if ($subtotal < $coupon['min_amount']) {
            return response()->json([
                'success' => false,
                'message' => 'Minimum order amount for this coupon is ₹' . $coupon['min_amount']
            ]);
        }

        // Calculate discount
        $discount = 0;
        $shipping = $subtotal >= 1000 ? 0 : 50;
        $shippingDiscount = 0;
        
        if ($coupon['type'] == 'percentage') {
            $discount = ($subtotal * $coupon['value']) / 100;
        } elseif ($coupon['type'] == 'fixed') {
            $discount = $coupon['value'];
        } elseif ($coupon['type'] == 'shipping') {
            $shippingDiscount = $shipping;
            $shipping = 0;
        }

        $tax = ($subtotal - $discount) * 0.08;
        $total = ($subtotal - $discount) + $shipping + $tax;

        // Store coupon in session
        Session::put('applied_coupon', [
            'code' => $couponCode,
            'type' => $coupon['type'],
            'value' => $coupon['value'],
            'discount' => $discount,
            'shipping_discount' => $shippingDiscount
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'coupon' => [
                'code' => $couponCode,
                'discount' => number_format($discount, 2)
            ],
            'totals' => [
                'subtotal' => number_format($subtotal, 2),
                'discount' => number_format($discount, 2),
                'shipping' => number_format($shipping, 2),
                'tax' => number_format($tax, 2),
                'total' => number_format($total, 2)
            ]
        ]);
    }

    public function removeCoupon()
    {
        Session::forget('applied_coupon');
        
        // Recalculate totals without coupon
        $cartItems = Cart::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $subtotal = 0;
        foreach ($cartItems as $item) {
            $itemPrice = $item->product->discount_price ?? $item->product->price;
            $subtotal += $itemPrice * $item->quantity;
        }

        $shipping = $subtotal >= 1000 ? 0 : 50;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed.',
            'totals' => [
                'subtotal' => number_format($subtotal, 2),
                'discount' => '0.00',
                'shipping' => number_format($shipping, 2),
                'tax' => number_format($tax, 2),
                'total' => number_format($total, 2)
            ]
        ]);
    }

    public function addAddress(Request $request)
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

        $validated['user_id'] = Auth::id();
        $validated['is_default'] = $request->has('is_default');

        // If this is set as default, unset others
        if ($validated['is_default']) {
            Address::where('user_id', Auth::id())
                ->where('type', $validated['type'])
                ->update(['is_default' => false]);
        }

        $address = Address::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Address added successfully!',
            'address' => $address
        ]);
    }

    public function validateAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string|min:10',
            'city' => 'required|string',
            'state' => 'required|string',
            'zip_code' => 'required|string',
            'country' => 'required|string'
        ]);

        // Simple validation - in real app, integrate with address validation service
        $isValid = true;
        $message = 'Address is valid.';

        // Basic ZIP code validation (example for India)
        if ($request->country == 'India' && !preg_match('/^[1-9][0-9]{5}$/', $request->zip_code)) {
            $isValid = false;
            $message = 'Invalid Indian PIN code. Please enter a valid 6-digit PIN code.';
        }

        return response()->json([
            'success' => $isValid,
            'message' => $message
        ]);
    }
}