<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = [];
        $subtotal = 0;
        $totalItems = 0;

        if (Auth::check()) {
            // For logged-in users, get cart from database
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();
        } else {
            // For guests, get cart from session
            $cart = session()->get('cart', []);
            $productIds = array_keys($cart);
            
            if (!empty($productIds)) {
                $products = Product::whereIn('id', $productIds)
                    ->where('is_active', true)
                    ->where('status', 'in_stock')
                    ->get();
                
                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['quantity'] ?? 1;
                    $cartItems[] = (object)[
                        'id' => $product->id,
                        'product' => $product,
                        'quantity' => $quantity,
                        'price' => $product->discount_price ?? $product->price
                    ];
                }
            }
        }

        // Calculate totals
        foreach ($cartItems as $item) {
            $itemPrice = $item->product->discount_price ?? $item->product->price;
            $subtotal += $itemPrice * $item->quantity;
            $totalItems += $item->quantity;
        }

        // Calculate shipping (example: free for orders over $100)
        $shipping = $subtotal >= 100 ? 0 : 10;
        $tax = $subtotal * 0.08; // 8% tax
        $total = $subtotal + $shipping + $tax;

        return view('user.cart', compact('cartItems', 'subtotal', 'shipping', 'tax', 'total', 'totalItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check product availability
        if ($product->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Only ' . $product->quantity . ' items available in stock.'
            ], 400);
        }

        if (Auth::check()) {
            // For logged-in users
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($cartItem) {
                // Update quantity
                $newQuantity = $cartItem->quantity + $request->quantity;
                if ($newQuantity > $product->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more than available stock.'
                    ], 400);
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity
                ]);
            }
        } else {
            // For guests
            $cart = session()->get('cart', []);

            if (isset($cart[$request->product_id])) {
                $newQuantity = $cart[$request->product_id]['quantity'] + $request->quantity;
                if ($newQuantity > $product->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cannot add more than available stock.'
                    ], 400);
                }
                $cart[$request->product_id]['quantity'] = $newQuantity;
            } else {
                $cart[$request->product_id] = [
                    'quantity' => $request->quantity,
                    'added_at' => now()->timestamp
                ];
            }

            session()->put('cart', $cart);
        }

        // Get updated cart count
        $cartCount = $this->getCartCount();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully!',
            'cart_count' => $cartCount
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10'
        ]);

        if (Auth::check()) {
            $cartItem = Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $product = Product::findOrFail($cartItem->product_id);

            if ($request->quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $product->quantity . ' items available in stock.'
                ], 400);
            }

            $cartItem->update(['quantity' => $request->quantity]);
        } else {
            $cart = session()->get('cart', []);
            
            if (isset($cart[$id])) {
                $product = Product::findOrFail($id);
                
                if ($request->quantity > $product->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Only ' . $product->quantity . ' items available in stock.'
                    ], 400);
                }
                
                $cart[$id]['quantity'] = $request->quantity;
                session()->put('cart', $cart);
            }
        }

        // Recalculate totals
        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'totals' => $totals
        ]);
    }

    public function remove($id)
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())
                ->where('id', $id)
                ->delete();
        } else {
            $cart = session()->get('cart', []);
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        // Get updated cart count and totals
        $cartCount = $this->getCartCount();
        $totals = $this->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from cart!',
            'cart_count' => $cartCount,
            'totals' => $totals
        ]);
    }

    public function clear()
    {
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        } else {
            session()->forget('cart');
        }

        return redirect()->route('user.cart.index')
            ->with('success', 'Cart cleared successfully!');
    }

    public function getCartCount()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->sum('quantity');
        } else {
            $cart = session()->get('cart', []);
            return array_sum(array_column($cart, 'quantity'));
        }
    }

    private function calculateTotals()
    {
        $cartItems = [];
        $subtotal = 0;

        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->get();
        } else {
            $cart = session()->get('cart', []);
            $productIds = array_keys($cart);
            
            if (!empty($productIds)) {
                $products = Product::whereIn('id', $productIds)
                    ->where('is_active', true)
                    ->where('status', 'in_stock')
                    ->get();
                
                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['quantity'] ?? 1;
                    $cartItems[] = (object)[
                        'product' => $product,
                        'quantity' => $quantity
                    ];
                }
            }
        }

        foreach ($cartItems as $item) {
            $itemPrice = $item->product->discount_price ?? $item->product->price;
            $subtotal += $itemPrice * $item->quantity;
        }

        $shipping = $subtotal >= 100 ? 0 : 10;
        $tax = $subtotal * 0.08;
        $total = $subtotal + $shipping + $tax;

        return [
            'subtotal' => number_format($subtotal, 2),
            'shipping' => number_format($shipping, 2),
            'tax' => number_format($tax, 2),
            'total' => number_format($total, 2)
        ];
    }

    public function getMiniCart()
    {
        $cartItems = [];
        $cartCount = 0;
        $cartTotal = 0;

        if (Auth::check()) {
            $cartItems = Cart::where('user_id', Auth::id())
                ->with('product')
                ->latest()
                ->take(3)
                ->get();
            
            $cartCount = $cartItems->sum('quantity');
            
            foreach ($cartItems as $item) {
                $itemPrice = $item->product->discount_price ?? $item->product->price;
                $cartTotal += $itemPrice * $item->quantity;
            }
        } else {
            $cart = session()->get('cart', []);
            $productIds = array_slice(array_keys($cart), 0, 3);
            
            if (!empty($productIds)) {
                $products = Product::whereIn('id', $productIds)
                    ->where('is_active', true)
                    ->where('status', 'in_stock')
                    ->get();
                
                foreach ($products as $product) {
                    $quantity = $cart[$product->id]['quantity'] ?? 1;
                    $cartItems[] = (object)[
                        'product' => $product,
                        'quantity' => $quantity
                    ];
                    $cartCount += $quantity;
                    
                    $itemPrice = $product->discount_price ?? $product->price;
                    $cartTotal += $itemPrice * $quantity;
                }
            }
        }

        return response()->json([
            'success' => true,
            'cart_items' => $cartItems,
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal, 2)
        ]);
    }
}