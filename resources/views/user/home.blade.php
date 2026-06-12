@extends('user.layouts.app')

@section('title', 'eCart Electronics - Home')

@section('content')
<!-- Modern Header Banner -->
<section style="background: linear-gradient(145deg, #0b2b4f 0%, #1a3e5c 100%); padding: 50px 0; margin-bottom: 40px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="background: rgba(255,255,255,0.12); padding: 6px 16px; border-radius: 40px; color: #ffd966; font-size: 14px; font-weight: 600; letter-spacing: 0.5px;">
                        <i class="fas fa-bolt" style="margin-right: 6px;"></i> FLASH SALE
                    </span>
                    <span style="color: rgba(255,255,255,0.7); font-size: 14px;">Free shipping on orders ₹999+</span>
                </div>
                <h1 style="font-size: 42px; font-weight: 700; color: white; margin-bottom: 12px; line-height: 1.2;">
                    Premium Electronics<br> <span style="color: #ffd966;">At Best Prices</span>
                </h1>
                <div style="display: flex; align-items: center; gap: 32px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-check-circle" style="color: #4ade80; font-size: 18px;"></i>
                        <span style="color: rgba(255,255,255,0.9); font-size: 15px;">Genuine Products</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-truck" style="color: #4ade80; font-size: 18px;"></i>
                        <span style="color: rgba(255,255,255,0.9); font-size: 15px;">Fast Delivery</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-shield-alt" style="color: #4ade80; font-size: 18px;"></i>
                        <span style="color: rgba(255,255,255,0.9); font-size: 15px;">2 Year Warranty</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 20px; padding: 24px; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 60px; height: 60px; background: rgba(255,215,0,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-tag" style="color: #ffd966; font-size: 28px;"></i>
                        </div>
                        <div>
                            <div style="color: rgba(255,255,255,0.8); font-size: 14px; margin-bottom: 4px;">Today's Deal</div>
                            <div style="color: white; font-size: 28px; font-weight: 700;">Upto 70%</div>
                            <div style="color: #ffd966; font-size: 14px;">Limited period offer</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Categories -->
<section class="featured-categories py-5 bg-light">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">Shop by Category</h2>
            <p class="section-subtitle">Browse products from our top categories</p>
        </div>

        <div class="row">
            @php
                $categories = \App\Models\Category::where('status', 'active')
                    ->whereNull('parent_id')
                    ->withCount(['products' => function($query) {
                        $query->where('is_active', true)->where('status', 'in_stock');
                    }])
                    ->having('products_count', '>', 0)
                    ->orderBy('display_order')
                    ->limit(8)
                    ->get();
            @endphp

            @foreach($categories as $category)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <a href="{{ route('user.products.index', ['category' => $category->slug]) }}">
                            <div class="category-image">
                                <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="img-fluid">
                                <div class="category-overlay"></div>
                            </div>
                            <div class="category-content">
                                <h5>{{ $category->name }}</h5>
                                <p class="mb-0">{{ $category->products_count }} Products</p>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products - MODERN STYLE CARDS (MEDIUM SIZE) -->
<section class="featured-products py-5">
    <div class="container">
        <div class="section-header mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Carefully curated products for you</p>
            </div>
            <a href="{{ route('user.products.index', ['sort' => 'featured']) }}" class="btn btn-outline-primary">
                View All <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="row g-4">
            @php
                $featuredProducts = \App\Models\Product::where('is_active', true)
                    ->where('status', 'in_stock')
                    ->where('is_featured', true)
                    ->latest()
                    ->limit(8)
                    ->get();
            @endphp

            @forelse($featuredProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <!-- MODERN FEATURED PRODUCT CARD - MEDIUM SIZE -->
                    <div class="modern-featured-card">
                        <!-- Image Section -->
                        <div class="featured-image-section">
                            <a href="{{ route('user.products.show', $product->slug) }}" class="featured-image-link">
                                <div class="featured-image-wrapper">
                                    <img src="{{ $product->main_image 
                                                ? asset('storage/' . $product->main_image) 
                                                : asset('images/no-image.png') }}" 
                                         class="featured-product-img"
                                         alt="{{ $product->name }}"
                                         loading="lazy">
                                    
                                    @if($product->has_discount)
                                        <div class="featured-discount-badge">
                                            <span>{{ round($product->discount_percentage) }}% OFF</span>
                                        </div>
                                    @endif
                                    
                                    @if($product->is_new)
                                        <div class="featured-new-badge">
                                            <span>NEW</span>
                                        </div>
                                    @endif
                                </div>
                            </a>
                            
                            <!-- Quick Actions -->
                            <div class="featured-quick-actions">
                                <button class="featured-action-btn wishlist" onclick="addToWishlist({{ $product->id }})" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="featured-action-btn quickview" onclick="quickView({{ $product->id }})" title="Quick View">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Content Section -->
                        <div class="featured-content-section">
                            <!-- Category & Brand -->
                            <div class="featured-meta-info">
                                @if($product->category)
                                    <a href="{{ route('user.products.index', ['category' => $product->category->slug]) }}" class="featured-category">
                                        {{ $product->category->name }}
                                    </a>
                                @endif
                                @if($product->brand)
                                    <span class="featured-meta-separator">•</span>
                                    <span class="featured-brand">{{ $product->brand->name }}</span>
                                @endif
                            </div>
                            
                            <!-- Product Title -->
                            <h3 class="featured-product-title">
                                <a href="{{ route('user.products.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            
                            <!-- Rating -->
                            <div class="featured-rating-wrapper">
                                <div class="featured-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->rating_avg)
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product->rating_avg)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="featured-rating-count">({{ $product->rating_count ?? 0 }})</span>
                            </div>
                            
                            <!-- Price & Cart -->
                            <div class="featured-price-cart-wrapper">
                                <div class="featured-price-wrapper">
                                    <span class="featured-current-price">₹{{ number_format($product->current_price, 2) }}</span>
                                    @if($product->has_discount)
                                        <span class="featured-original-price">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                <button class="featured-cart-btn add-to-cart-btn"
                                    data-id="{{ $product->id }}"
                                    {{ $product->stock_level == 'out_of_stock' ? 'disabled' : '' }}>
                                    @if($product->stock_level == 'out_of_stock')
                                        <i class="fas fa-bell"></i>
                                        <span>Notify</span>
                                    @else
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Add</span>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5>No Featured Products Available</h5>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- New Arrivals - MODERN STYLE CARDS (MEDIUM SIZE) -->
<section class="new-arrivals py-5 bg-light">
    <div class="container">
        <div class="section-header mb-5 d-flex justify-content-between align-items-center">
            <div>
                <h2 class="section-title">New Arrivals</h2>
                <p class="section-subtitle">Latest products added to our store</p>
            </div>
            <a href="{{ route('user.products.index', ['sort' => 'latest']) }}" class="btn btn-outline-primary">
                View All <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="row g-4">
            @php
                $newProducts = \App\Models\Product::where('is_active', true)
                    ->where('status', 'in_stock')
                    ->latest()
                    ->limit(8)
                    ->get();
            @endphp

            @foreach($newProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    <!-- MODERN NEW ARRIVAL PRODUCT CARD - MEDIUM SIZE -->
                    <div class="modern-arrival-card">
                        <!-- Image Section -->
                        <div class="arrival-image-section">
                            <a href="{{ route('user.products.show', $product->slug) }}" class="arrival-image-link">
                                <div class="arrival-image-wrapper">
                                    <img src="{{ $product->main_image 
                                                ? asset('storage/' . $product->main_image) 
                                                : asset('images/no-image.png') }}" 
                                         class="arrival-product-img"
                                         alt="{{ $product->name }}"
                                         loading="lazy">
                                    
                                    @if($product->has_discount)
                                        <div class="arrival-discount-badge">
                                            <span>-{{ round($product->discount_percentage) }}%</span>
                                        </div>
                                    @endif
                                    
                                    <div class="arrival-time-badge">
                                        <i class="far fa-clock"></i>
                                        <span>New</span>
                                    </div>
                                </div>
                            </a>
                            
                            <!-- Quick Actions -->
                            <div class="arrival-quick-actions">
                                <button class="arrival-action-btn" onclick="addToWishlist({{ $product->id }})" title="Add to Wishlist">
                                    <i class="far fa-heart"></i>
                                </button>
                                <button class="arrival-action-btn" onclick="quickView({{ $product->id }})" title="Quick View">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Content Section -->
                        <div class="arrival-content-section">
                            <!-- Product Title -->
                            <h3 class="arrival-product-title">
                                <a href="{{ route('user.products.show', $product->slug) }}">
                                    {{ $product->name }}
                                </a>
                            </h3>
                            
                            <!-- Category -->
                            <div class="arrival-category">
                                @if($product->category)
                                    <a href="{{ route('user.products.index', ['category' => $product->category->slug]) }}">
                                        {{ $product->category->name }}
                                    </a>
                                @endif
                            </div>
                            
                            <!-- Rating & Stock -->
                            <div class="arrival-rating-stock">
                                <div class="arrival-stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $product->rating_avg)
                                            <i class="fas fa-star"></i>
                                        @elseif($i - 0.5 <= $product->rating_avg)
                                            <i class="fas fa-star-half-alt"></i>
                                        @else
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                    <span>({{ $product->rating_count ?? 0 }})</span>
                                </div>
                                
                                @if($product->stock_level == 'low')
                                    <span class="arrival-stock low">Only {{ $product->stock_quantity }} left</span>
                                @elseif($product->stock_level == 'in_stock')
                                    <span class="arrival-stock in">In Stock</span>
                                @endif
                            </div>
                            
                            <!-- Price & Cart -->
                            <div class="arrival-price-cart">
                                <div class="arrival-price">
                                    <span class="arrival-current-price">₹{{ number_format($product->current_price, 2) }}</span>
                                    @if($product->has_discount)
                                        <span class="arrival-original-price">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                <button class="arrival-cart-btn add-to-cart-btn"
                                    data-id="{{ $product->id }}"
                                    {{ $product->stock_level == 'out_of_stock' ? 'disabled' : '' }}>
                                    <i class="fas fa-shopping-bag"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Top Brands -->
<section class="top-brands py-5">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">Top Brands</h2>
            <p class="section-subtitle">Trusted brands we work with</p>
        </div>

        <div class="row">
            @php
                $brands = \App\Models\Brand::where('status', 'active')
                    ->withCount('products')
                    ->limit(12)
                    ->get();
            @endphp

            @foreach($brands as $brand)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="brand-card text-center">
                        <a href="{{ route('user.products.index', ['brand' => $brand->slug]) }}">
                            <img src="{{ $brand->logo_url }}" class="img-fluid mb-2">
                            <h6>{{ $brand->name }}</h6>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<style>
/* ===== MODERN FEATURED PRODUCT CARD STYLES - MEDIUM SIZE (Flipkart/Amazon Style) ===== */
.modern-featured-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    border: 1px solid #f0f0f0;
}

.modern-featured-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    border-color: #e0e0e0;
}

.featured-image-section {
    position: relative;
    padding: 16px 16px 0 16px;
}

.featured-image-link {
    display: block;
    text-decoration: none;
}

.featured-image-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 100%;
    overflow: hidden;
    border-radius: 8px;
    background: #fafafa;
}

.featured-product-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    transition: transform 0.4s ease;
    padding: 8px;
}

.modern-featured-card:hover .featured-product-img {
    transform: scale(1.04);
}

.featured-discount-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(239, 68, 68, 0.2);
}

.featured-discount-badge span {
    color: white;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.featured-new-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    padding: 4px 12px;
    border-radius: 20px;
    z-index: 2;
    box-shadow: 0 2px 8px rgba(139, 92, 246, 0.2);
}

.featured-new-badge span {
    color: white;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.featured-quick-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    opacity: 0;
    transform: translateX(8px);
    transition: all 0.3s ease;
    z-index: 5;
}

.modern-featured-card:hover .featured-quick-actions {
    opacity: 1;
    transform: translateX(0);
}

.featured-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: none;
    background: white;
    color: #4b5563;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
}

.featured-action-btn:hover {
    background: #4f46e5;
    color: white;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
}

.featured-content-section {
    padding: 12px 14px 14px 14px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.featured-meta-info {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 6px;
    font-size: 11px;
}

.featured-category {
    color: #4f46e5;
    text-decoration: none;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 10px;
    transition: color 0.2s;
}

.featured-category:hover {
    color: #4338ca;
}

.featured-meta-separator {
    color: #d1d5db;
}

.featured-brand {
    color: #6b7280;
    font-size: 10px;
    font-weight: 500;
}

.featured-product-title {
    margin: 0 0 8px 0;
    font-size: 14px;
    line-height: 1.4;
    font-weight: 500;
}

.featured-product-title a {
    color: #212121;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s;
}

.featured-product-title a:hover {
    color: #4f46e5;
}

.featured-rating-wrapper {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 12px;
}

.featured-stars {
    display: flex;
    gap: 2px;
}

.featured-stars i {
    font-size: 11px;
}

.featured-stars .fa-star,
.featured-stars .fa-star-half-alt {
    color: #ffc107;
}

.featured-stars .far.fa-star {
    color: #e0e0e0;
}

.featured-rating-count {
    color: #6b7280;
    font-size: 11px;
    font-weight: 500;
}

.featured-price-cart-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 10px;
    border-top: 1px solid #f0f0f0;
}

.featured-price-wrapper {
    display: flex;
    flex-direction: column;
}

.featured-current-price {
    font-size: 18px;
    font-weight: 700;
    color: #212121;
    line-height: 1.2;
}

.featured-original-price {
    font-size: 11px;
    color: #9e9e9e;
    text-decoration: line-through;
}

.featured-cart-btn {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 30px;
    padding: 6px 14px;
    display: flex;
    align-items: center;
    gap: 6px;
    color: #212121;
    font-size: 12px;
    font-weight: 500;
    transition: all 0.2s ease;
    cursor: pointer;
    background: #fafafa;
}

.featured-cart-btn i {
    font-size: 13px;
    color: #4f46e5;
    transition: all 0.2s ease;
}

.featured-cart-btn:hover:not(:disabled) {
    background: #4f46e5;
    border-color: #4f46e5;
    color: white;
    transform: translateX(2px);
}

.featured-cart-btn:hover:not(:disabled) i {
    color: white;
}

.featured-cart-btn:disabled {
    background: #f5f5f5;
    border-color: #e0e0e0;
    color: #9e9e9e;
    cursor: not-allowed;
}

.featured-cart-btn:disabled i {
    color: #9e9e9e;
}

/* ===== MODERN NEW ARRIVAL PRODUCT CARD STYLES - MEDIUM SIZE ===== */
.modern-arrival-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    border: 1px solid #f0f0f0;
}

.modern-arrival-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    border-color: #e0e0e0;
}

.arrival-image-section {
    position: relative;
    padding: 16px 16px 0 16px;
}

.arrival-image-link {
    display: block;
    text-decoration: none;
}

.arrival-image-wrapper {
    position: relative;
    width: 100%;
    padding-bottom: 100%;
    overflow: hidden;
    border-radius: 8px;
    background: #fafafa;
}

.arrival-product-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: contain;
    object-position: center;
    transition: transform 0.4s ease;
    padding: 8px;
}

.modern-arrival-card:hover .arrival-product-img {
    transform: scale(1.04);
}

.arrival-discount-badge {
    position: absolute;
    top: 8px;
    left: 8px;
    background: #059669;
    padding: 4px 10px;
    border-radius: 20px;
    z-index: 2;
}

.arrival-discount-badge span {
    color: white;
    font-size: 11px;
    font-weight: 700;
}

.arrival-time-badge {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(4px);
    padding: 4px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 4px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

.arrival-time-badge i {
    color: #2563eb;
    font-size: 10px;
}

.arrival-time-badge span {
    color: #1e293b;
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}

.arrival-quick-actions {
    position: absolute;
    top: 12px;
    right: 12px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    opacity: 0;
    transform: translateX(8px);
    transition: all 0.3s ease;
    z-index: 5;
}

.modern-arrival-card:hover .arrival-quick-actions {
    opacity: 1;
    transform: translateX(0);
}

.arrival-action-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    border: none;
    background: white;
    color: #475569;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

.arrival-action-btn:hover {
    background: #2563eb;
    color: white;
    transform: scale(1.05);
}

.arrival-content-section {
    padding: 12px 14px 14px 14px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.arrival-product-title {
    margin: 0 0 4px 0;
    font-size: 14px;
    line-height: 1.4;
    font-weight: 500;
}

.arrival-product-title a {
    color: #212121;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s;
}

.arrival-product-title a:hover {
    color: #2563eb;
}

.arrival-category {
    margin-bottom: 8px;
}

.arrival-category a {
    color: #64748b;
    text-decoration: none;
    font-size: 11px;
    transition: color 0.2s;
}

.arrival-category a:hover {
    color: #2563eb;
}

.arrival-rating-stock {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
}

.arrival-stars {
    display: flex;
    align-items: center;
    gap: 3px;
}

.arrival-stars i {
    font-size: 10px;
}

.arrival-stars .fa-star,
.arrival-stars .fa-star-half-alt {
    color: #ffc107;
}

.arrival-stars .far.fa-star {
    color: #e0e0e0;
}

.arrival-stars span {
    color: #64748b;
    font-size: 10px;
    margin-left: 3px;
}

.arrival-stock {
    font-size: 10px;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 20px;
}

.arrival-stock.low {
    background: #fef2f2;
    color: #b91c1c;
}

.arrival-stock.in {
    background: #f0fdf9;
    color: #0d9488;
}

.arrival-price-cart {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: auto;
    padding-top: 10px;
    border-top: 1px solid #f0f0f0;
}

.arrival-price {
    display: flex;
    flex-direction: column;
}

.arrival-current-price {
    font-size: 18px;
    font-weight: 700;
    color: #212121;
    line-height: 1.2;
}

.arrival-original-price {
    font-size: 11px;
    color: #9e9e9e;
    text-decoration: line-through;
}

.arrival-cart-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #e0e0e0;
    background: #fafafa;
    color: #2563eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.arrival-cart-btn:hover:not(:disabled) {
    background: #2563eb;
    border-color: #2563eb;
    color: white;
    transform: scale(1.05);
}

.arrival-cart-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ===== RESPONSIVE - MEDIUM SIZE ===== */
@media (max-width: 768px) {
    .featured-image-section,
    .arrival-image-section {
        padding: 12px 12px 0 12px;
    }
    
    .featured-content-section,
    .arrival-content-section {
        padding: 10px 12px 12px 12px;
    }
    
    .featured-product-title,
    .arrival-product-title {
        font-size: 13px;
    }
    
    .featured-current-price,
    .arrival-current-price {
        font-size: 16px;
    }
    
    .featured-cart-btn span {
        display: inline;
    }
    
    .featured-cart-btn {
        padding: 5px 12px;
    }
    
    .featured-cart-btn span {
        font-size: 11px;
    }
}

@media (max-width: 576px) {
    .featured-product-img,
    .arrival-product-img {
        padding: 6px;
    }
    
    .featured-quick-actions,
    .arrival-quick-actions {
        top: 8px;
        right: 8px;
    }
    
    .featured-action-btn,
    .arrival-action-btn {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
    
    .featured-cart-btn span {
        display: none;
    }
    
    .featured-cart-btn {
        padding: 6px 10px;
    }
}

/* ===== ANIMATIONS ===== */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(12px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modern-featured-card,
.modern-arrival-card {
    animation: fadeInUp 0.4s ease forwards;
}

/* ===== NO IMAGE PLACEHOLDER ===== */
.featured-product-img[src$="no-image.png"],
.arrival-product-img[src$="no-image.png"] {
    object-fit: contain;
    padding: 16px;
    opacity: 0.6;
}
</style>

@endsection

@push('scripts')
<script>
      // Add to Cart Without Redirect
$(document).on('click', '.add-to-cart-btn', function(e) {
    e.preventDefault();

    let productId = $(this).data('id');
    let button = $(this);

    $.ajax({
        url: "{{ route('user.cart.add') }}", // unga cart add route
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            product_id: productId,
            quantity: 1
        },
        success: function(response) {
            // Optional success feedback
            button.html('<i class="fas fa-check"></i> Added');
            button.prop('disabled', true);

            setTimeout(function() {
                button.html('<i class="fas fa-shopping-cart"></i> Add');
                button.prop('disabled', false);
            }, 1500);
        },
        error: function() {
            alert('Something went wrong!');
        }
    });
});
    $(document).ready(function() {
        // Hero Slider
        $('.hero-carousel').owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: true,
            autoplay: true,
            autoplayTimeout: 5000,
            navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>']
        });

        // Testimonial Carousel
        $('.testimonial-carousel').owlCarousel({
            items: 1,
            loop: true,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 4000,
            responsive: {
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }
            }
        });
    });

    // Quick View Function
    function quickView(productId) {
        // Your quick view logic here
        console.log('Quick view product:', productId);
    }

    // Add to Wishlist Function
    function addToWishlist(productId) {
        // Your wishlist logic here
        console.log('Add to wishlist:', productId);
    }

    
</script>
@endpush