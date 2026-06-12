<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to view your wishlist.');
        }

        $wishlistItems = Wishlist::where('user_id', Auth::id())
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'slug', 'price', 'discount_price', 'main_image', 'status', 'is_active', 'quantity');
            }])
            ->latest()
            ->paginate(12);

        return view('user.wishlist', compact('wishlistItems'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to wishlist.',
                'requires_login' => true
            ], 401);
        }

        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product already in wishlist
        $existing = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in your wishlist.'
            ], 400);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist!',
            'wishlist_count' => $wishlistCount
        ]);
    }

    public function remove($id)
{
    if (!Auth::check()) {
        return redirect()->route('login')
            ->with('error', 'Please login to manage wishlist.');
    }

    $wishlistItem = Wishlist::where('user_id', Auth::id())
        ->where('id', $id)
        ->firstOrFail();

    $wishlistItem->delete();

    $wishlistCount = Wishlist::where('user_id', Auth::id())->count();

    // If AJAX request
    if (request()->ajax()) {
        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist!',
            'wishlist_count' => $wishlistCount
        ]);
    }

    // Normal form submit
    return redirect()->back()
        ->with('success', 'Product removed from wishlist!');
}

    public function clear()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to manage wishlist.');
        }

        Wishlist::where('user_id', Auth::id())->delete();

        return redirect()->route('user.wishlist.index')
            ->with('success', 'Wishlist cleared successfully!');
    }

    public function moveToCart($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to perform this action.');
        }

        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('product')
            ->firstOrFail();

        // Check if product already in cart
        $cartItem = \App\Models\Cart::where('user_id', Auth::id())
            ->where('product_id', $wishlistItem->product_id)
            ->first();

        if ($cartItem) {
            $wishlistItem->delete();
            return redirect()->route('user.cart.index')
                ->with('info', 'Product already in cart. Removed from wishlist.');
        }

        // Check product availability
        if ($wishlistItem->product->quantity < 1) {
            return redirect()->back()
                ->with('error', 'Product is out of stock.');
        }

        // Add to cart
        \App\Models\Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $wishlistItem->product_id,
            'quantity' => 1
        ]);

        // Remove from wishlist
        $wishlistItem->delete();

        return redirect()->route('user.cart.index')
            ->with('success', 'Product moved to cart successfully!');
    }

    public function getWishlistCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => true,
                'count' => 0
            ]);
        }

        $count = Wishlist::where('user_id', Auth::id())->count();

        return response()->json([
            'success' => true,
            'count' => $count
        ]);
    }

    public function check($productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'in_wishlist' => false
            ]);
        }

        $inWishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist
        ]);
    }
}