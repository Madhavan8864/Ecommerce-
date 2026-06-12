<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Base query - ONLY show admin-added products that are active
        $query = Product::visible()
            ->with(['category', 'brand']);

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category)
                  ->where('status', 'active');
            });
        }

        // Filter by brand
        if ($request->has('brand') && !empty($request->brand)) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand)
                  ->where('status', 'active');
            });
        }

        // Filter by price range
        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%")
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%")
                        ->where('status', 'active');
                  })
                  ->orWhereHas('brand', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%")
                        ->where('status', 'active');
                  });
            });
        }

        // Filter by stock status
        if ($request->has('stock')) {
            if ($request->stock == 'in_stock') {
                $query->where('quantity', '>', 0);
            } elseif ($request->stock == 'out_of_stock') {
                $query->where('quantity', '=', 0);
            } elseif ($request->stock == 'low_stock') {
                $query->whereBetween('quantity', [1, 10]);
            }
        }

        // Filter by featured products
        if ($request->has('featured') && $request->featured == '1') {
            $query->where('is_featured', true);
        }

        // Filter by discount
        if ($request->has('discount') && $request->discount == '1') {
            $query->whereNotNull('discount_price')
                  ->where('discount_price', '>', 0);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'ASC');
                break;
            case 'price_high':
                $query->orderBy('price', 'DESC');
                break;
            case 'popular':
                $query->orderBy('views', 'DESC');
                break;
            case 'best_selling':
                $query->orderBy('sold_count', 'DESC');
                break;
            case 'discount':
                $query->whereNotNull('discount_price')
                      ->where('discount_price', '>', 0)
                      ->orderBy('discount_percentage', 'DESC');
                break;
            case 'featured':
                $query->where('is_featured', true)
                      ->latest();
                break;
            case 'rating':
                $query->orderBy('rating_avg', 'DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('name', 'DESC');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        // Get active categories and brands for filters
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($q) {
                $q->visible();
            }])
            ->having('products_count', '>', 0)
            ->get();

        $brands = Brand::where('status', 'active')
            ->withCount(['products' => function($q) {
                $q->visible();
            }])
            ->having('products_count', '>', 0)
            ->get();

        // Get price range for filter
        $priceRange = [
            'min' => Product::visible()->min('price') ?? 0,
            'max' => Product::visible()->max('price') ?? 10000
        ];

        return view('user.products.index', compact(
            'products', 
            'categories', 
            'brands', 
            'priceRange'
        ));
    }

    public function show($slug)
    {  
        // Get product - ONLY if it's active and visible
        $product = Product::where('slug', $slug)
            ->visible()
            ->with(['category', 'brand'])
            ->firstOrFail();

        // Increment view count
        $product->increment('views');

        // Get related products (same category, also active)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->visible()
            ->with(['category', 'brand'])
            ->take(4)
            ->get();

        // Get product reviews - ONLY approved reviews
        $reviews = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->with('user')
            ->latest()
            ->paginate(5);

        // Calculate average rating
        $averageRating = $reviews->avg('rating') ?? 0;
        $ratingCount = $reviews->count();

        // Get rating distribution
        $ratingDistribution = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'DESC')
            ->get();

        // Check if user has already reviewed
        $hasReviewed = false;
        if (auth()->check()) {
            $hasReviewed = Review::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('user.products.show', compact(
            'product',
            'relatedProducts',
            'reviews',
            'averageRating',
            'ratingCount',
            'ratingDistribution',
            'hasReviewed'
        ));
    }

    public function addReview(Request $request, Product $product)
    {
        // Check if product is visible to user
        if (!$product->isVisible()) {
            abort(404, 'Product not found');
        }

        // Validate request
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'required|string|max:200',
            'comment' => 'required|string|min:10|max:500'
        ]);

        // Check if user is logged in
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to write a review.');
        }

        // Check if user already reviewed this product
        $existingReview = Review::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return redirect()->back()
                ->with('error', 'You have already reviewed this product.');
        }

        // Create review with APPROVED status (immediate display)
        $review = Review::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'status' => 'approved'
        ]);

        // Update product rating
        $this->updateProductRating($product);

        return redirect()->back()
            ->with('success', 'Thank you for your review!');
    }

    /**
     * Update product rating based on all approved reviews
     */
    private function updateProductRating($product)
    {
        $reviews = Review::where('product_id', $product->id)
            ->where('status', 'approved')
            ->get();
        
        $avgRating = $reviews->avg('rating') ?? 0;
        $ratingCount = $reviews->count();
        
        $product->update([
            'rating_avg' => $avgRating,
            'rating_count' => $ratingCount
        ]);
    }

    public function compare(Request $request)
    {
        $productIds = $request->get('products', []);
        
        if (count($productIds) > 4) {
            return redirect()->back()
                ->with('error', 'You can compare up to 4 products only.');
        }

        $products = Product::whereIn('id', $productIds)
            ->visible()
            ->with(['category', 'brand'])
            ->get();

        if ($products->isEmpty()) {
            return redirect()->route('user.products.index')
                ->with('error', 'No products selected for comparison.');
        }

        return view('user.products.compare', compact('products'));
    }

    public function quickView($id)
    {
        $product = Product::where('id', $id)
            ->visible()
            ->with(['category', 'brand'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'product' => $product,
            'images' => $product->images ? json_decode($product->images, true) : [],
            'discount' => $product->discount_price ? [
                'percentage' => $product->discount_percentage,
                'price' => $product->discount_price
            ] : null
        ]);
    }

    public function categoryProducts($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $products = Product::where('category_id', $category->id)
            ->visible()
            ->with(['category', 'brand'])
            ->latest()
            ->paginate(12);

        $subcategories = Category::where('parent_id', $category->id)
            ->where('status', 'active')
            ->get();

        return view('user.products.category', compact(
            'category',
            'products',
            'subcategories'
        ));
    }

    public function brandProducts($slug)
    {
        $brand = Brand::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        $products = Product::where('brand_id', $brand->id)
            ->visible()
            ->with(['category', 'brand'])
            ->latest()
            ->paginate(12);

        return view('user.products.brand', compact('brand', 'products'));
    }

    public function newArrivals()
    {
        $products = Product::visible()
            ->with(['category', 'brand'])
            ->latest()
            ->take(20)
            ->get();

        return view('user.products.new-arrivals', compact('products'));
    }

    public function featuredProducts()
    {
        $products = Product::visible()
            ->where('is_featured', true)
            ->with(['category', 'brand'])
            ->latest()
            ->paginate(12);

        return view('user.products.featured', compact('products'));
    }

    public function discountedProducts()
    {
        $products = Product::visible()
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->with(['category', 'brand'])
            ->orderBy('discount_percentage', 'DESC')
            ->paginate(12);

        return view('user.products.discounted', compact('products'));
    }

    public function bestSellers()
    {
        $products = Product::visible()
            ->with(['category', 'brand'])
            ->orderBy('sold_count', 'DESC')
            ->paginate(12);

        return view('user.products.best-sellers', compact('products'));
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->get('query', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::visible()
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%")
                  ->orWhereHas('category', function($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%")
                        ->where('status', 'active');
                  })
                  ->orWhereHas('brand', function($q) use ($query) {
                      $q->where('name', 'like', "%{$query}%")
                        ->where('status', 'active');
                  });
            })
            ->select('id', 'name', 'slug', 'main_image', 'price', 'discount_price')
            ->take(10)
            ->get();

        $categories = Category::where('name', 'like', "%{$query}%")
            ->where('status', 'active')
            ->select('id', 'name', 'slug')
            ->take(5)
            ->get();

        $brands = Brand::where('name', 'like', "%{$query}%")
            ->where('status', 'active')
            ->select('id', 'name', 'slug')
            ->take(5)
            ->get();

        $suggestions = [];

        // Add products to suggestions
        foreach ($products as $product) {
            $suggestions[] = [
                'type' => 'product',
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'image' => $product->main_image_url,
                'price' => $product->current_price,
                'url' => route('user.products.show', $product->slug)
            ];
        }

        // Add categories to suggestions
        foreach ($categories as $category) {
            $suggestions[] = [
                'type' => 'category',
                'id' => $category->id,
                'name' => $category->name,
                'slug' => $category->slug,
                'url' => route('user.products.category', $category->slug)
            ];
        }

        // Add brands to suggestions
        foreach ($brands as $brand) {
            $suggestions[] = [
                'type' => 'brand',
                'id' => $brand->id,
                'name' => $brand->name,
                'slug' => $brand->slug,
                'url' => route('user.products.brand', $brand->slug)
            ];
        }

        return response()->json($suggestions);
    }

    public function checkStock(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::visible()
            ->where('id', $request->product_id)
            ->firstOrFail();

        $available = $product->canAddToCart($request->quantity);

        return response()->json([
            'success' => true,
            'available' => $available,
            'max_quantity' => $product->quantity,
            'message' => $available 
                ? 'Product is available' 
                : 'Only ' . $product->quantity . ' items available in stock'
        ]);
    }

    public function getProductDetails($id)
    {
        $product = Product::visible()
            ->where('id', $id)
            ->with(['category', 'brand'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'current_price' => $product->current_price,
                'main_image' => $product->main_image_url,
                'quantity' => $product->quantity,
                'status' => $product->status,
                'has_discount' => $product->has_discount,
                'discount_percentage' => $product->discount_percentage,
                'in_stock' => $product->quantity > 0,
                'category' => $product->category ? $product->category->name : null,
                'brand' => $product->brand ? $product->brand->name : null
            ]
        ]);
    }
}