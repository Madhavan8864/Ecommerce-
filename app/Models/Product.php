<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'short_description',
        'category_id',
        'brand_id',
        'price',
        'discount_price',
        'discount_percentage',
        'quantity',
        'min_order_quantity',
        'max_order_quantity',
        'weight',
        'dimensions',
        'main_image',
        'images',
        'specifications',
        'features',
        'status',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'views',
        'sold_count',
        'rating_avg',
        'rating_count'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'quantity' => 'integer',
        'min_order_quantity' => 'integer',
        'max_order_quantity' => 'integer',
        'weight' => 'decimal:2',
        'images' => 'array',
        'specifications' => 'array',
        'features' => 'array',
        'status' => 'string',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'views' => 'integer',
        'sold_count' => 'integer',
        'rating_avg' => 'decimal:1',
        'rating_count' => 'integer',
    ];

    // ==================== RELATIONSHIPS ====================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function cartItems()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    // ==================== VISIBILITY SCOPES ====================
    
    /**
     * Scope for products visible to users
     * Products must be: active, in stock, with active category and brand
     */
    public function scopeVisible($query)
    {
        return $query->where('is_active', true)
            ->where('status', 'in_stock')
            ->whereHas('category', function ($q) {
                $q->where('status', 'active');
            })
            ->where(function ($q) {
                $q->whereNull('brand_id')
                  ->orWhereHas('brand', function ($q2) {
                      $q2->where('status', 'active');
                  });
            });
    }

    /**
     * Check if product is visible to users
     */
    public function isVisible()
    {
        return $this->is_active && 
               $this->status == 'in_stock' &&
               $this->category && 
               $this->category->status == 'active' &&
               (!$this->brand_id || ($this->brand && $this->brand->status == 'active'));
    }

    // ==================== OTHER SCOPES ====================
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('status', 'in_stock');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLowStock($query, $threshold = 10)
    {
        return $query->where('quantity', '<=', $threshold)->where('quantity', '>', 0);
    }

    public function scopeOutOfStock($query)
    {
        return $query->where('quantity', '=', 0)->where('status', '!=', 'discontinued');
    }

    public function scopeWithDiscount($query)
    {
        return $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
    }

    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    public function scopeBestSelling($query)
    {
        return $query->orderBy('sold_count', 'desc');
    }

    public function scopeNewArrivals($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByBrand($query, $brandId)
    {
        return $query->where('brand_id', $brandId);
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query->where(function($q) use ($searchTerm) {
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

    // ==================== ATTRIBUTES ====================

    /**
     * Get the main image URL for product display
     * This is the main method used in admin and user views
     */
    public function getMainImageUrlAttribute()
    {
        if (!$this->main_image) {
            return asset('images/default-product.png');
        }
        
        // If the path already starts with 'storage/'
        if (strpos($this->main_image, 'storage/') === 0) {
            return asset($this->main_image);
        }
        
        // If the path starts with 'products/' (after storage link)
        if (strpos($this->main_image, 'products/') === 0) {
            return asset('storage/' . $this->main_image);
        }
        
        // Default case
        return asset('storage/' . $this->main_image);
    }

    /**
     * Alias for main_image_url for backward compatibility
     */
    public function getImageUrlAttribute()
    {
        return $this->main_image_url;
    }

    /**
     * Get all product images as array of URLs
     */
    public function getImagesArrayAttribute()
    {
        if ($this->images) {
            $images = is_string($this->images) ? json_decode($this->images, true) : $this->images;
            if (is_array($images) && count($images) > 0) {
                return array_map(function($image) {
                    if (strpos($image, 'storage/') === 0) {
                        return asset($image);
                    }
                    return asset('storage/' . $image);
                }, $images);
            }
        }
        
        return [$this->main_image_url];
    }

    /**
     * Get current price (with discount applied)
     */
    public function getCurrentPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Check if product has discount
     */
    public function getHasDiscountAttribute()
    {
        return !is_null($this->discount_price) && $this->discount_price > 0 && $this->discount_price < $this->price;
    }

    /**
     * Get discount amount
     */
    public function getDiscountAmountAttribute()
    {
        if ($this->has_discount) {
            return $this->price - $this->discount_price;
        }
        
        return 0;
    }

    /**
     * Get stock status text
     */
    public function getStockStatusAttribute()
    {
        if ($this->status === 'discontinued') {
            return 'Discontinued';
        }
        
        if ($this->quantity <= 0) {
            return 'Out of Stock';
        }
        
        if ($this->quantity <= 10) {
            return 'Low Stock';
        }
        
        return 'In Stock';
    }

    /**
     * Get stock level for CSS classes
     */
    public function getStockLevelAttribute()
    {
        if ($this->quantity <= 0) return 'out_of_stock';
        if ($this->quantity <= 10) return 'low';
        return 'good';
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    /**
     * Get formatted discount price
     */
    public function getFormattedDiscountPriceAttribute()
    {
        return $this->discount_price ? '₹' . number_format($this->discount_price, 2) : null;
    }

    /**
     * Get formatted current price
     */
    public function getFormattedCurrentPriceAttribute()
    {
        return '₹' . number_format($this->current_price, 2);
    }

    // ==================== METHODS ====================

    /**
     * Update product rating based on reviews
     */
    public function updateRating()
    {
        $reviews = $this->reviews()->where('status', 'approved')->get();
        
        if ($reviews->count() > 0) {
            $this->rating_avg = $reviews->avg('rating');
            $this->rating_count = $reviews->count();
        } else {
            $this->rating_avg = 0;
            $this->rating_count = 0;
        }
        
        $this->save();
    }

    /**
     * Check if product can be added to cart
     */
    public function canAddToCart($quantity = 1)
    {
        // Check if product is visible
        if (!$this->isVisible()) {
            return false;
        }
        
        // Check quantity
        if ($quantity > $this->quantity) {
            return false;
        }
        
        // Check min order quantity
        if ($this->min_order_quantity && $quantity < $this->min_order_quantity) {
            return false;
        }
        
        // Check max order quantity
        if ($this->max_order_quantity && $quantity > $this->max_order_quantity) {
            return false;
        }
        
        return true;
    }

    /**
     * Increment sold count when order is placed
     */
    public function incrementSoldCount($quantity = 1)
    {
        $this->increment('sold_count', $quantity);
        $this->decrement('quantity', $quantity);
        
        if ($this->quantity <= 0) {
            $this->update(['status' => 'out_of_stock']);
        }
    }

    /**
     * Decrement sold count when order is cancelled
     */
    public function decrementSoldCount($quantity = 1)
    {
        $this->decrement('sold_count', $quantity);
        $this->increment('quantity', $quantity);
        
        if ($this->quantity > 0 && $this->status == 'out_of_stock') {
            $this->update(['status' => 'in_stock']);
        }
    }

    /**
     * Get average rating as stars HTML
     */
    public function getAverageRatingStars()
    {
        $stars = '';
        $fullStars = floor($this->rating_avg);
        $halfStar = ($this->rating_avg - $fullStars) >= 0.5;
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
        
        for ($i = 0; $i < $fullStars; $i++) {
            $stars .= '<i class="fas fa-star text-warning"></i>';
        }
        
        if ($halfStar) {
            $stars .= '<i class="fas fa-star-half-alt text-warning"></i>';
        }
        
        for ($i = 0; $i < $emptyStars; $i++) {
            $stars .= '<i class="far fa-star text-warning"></i>';
        }
        
        return $stars;
    }

    /**
     * Get related products from same category
     */
    public function getRelatedProducts($limit = 4)
    {
        return self::where('category_id', $this->category_id)
            ->where('id', '!=', $this->id)
            ->visible()
            ->with(['category', 'brand'])
            ->take($limit)
            ->get();
    }

    /**
     * Get breadcrumbs for product page
     */
    public function getBreadcrumbs()
    {
        $breadcrumbs = [];
        
        // Home
        $breadcrumbs[] = [
            'name' => 'Home',
            'url' => route('user.home')
        ];
        
        // Category
        if ($this->category) {
            $breadcrumbs[] = [
                'name' => $this->category->name,
                'url' => route('user.products.category', $this->category->slug)
            ];
            
            // Parent category if exists
            if ($this->category->parent) {
                array_splice($breadcrumbs, 1, 0, [[
                    'name' => $this->category->parent->name,
                    'url' => route('user.products.category', $this->category->parent->slug)
                ]]);
            }
        }
        
        // Product
        $breadcrumbs[] = [
            'name' => $this->name,
            'url' => '#',
            'active' => true
        ];
        
        return $breadcrumbs;
    }

    /**
     * Check if product is new (within 30 days)
     */
    public function isNew()
    {
        return $this->created_at->diffInDays(now()) <= 30;
    }

    /**
     * Get specifications as array
     */
    public function getSpecificationsArray()
    {
        if (empty($this->specifications)) {
            return [];
        }

        if (is_string($this->specifications)) {
            $specs = json_decode($this->specifications, true);
            return json_last_error() === JSON_ERROR_NONE ? $specs : [];
        }

        return is_array($this->specifications) ? $this->specifications : [];
    }

    /**
     * Get features as array
     */
    public function getFeaturesArray()
    {
        if (empty($this->features)) {
            return [];
        }

        if (is_string($this->features)) {
            $features = json_decode($this->features, true);
            return json_last_error() === JSON_ERROR_NONE ? $features : [];
        }

        return is_array($this->features) ? $this->features : [];
    }

    // ==================== STATIC METHODS ====================

    /**
     * Get featured products
     */
    public static function getFeaturedProducts($limit = 8)
    {
        return self::visible()
            ->where('is_featured', true)
            ->with(['category', 'brand'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get new arrivals
     */
    public static function getNewArrivals($limit = 8)
    {
        return self::visible()
            ->with(['category', 'brand'])
            ->latest()
            ->take($limit)
            ->get();
    }

    /**
     * Get best selling products
     */
    public static function getBestSellers($limit = 8)
    {
        return self::visible()
            ->with(['category', 'brand'])
            ->orderBy('sold_count', 'DESC')
            ->take($limit)
            ->get();
    }

    /**
     * Get top rated products
     */
    public static function getTopRated($limit = 8)
    {
        return self::visible()
            ->with(['category', 'brand'])
            ->where('rating_avg', '>=', 4)
            ->orderBy('rating_avg', 'DESC')
            ->take($limit)
            ->get();
    }

    /**
     * Get products on sale
     */
    public static function getOnSale($limit = 8)
    {
        return self::visible()
            ->whereNotNull('discount_price')
            ->where('discount_price', '>', 0)
            ->with(['category', 'brand'])
            ->orderBy('discount_percentage', 'DESC')
            ->take($limit)
            ->get();
    }

    // ==================== QUERY HELPERS ====================

    /**
     * Get price range for filter
     */
    public static function getPriceRange()
    {
        $min = self::visible()->min('price') ?? 0;
        $max = self::visible()->max('price') ?? 10000;
        
        return [
            'min' => floor($min / 100) * 100, // Round down to nearest 100
            'max' => ceil($max / 100) * 100   // Round up to nearest 100
        ];
    }

    /**
     * Get filtered products based on criteria
     */
    public static function getFilteredProducts($filters = [])
    {
        $query = self::visible()->with(['category', 'brand']);

        // Apply filters
        if (isset($filters['category']) && $filters['category']) {
            $query->where('category_id', $filters['category']);
        }

        if (isset($filters['brand']) && $filters['brand']) {
            $query->where('brand_id', $filters['brand']);
        }

        if (isset($filters['min_price']) && $filters['min_price']) {
            $query->where('price', '>=', $filters['min_price']);
        }

        if (isset($filters['max_price']) && $filters['max_price']) {
            $query->where('price', '<=', $filters['max_price']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->search($filters['search']);
        }

        if (isset($filters['featured']) && $filters['featured']) {
            $query->where('is_featured', true);
        }

        if (isset($filters['discount']) && $filters['discount']) {
            $query->withDiscount();
        }

        // Apply sorting
        if (isset($filters['sort'])) {
            switch ($filters['sort']) {
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
                case 'rating':
                    $query->orderBy('rating_avg', 'DESC');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
        }

        return $query;
    }
}