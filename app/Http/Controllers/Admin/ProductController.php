<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand'])->latest()->paginate(10);
        $categories = Category::all();
        $brands = Brand::all();
        
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products',
            'main_image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'specifications' => 'nullable|array',
            'features' => 'nullable|array',
            'status' => 'required|in:in_stock,out_of_stock,discontinued',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ]);

        // Slug generate
        $validated['slug'] = Str::slug($validated['name']);

        // Checkbox handling
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        /*
        |--------------------------------------------------------------------------
        | Store Multiple Images
        |--------------------------------------------------------------------------
        */
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }
        $validated['images'] = json_encode($imagePaths);

        /*
        |--------------------------------------------------------------------------
        | Store Main Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('main_image')) {
            $mainImagePath = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = $mainImagePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Discount Percentage
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['discount_price']) && $validated['price'] > 0) {
            $discountPercentage = (($validated['price'] - $validated['discount_price']) / $validated['price']) * 100;
            $validated['discount_percentage'] = round($discountPercentage, 2);
        } else {
            $validated['discount_percentage'] = null;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('status', 'active')->get();
        $brands = Brand::where('status', 'active')->get();
        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'specifications' => 'nullable|array',
            'features' => 'nullable|array',
            'status' => 'required|in:in_stock,out_of_stock,discontinued',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['is_featured'] = $request->has('is_featured');
        $validated['is_active'] = $request->has('is_active');

        /*
        |--------------------------------------------------------------------------
        | Update Multiple Images
        |--------------------------------------------------------------------------
        */
        $existingImages = $product->images ? json_decode($product->images, true) : [];
        $imagePaths = $existingImages;

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $imagePaths[] = $path;
            }
        }
        $validated['images'] = json_encode($imagePaths);

        /*
        |--------------------------------------------------------------------------
        | Update Main Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('main_image')) {
            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            }

            $mainImagePath = $request->file('main_image')->store('products', 'public');
            $validated['main_image'] = $mainImagePath;
        }

        /*
        |--------------------------------------------------------------------------
        | Discount Percentage
        |--------------------------------------------------------------------------
        */
        if (!empty($validated['discount_price']) && $validated['price'] > 0) {
            $discountPercentage = (($validated['price'] - $validated['discount_price']) / $validated['price']) * 100;
            $validated['discount_percentage'] = round($discountPercentage, 2);
        } else {
            $validated['discount_percentage'] = null;
        }

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
        }

        if ($product->images) {
            $images = json_decode($product->images, true);
            foreach ($images as $image) {
                if (Storage::disk('public')->exists($image)) {
                    Storage::disk('public')->delete($image);
                }
            }
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully!'
        ]);
    }

    public function show(Product $product)
    {
        $product->load(['category', 'brand']);
        return view('admin.products.show', compact('product'));
    }

    public function deleteImage(Product $product, $imageIndex)
    {
        $images = json_decode($product->images, true);

        if (isset($images[$imageIndex])) {
            if (Storage::disk('public')->exists($images[$imageIndex])) {
                Storage::disk('public')->delete($images[$imageIndex]);
            }

            unset($images[$imageIndex]);
            $images = array_values($images);

            $product->update(['images' => json_encode($images)]);

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }
}
