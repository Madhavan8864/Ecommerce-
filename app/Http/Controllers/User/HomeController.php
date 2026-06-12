<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->where('status', 'in_stock')
            ->with(['category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        // Get new arrivals
        $newArrivals = Product::where('is_active', true)
            ->where('status', 'in_stock')
            ->with(['category', 'brand'])
            ->latest()
            ->take(8)
            ->get();

        // Get trending products (based on views or sales)
        $trendingProducts = Product::where('is_active', true)
            ->where('status', 'in_stock')
            ->with(['category', 'brand'])
            ->orderBy('views', 'DESC')
            ->take(8)
            ->get();

        // Get best selling products
        $bestSellingProducts = Product::where('is_active', true)
            ->where('status', 'in_stock')
            ->with(['category', 'brand'])
            ->orderBy('sold_count', 'DESC')
            ->take(8)
            ->get();

        // Get categories with product counts
        $categories = Category::where('status', 'active')
            ->withCount(['products' => function($query) {
                $query->where('is_active', true)->where('status', 'in_stock');
            }])
            ->having('products_count', '>', 0)
            ->take(6)
            ->get();

        // Get active brands
        $brands = Brand::where('status', 'active')
            ->withCount(['products' => function($query) {
                $query->where('is_active', true)->where('status', 'in_stock');
            }])
            ->having('products_count', '>', 0)
            ->take(8)
            ->get();

        return view('user.home', compact(
            'featuredProducts',
            'newArrivals',
            'trendingProducts',
            'bestSellingProducts',
            'categories',
            'brands'
        ));
    }

    public function search(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('status', 'in_stock')
            ->with(['category', 'brand']);

        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('sku', 'like', "%{$searchTerm}%")
                  ->orWhereHas('category', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  })
                  ->orWhereHas('brand', function($q) use ($searchTerm) {
                      $q->where('name', 'like', "%{$searchTerm}%");
                  });
            });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('brand') && !empty($request->brand)) {
            $query->whereHas('brand', function($q) use ($request) {
                $q->where('slug', $request->brand);
            });
        }

        if ($request->has('min_price') && !empty($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
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
            case 'latest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12);

        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();

        return view('user.products.index', compact('products', 'categories', 'brands'));
    }

    public function contact()
    {
        return view('user.contact');
    }

    public function about()
    {
        return view('user.about');
    }

    public function faq()
    {
        return view('user.faq');
    }
}