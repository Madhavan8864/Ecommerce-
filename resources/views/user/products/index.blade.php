@extends('user.layouts.app')

@section('title', 'Products - eCart Electronics')
@section('content')
<!-- Products Section -->
<section style="padding: 0 0 60px 0; background: #f8fafd;">
    <div class="container">
        <!-- Breadcrumb & Navigation -->
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 30px; background: white; padding: 16px 24px; border-radius: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('user.home') }}" style="color: #5b6f82; text-decoration: none; font-size: 14px; display: flex; align-items: center; gap: 6px;">
                    <i class="fas fa-home"></i> Home
                </a>
                <i class="fas fa-chevron-right" style="color: #cbd5e0; font-size: 12px;"></i>
                <span style="color: #1e3a5f; font-weight: 600; font-size: 14px;">All Products</span>
            </div>
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="background: #ecfdf3; color: #067647; padding: 6px 14px; border-radius: 40px; font-size: 13px; font-weight: 600;">
                    <i class="fas fa-circle" style="font-size: 8px; margin-right: 6px;"></i>
                    {{ $products->total() }} Products Available
                </span>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Filters - Premium Dark Theme -->
            <div class="col-lg-3">
                <div style="position: sticky; top: 24px; background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid #edf2f7; overflow: hidden;">
                    <!-- Filter Header -->
                    <div style="background: linear-gradient(145deg, #1e3a5f, #152c44); padding: 20px 24px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <div style="width: 40px; height: 40px; background: rgba(255,255,255,0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-sliders-h" style="color: white; font-size: 18px;"></i>
                            </div>
                            <div>
                                <h5 style="color: white; font-size: 18px; font-weight: 700; margin: 0;">Filter Products</h5>
                                <p style="color: rgba(255,255,255,0.7); font-size: 13px; margin: 4px 0 0;">Find your perfect match</p>
                            </div>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div style="padding: 24px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                            <h6 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-folder-tree" style="color: #1e3a5f;"></i> Categories
                            </h6>
                            <span style="background: #f1f5f9; color: #1e3a5f; padding: 4px 12px; border-radius: 40px; font-size: 12px; font-weight: 600;">{{ $categories->count() }}</span>
                        </div>

                        @php
                            $categories = \App\Models\Category::where('status', 'active')
                                ->withCount(['products' => function($query) {
                                    $query->where('is_active', true)->where('status', 'in_stock');
                                }])
                                ->having('products_count', '>', 0)
                                ->get();
                        @endphp

                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <a href="{{ route('user.products.index') }}" 
                               style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 14px; text-decoration: none; background: {{ !request('category') ? '#1e3a5f' : 'transparent' }}; color: {{ !request('category') ? 'white' : '#1e293b' }}; transition: all 0.2s; font-weight: {{ !request('category') ? '600' : '500' }};">
                                <span style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-boxes" style="font-size: 14px; color: {{ !request('category') ? 'white' : '#94a3b8' }};"></i>
                                    All Categories
                                </span>
                                <span style="background: {{ !request('category') ? 'rgba(255,255,255,0.2)' : '#f1f5f9' }}; color: {{ !request('category') ? 'white' : '#64748b' }}; padding: 2px 10px; border-radius: 40px; font-size: 12px;">
                                    {{ \App\Models\Product::where('is_active', true)->where('status', 'in_stock')->count() }}
                                </span>
                            </a>

                            @foreach($categories as $category)
                                <div style="margin-bottom: 2px;">
                                    <a href="{{ route('user.products.index', ['category' => $category->slug]) }}" 
                                       style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 14px; text-decoration: none; background: {{ request('category') == $category->slug ? '#f0f7ff' : 'transparent' }}; color: {{ request('category') == $category->slug ? '#1e3a5f' : '#334155' }}; transition: all 0.2s; font-weight: {{ request('category') == $category->slug ? '600' : '400' }};">
                                        <span style="display: flex; align-items: center; gap: 10px;">
                                            <i class="fas fa-folder" style="font-size: 14px; color: {{ request('category') == $category->slug ? '#1e3a5f' : '#94a3b8' }};"></i>
                                            {{ $category->name }}
                                        </span>
                                        <span style="background: {{ request('category') == $category->slug ? '#1e3a5f' : '#f1f5f9' }}; color: {{ request('category') == $category->slug ? 'white' : '#64748b' }}; padding: 2px 10px; border-radius: 40px; font-size: 12px; font-weight: 600;">
                                            {{ $category->products_count }}
                                        </span>
                                    </a>

                                    @if($category->children->count() > 0)
                                        <div style="padding-left: 20px; margin-top: 4px;">
                                            @foreach($category->children()->where('status', 'active')->get() as $subCategory)
                                                @php
                                                    $subProductCount = \App\Models\Product::where('is_active', true)
                                                        ->where('status', 'in_stock')
                                                        ->where('category_id', $subCategory->id)
                                                        ->count();
                                                @endphp
                                                @if($subProductCount > 0)
                                                    <a href="{{ route('user.products.index', ['category' => $subCategory->slug]) }}" 
                                                       style="display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-radius: 12px; text-decoration: none; background: {{ request('category') == $subCategory->slug ? '#f8fafc' : 'transparent' }}; color: {{ request('category') == $subCategory->slug ? '#1e3a5f' : '#5b6f82' }}; font-size: 14px; transition: all 0.2s;">
                                                        <span style="display: flex; align-items: center; gap: 8px;">
                                                            <i class="fas fa-angle-right" style="font-size: 12px; color: #94a3b8;"></i>
                                                            {{ $subCategory->name }}
                                                        </span>
                                                        <span style="background: {{ request('category') == $subCategory->slug ? '#1e3a5f' : '#f1f5f9' }}; color: {{ request('category') == $subCategory->slug ? 'white' : '#64748b' }}; padding: 2px 8px; border-radius: 40px; font-size: 11px;">
                                                            {{ $subProductCount }}
                                                        </span>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brands -->
                    <div style="padding: 24px; border-top: 1px solid #edf2f7;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                            <h6 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 8px;">
                                <i class="fas fa-trademark" style="color: #1e3a5f;"></i> Top Brands
                            </h6>
                        </div>

                        @php
                            $brands = \App\Models\Brand::where('status', 'active')
                                ->withCount(['products' => function($query) {
                                    $query->where('is_active', true)->where('status', 'in_stock');
                                }])
                                ->having('products_count', '>', 0)
                                ->get();
                        @endphp

                        <div style="display: flex; flex-direction: column; gap: 4px; max-height: 260px; overflow-y: auto; padding-right: 4px;">
                            <a href="{{ route('user.products.index') }}" 
                               style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 14px; text-decoration: none; background: {{ !request('brand') ? '#f0f7ff' : 'transparent' }}; color: {{ !request('brand') ? '#1e3a5f' : '#334155' }}; font-weight: {{ !request('brand') ? '600' : '400' }};">
                                <span style="display: flex; align-items: center; gap: 10px;">
                                    <i class="fas fa-building" style="color: #94a3b8;"></i>
                                    All Brands
                                </span>
                            </a>

                            @foreach($brands as $brand)
                                <a href="{{ route('user.products.index', ['brand' => $brand->slug]) }}" 
                                   style="display: flex; align-items: center; justify-content: space-between; padding: 12px 16px; border-radius: 14px; text-decoration: none; background: {{ request('brand') == $brand->slug ? '#1e3a5f' : 'transparent' }}; color: {{ request('brand') == $brand->slug ? 'white' : '#334155' }}; transition: all 0.2s;">
                                    <span style="display: flex; align-items: center; gap: 10px;">
                                        @if($brand->logo)
                                            <img src="{{ asset('storage/'.$brand->logo) }}" style="width: 20px; height: 20px; object-fit: contain; border-radius: 4px;">
                                        @else
                                            <i class="fas fa-tag" style="color: {{ request('brand') == $brand->slug ? 'white' : '#94a3b8' }};"></i>
                                        @endif
                                        {{ $brand->name }}
                                    </span>
                                    <span style="background: {{ request('brand') == $brand->slug ? 'rgba(255,255,255,0.2)' : '#f1f5f9' }}; color: {{ request('brand') == $brand->slug ? 'white' : '#64748b' }}; padding: 2px 10px; border-radius: 40px; font-size: 12px;">
                                        {{ $brand->products_count }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div style="padding: 24px; border-top: 1px solid #edf2f7;">
                        <h6 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 24px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-rupee-sign" style="color: #1e3a5f;"></i> Price Range
                        </h6>

                        <input type="hidden" id="min_price" value="{{ request('min_price', 0) }}">
                        <input type="hidden" id="max_price" value="{{ request('max_price', 100000) }}">
                        
                        <div id="price-slider" style="margin: 20px 0 30px;"></div>

                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                            <div style="flex: 1;">
                                <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 6px;">Minimum</label>
                                <div style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0 12px; transition: all 0.2s;">
                                    <span style="color: #64748b; font-size: 14px; font-weight: 600;">₹</span>
                                    <input type="number" id="min-price-input" value="{{ request('min_price', 0) }}" 
                                           style="width: 100%; border: none; background: transparent; padding: 14px 8px; font-size: 14px; color: #1e293b; font-weight: 600;">
                                </div>
                            </div>
                            <div style="flex: 1;">
                                <label style="display: block; font-size: 12px; color: #64748b; margin-bottom: 6px;">Maximum</label>
                                <div style="display: flex; align-items: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 0 12px;">
                                    <span style="color: #64748b; font-size: 14px; font-weight: 600;">₹</span>
                                    <input type="number" id="max-price-input" value="{{ request('max_price', 100000) }}" 
                                           style="width: 100%; border: none; background: transparent; padding: 14px 8px; font-size: 14px; color: #1e293b; font-weight: 600;">
                                </div>
                            </div>
                        </div>

                        <button onclick="applyPriceFilter()" 
                                style="width: 100%; background: #1e3a5f; color: white; border: none; border-radius: 14px; padding: 16px; font-size: 15px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(30,58,95,0.2);">
                            <i class="fas fa-filter"></i> Apply Filter
                        </button>
                    </div>

                    <!-- Stock Status -->
                    <div style="padding: 24px; border-top: 1px solid #edf2f7;">
                        <h6 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-check-circle" style="color: #1e3a5f;"></i> Availability
                        </h6>
                        
                        <label style="display: flex; align-items: center; padding: 8px 0; cursor: pointer;">
                            <div style="position: relative; width: 24px; height: 24px; margin-right: 12px;">
                                <input type="checkbox" id="in-stock" onchange="toggleStockFilter()" {{ request('stock') == 'in_stock' ? 'checked' : '' }}
                                       style="width: 20px; height: 20px; border-radius: 6px; border: 2px solid #cbd5e1; cursor: pointer; accent-color: #1e3a5f;">
                            </div>
                            <span style="font-size: 15px; color: #1e293b; font-weight: 500;">In Stock Only</span>
                            @if(request('stock') == 'in_stock')
                                <span style="margin-left: 12px; background: #dcfce7; color: #166534; padding: 2px 12px; border-radius: 40px; font-size: 12px;">Active</span>
                            @endif
                        </label>
                    </div>

                    <!-- Clear Filters -->
                    @if(request()->has('category') || request()->has('brand') || request()->has('min_price') || request()->has('max_price') || request()->has('search') || request()->has('stock'))
                        <div style="padding: 24px; border-top: 1px solid #edf2f7; background: #f8fafc;">
                            <a href="{{ route('user.products.index') }}" 
                               style="display: flex; align-items: center; justify-content: center; gap: 8px; background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px; text-decoration: none; color: #ef4444; font-size: 14px; font-weight: 600; transition: all 0.2s;">
                                <i class="fas fa-times-circle"></i>
                                Clear All Filters
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <!-- Toolbar -->
                <div style="background: white; border-radius: 20px; padding: 16px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 12px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: #f0f7ff; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-cubes" style="color: #1e3a5f; font-size: 20px;"></i>
                        </div>
                        <div>
                            <span style="font-size: 15px; color: #5b6f82;">Showing</span>
                            <span style="font-size: 18px; font-weight: 700; color: #1e3a5f; margin: 0 4px;">{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span>
                            <span style="font-size: 15px; color: #5b6f82;">of</span>
                            <span style="font-size: 18px; font-weight: 700; color: #1e3a5f; margin-left: 4px;">{{ $products->total() }}</span>
                            <span style="font-size: 15px; color: #5b6f82; margin-left: 4px;">products</span>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-sort" style="color: #94a3b8;"></i>
                            <select onchange="sortProducts(this.value)" 
                                    style="border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; font-size: 14px; color: #1e293b; background: white; cursor: pointer; font-weight: 500; min-width: 180px;">
                                <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>📅 Latest Arrivals</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>💰 Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>💰 Price: High to Low</option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>🔥 Most Popular</option>
                                <option value="best_selling" {{ request('sort') == 'best_selling' ? 'selected' : '' }}>⚡ Best Selling</option>
                                <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>🎯 Biggest Discounts</option>
                            </select>
                        </div>

                        <div style="display: flex; background: #f1f5f9; border-radius: 14px; padding: 4px;">
                            <button onclick="changeView('grid')" 
                                    style="border: none; background: {{ request('view', 'grid') == 'grid' ? '#1e3a5f' : 'transparent' }}; color: {{ request('view', 'grid') == 'grid' ? 'white' : '#64748b' }}; padding: 10px 18px; border-radius: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 600;">
                                <i class="fas fa-th-large"></i> Grid
                            </button>
                            <button onclick="changeView('list')" 
                                    style="border: none; background: {{ request('view') == 'list' ? '#1e3a5f' : 'transparent' }}; color: {{ request('view') == 'list' ? 'white' : '#64748b' }}; padding: 10px 18px; border-radius: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; font-size: 14px; font-weight: 600;">
                                <i class="fas fa-list-ul"></i> List
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Search Results Banner -->
                @if(request('search'))
                    <div style="background: linear-gradient(145deg, #fef9e7, #fff5e0); border-left: 6px solid #f59e0b; border-radius: 16px; padding: 20px 24px; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="width: 48px; height: 48px; background: white; border-radius: 14px; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                                <i class="fas fa-search" style="color: #f59e0b; font-size: 20px;"></i>
                            </div>
                            <div>
                                <h6 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">Search Results</h6>
                                <p style="margin: 0; color: #5b6f82; font-size: 14px;">
                                    Showing results for "<span style="font-weight: 700; color: #1e3a5f; background: white; padding: 4px 12px; border-radius: 40px;">{{ request('search') }}</span>"
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('user.products.index') }}" 
                           style="display: flex; align-items: center; gap: 8px; color: #64748b; text-decoration: none; padding: 10px 20px; background: white; border-radius: 40px; font-size: 14px; font-weight: 600; transition: all 0.2s;">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                @endif

                @if($products->count() > 0)
                    <!-- Grid View - Premium Product Cards (MEDIUM SIZE - Flipkart/Amazon Style) -->
                    <div id="products-grid" style="display: {{ request('view', 'grid') == 'grid' ? 'block' : 'none' }};">
                        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                            @foreach($products as $product)
                                <div style="background: white; border-radius: 12px; overflow: hidden; transition: all 0.3s ease; border: 1px solid #edf2f7; position: relative;"
                                     onmouseover="this.style.boxShadow='0 8px 20px rgba(0,0,0,0.08)'; this.style.transform='translateY(-2px)'; this.style.borderColor='#e2e8f0'"
                                     onmouseout="this.style.boxShadow='none'; this.style.transform='translateY(0)'; this.style.borderColor='#edf2f7'">
                                    
                                    <!-- Product Badges - Compact -->
                                    <div style="position: absolute; top: 12px; left: 12px; z-index: 10; display: flex; flex-direction: column; gap: 6px;">
                                        @if($product->has_discount)
                                            <span style="background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700; letter-spacing: 0.3px; box-shadow: 0 2px 6px rgba(239,68,68,0.2);">
                                                -{{ round($product->discount_percentage) }}%
                                            </span>
                                        @endif
                                        @if($product->is_new)
                                            <span style="background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 700;">
                                                NEW
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Wishlist Button - Compact -->
                                    <button onclick="addToWishlist({{ $product->id }})" 
                                            style="position: absolute; top: 12px; right: 12px; width: 32px; height: 32px; border-radius: 50%; border: none; background: white; color: #ef4444; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 20; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.2s; opacity: 0; transform: scale(0.9);"
                                            onmouseover="this.style.background='#ef4444'; this.style.color='white'; this.style.transform='scale(1.05)'"
                                            onmouseout="this.style.background='white'; this.style.color='#ef4444'; this.style.transform='scale(1)'">
                                        <i class="far fa-heart" style="font-size: 14px;"></i>
                                    </button>

                                    <!-- Product Image - Reduced Height -->
                                    <div style="background: #fafafc; padding: 20px 16px; display: flex; align-items: center; justify-content: center; position: relative; height: 180px;">
                                        <a href="{{ route('user.products.show', $product->slug) }}" style="display: block; text-decoration: none;">
                                            <div style="position: relative; width: 100%; height: 140px; display: flex; align-items: center; justify-content: center;">
                                                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/no-image.png') }}" 
                                                     style="max-width: 100%; max-height: 100%; object-fit: contain; transition: transform 0.25s ease;"
                                                     alt="{{ $product->name }}"
                                                     onmouseover="this.style.transform='scale(1.05)'"
                                                     onmouseout="this.style.transform='scale(1)'">
                                            </div>
                                        </a>

                                        @if($product->stock_level == 'out_of_stock')
                                            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center; z-index: 15;">
                                                <span style="background: #1e293b; color: white; padding: 6px 16px; border-radius: 30px; font-size: 11px; font-weight: 700;">OUT OF STOCK</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Details - Compact -->
                                    <div style="padding: 14px 14px 16px;">
                                        <!-- Category & Brand -->
                                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 8px; flex-wrap: wrap;">
                                            @if($product->category)
                                                <a href="{{ route('user.products.index', ['category' => $product->category->slug]) }}" 
                                                   style="color: #1e3a5f; text-decoration: none; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.3px; background: #f0f7ff; padding: 3px 10px; border-radius: 30px;">
                                                    {{ $product->category->name }}
                                                </a>
                                            @endif
                                            @if($product->brand)
                                                <span style="color: #64748b; font-size: 10px; font-weight: 500; display: flex; align-items: center; gap: 3px;">
                                                    <i class="fas fa-certificate" style="color: #94a3b8; font-size: 9px;"></i>
                                                    {{ $product->brand->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Product Title - Compact -->
                                        <h3 style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; line-height: 1.4; height: 38px; overflow: hidden; color: #1e293b;">
                                            <a href="{{ route('user.products.show', $product->slug) }}" 
                                               style="color: inherit; text-decoration: none; transition: color 0.2s;"
                                               onmouseover="this.style.color='#1e3a5f'"
                                               onmouseout="this.style.color='#1e293b'">
                                                {{ Str::limit($product->name, 50) }}
                                            </a>
                                        </h3>

                                        <!-- Rating - Compact -->
                                        <div style="display: flex; align-items: center; gap: 6px; margin-bottom: 12px;">
                                            <div style="display: flex; align-items: center; gap: 3px;">
                                                <div style="display: flex; gap: 2px;">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $product->rating_avg)
                                                            <i class="fas fa-star" style="color: #f59e0b; font-size: 10px;"></i>
                                                        @elseif($i - 0.5 <= $product->rating_avg)
                                                            <i class="fas fa-star-half-alt" style="color: #f59e0b; font-size: 10px;"></i>
                                                        @else
                                                            <i class="far fa-star" style="color: #cbd5e1; font-size: 10px;"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <span style="color: #64748b; font-size: 11px; font-weight: 500; margin-left: 2px;">{{ number_format($product->rating_avg, 1) }}</span>
                                            </div>
                                            <span style="color: #94a3b8; font-size: 10px;">({{ $product->rating_count ?? 0 }})</span>
                                            
                                            @if($product->stock_level == 'low')
                                                <span style="margin-left: auto; background: #fffbeb; color: #b45309; padding: 2px 8px; border-radius: 30px; font-size: 9px; font-weight: 600; display: flex; align-items: center; gap: 3px;">
                                                    <i class="fas fa-exclamation-circle" style="font-size: 8px;"></i>
                                                    Low stock
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Price & Add to Cart - Compact -->
                                        <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid #edf2f7; margin-top: 8px; padding-top: 12px;">
                                            <div>
                                                <span style="font-size: 18px; font-weight: 800; color: #1e3a5f;">₹{{ number_format($product->current_price, 0) }}</span>
                                                @if($product->has_discount)
                                                    <span style="display: block; font-size: 11px; color: #94a3b8; text-decoration: line-through; margin-top: 2px;">₹{{ number_format($product->price, 0) }}</span>
                                                @endif
                                            </div>
                                            
                                            <button class="add-to-cart-btn" data-id="{{ $product->id }}" {{ $product->stock_level == 'out_of_stock' ? 'disabled' : '' }}
                                                    style="background: {{ $product->stock_level == 'out_of_stock' ? '#e2e8f0' : '#1e3a5f' }}; color: white; border: none; border-radius: 10px; padding: 8px 14px; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: {{ $product->stock_level != 'out_of_stock' ? '0 2px 6px rgba(30,58,95,0.2)' : 'none' }};"
                                                    onmouseover="if(!this.disabled) this.style.background='#152c44'; this.style.transform='translateY(-1px)'"
                                                    onmouseout="if(!this.disabled) this.style.background='#1e3a5f'; this.style.transform='translateY(0)'">
                                                @if($product->stock_level == 'out_of_stock')
                                                    <i class="fas fa-bell" style="font-size: 11px;"></i>
                                                    <span>Notify</span>
                                                @else
                                                    <i class="fas fa-shopping-cart" style="font-size: 11px;"></i>
                                                    <span>Add</span>
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- List View - Compact Style -->
                    <div id="products-list" style="display: {{ request('view') == 'list' ? 'block' : 'none' }};">
                        <div style="display: flex; flex-direction: column; gap: 16px;">
                            @foreach($products as $product)
                                <div style="background: white; border-radius: 14px; overflow: hidden; transition: all 0.2s; border: 1px solid #edf2f7;"
                                     onmouseover="this.style.boxShadow='0 6px 14px rgba(0,0,0,0.06)'; this.style.borderColor='#e2e8f0'"
                                     onmouseout="this.style.boxShadow='none'; this.style.borderColor='#edf2f7'">
                                    <div style="display: flex;">
                                        <!-- Image - Compact -->
                                        <div style="width: 140px; background: #fafafc; padding: 16px; display: flex; align-items: center; justify-content: center; position: relative;">
                                            <a href="{{ route('user.products.show', $product->slug) }}">
                                                <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/no-image.png') }}" 
                                                     style="max-width: 100%; max-height: 100px; object-fit: contain;"
                                                     alt="{{ $product->name }}">
                                            </a>
                                            @if($product->has_discount)
                                                <span style="position: absolute; top: 12px; left: 12px; background: linear-gradient(135deg, #ef4444, #dc2626); color: white; padding: 3px 10px; border-radius: 20px; font-size: 10px; font-weight: 700;">
                                                    -{{ round($product->discount_percentage) }}%
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Content - Compact -->
                                        <div style="flex: 1; padding: 16px 20px 16px 0; display: flex;">
                                            <div style="flex: 1; padding-right: 20px;">
                                                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px; flex-wrap: wrap;">
                                                    @if($product->category)
                                                        <a href="{{ route('user.products.index', ['category' => $product->category->slug]) }}" 
                                                           style="color: #1e3a5f; text-decoration: none; font-size: 11px; font-weight: 700; text-transform: uppercase; background: #f0f7ff; padding: 3px 12px; border-radius: 30px;">
                                                            {{ $product->category->name }}
                                                        </a>
                                                    @endif
                                                    @if($product->brand)
                                                        <span style="color: #5b6f82; font-size: 11px;">
                                                            <i class="fas fa-check-circle" style="color: #10b981; font-size: 10px;"></i> {{ $product->brand->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <h3 style="margin: 0 0 10px; font-size: 16px; font-weight: 600; color: #1e293b;">
                                                    <a href="{{ route('user.products.show', $product->slug) }}" style="color: inherit; text-decoration: none;">
                                                        {{ Str::limit($product->name, 60) }}
                                                    </a>
                                                </h3>
                                                
                                                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px; flex-wrap: wrap;">
                                                    <div style="display: flex; align-items: center; gap: 3px;">
                                                        <div style="display: flex; gap: 2px;">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                @if($i <= $product->rating_avg)
                                                                    <i class="fas fa-star" style="color: #f59e0b; font-size: 11px;"></i>
                                                                @elseif($i - 0.5 <= $product->rating_avg)
                                                                    <i class="fas fa-star-half-alt" style="color: #f59e0b; font-size: 11px;"></i>
                                                                @else
                                                                    <i class="far fa-star" style="color: #cbd5e1; font-size: 11px;"></i>
                                                                @endif
                                                            @endfor
                                                        </div>
                                                        <span style="color: #5b6f82; font-size: 11px; font-weight: 500; margin-left: 3px;">{{ number_format($product->rating_avg, 1) }}/5</span>
                                                    </div>
                                                    <span style="color: #94a3b8; font-size: 11px;">({{ $product->rating_count ?? 0 }} reviews)</span>
                                                    
                                                    @if($product->stock_level == 'in_stock')
                                                        <span style="background: #dcfce7; color: #166534; padding: 2px 10px; border-radius: 30px; font-size: 10px; font-weight: 600;">
                                                            <i class="fas fa-check-circle" style="font-size: 9px;"></i> In Stock
                                                        </span>
                                                    @elseif($product->stock_level == 'low')
                                                        <span style="background: #fffbeb; color: #b45309; padding: 2px 10px; border-radius: 30px; font-size: 10px; font-weight: 600;">
                                                            <i class="fas fa-exclamation-triangle" style="font-size: 9px;"></i> Low Stock
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                <p style="color: #5b6f82; font-size: 13px; line-height: 1.5; margin-bottom: 14px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                    {{ Str::limit($product->short_description, 100) }}
                                                </p>
                                                
                                                <div style="display: flex; align-items: center; gap: 12px;">
                                                    <div style="display: flex; align-items: baseline; gap: 6px;">
                                                        <span style="font-size: 20px; font-weight: 800; color: #1e3a5f;">₹{{ number_format($product->current_price, 0) }}</span>
                                                        @if($product->has_discount)
                                                            <span style="font-size: 13px; color: #94a3b8; text-decoration: line-through;">₹{{ number_format($product->price, 0) }}</span>
                                                            <span style="background: #f0f7ff; color: #1e3a5f; padding: 2px 10px; border-radius: 30px; font-size: 11px; font-weight: 600;">
                                                                Save ₹{{ number_format($product->price - $product->current_price, 0) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div style="width: 160px; display: flex; flex-direction: column; gap: 10px; justify-content: center;">
                                                <button class="add-to-cart-btn" data-id="{{ $product->id }}" {{ $product->stock_level == 'out_of_stock' ? 'disabled' : '' }}
                                                        style="width: 100%; background: {{ $product->stock_level == 'out_of_stock' ? '#e2e8f0' : '#1e3a5f' }}; color: white; border: none; border-radius: 12px; padding: 12px; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; transition: all 0.2s; box-shadow: 0 2px 6px rgba(30,58,95,0.15);">
                                                    <i class="fas fa-shopping-cart" style="font-size: 12px;"></i>
                                                    Add to Cart
                                                </button>
                                                <button onclick="addToWishlist({{ $product->id }})"
                                                        style="width: 100%; background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px; color: #5b6f82; font-size: 13px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 6px; cursor: pointer; transition: all 0.2s;">
                                                    <i class="far fa-heart"></i>
                                                    Wishlist
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div style="margin-top: 48px;">
                        <style>
                            .pagination {
                                display: flex;
                                gap: 8px;
                                list-style: none;
                                padding: 0;
                                margin: 0;
                                justify-content: center;
                            }
                            .pagination .page-item .page-link {
                                border: 1px solid #e2e8f0;
                                padding: 10px 18px;
                                border-radius: 12px;
                                color: #5b6f82;
                                font-size: 14px;
                                font-weight: 500;
                                text-decoration: none;
                                transition: all 0.2s;
                                display: block;
                                background: white;
                            }
                            .pagination .page-item.active .page-link {
                                background: #1e3a5f;
                                color: white;
                                border-color: #1e3a5f;
                                box-shadow: 0 4px 10px rgba(30,58,95,0.2);
                            }
                            .pagination .page-item .page-link:hover {
                                background: #f0f7ff;
                                border-color: #1e3a5f;
                                color: #1e3a5f;
                            }
                            @media (max-width: 768px) {
                                .pagination .page-item .page-link {
                                    padding: 8px 14px;
                                    font-size: 13px;
                                }
                            }
                        </style>
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty State - Premium -->
                    <div style="background: white; border-radius: 20px; padding: 60px 40px; text-align: center; border: 1px solid #edf2f7;">
                        <div style="width: 100px; height: 100px; background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 28px;">
                            <i class="fas fa-box-open" style="font-size: 40px; color: #94a3b8;"></i>
                        </div>
                        <h4 style="font-size: 22px; font-weight: 700; color: #1e3a5f; margin-bottom: 12px;">No Products Found</h4>
                        <p style="color: #5b6f82; font-size: 15px; margin-bottom: 28px; max-width: 380px; margin-left: auto; margin-right: auto;">
                            We couldn't find any products matching your current filters. Try adjusting your search criteria.
                        </p>
                        <a href="{{ route('user.products.index') }}" 
                           style="display: inline-flex; align-items: center; gap: 10px; background: #1e3a5f; color: white; padding: 14px 32px; border-radius: 40px; text-decoration: none; font-size: 15px; font-weight: 600; transition: all 0.2s; box-shadow: 0 4px 12px rgba(30,58,95,0.2);"
                           onmouseover="this.style.background='#152c44'; this.style.transform='translateY(-1px)'"
                           onmouseout="this.style.background='#1e3a5f'; this.style.transform='translateY(0)'">
                            <i class="fas fa-sync-alt"></i>
                            Reset All Filters
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- Premium Slider CSS -->
<style>
    /* NoUI Slider Premium */
    .noUi-target {
        background: #e2e8f0;
        border: none;
        box-shadow: none;
        border-radius: 40px;
        height: 6px;
    }
    
    .noUi-connect {
        background: linear-gradient(145deg, #1e3a5f, #152c44);
        box-shadow: 0 1px 3px rgba(30,58,95,0.2);
    }
    
    .noUi-horizontal .noUi-handle {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: white;
        border: 2px solid #1e3a5f;
        box-shadow: 0 2px 6px rgba(30,58,95,0.2);
        top: -7px;
        right: -10px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .noUi-horizontal .noUi-handle:hover {
        transform: scale(1.1);
    }
    
    .noUi-handle:before,
    .noUi-handle:after {
        display: none;
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 5px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 40px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 40px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Hover Animations - Show wishlist button on hover */
    [style*="grid-template-columns: repeat(3, 1fr)"] > div:hover button[style*="position: absolute; top: 12px; right: 12px;"] {
        opacity: 1 !important;
        transform: scale(1) !important;
    }
    
    /* Responsive */
    @media (max-width: 1200px) {
        [style*="grid-template-columns: repeat(3, 1fr)"] {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    @media (max-width: 768px) {
        [style*="grid-template-columns: repeat(3, 1fr)"] {
            grid-template-columns: 1fr !important;
        }
        
        [style*="grid-template-columns: repeat(3, 1fr)"] > div {
            max-width: 320px;
            margin: 0 auto;
            width: 100%;
        }
    }
</style>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.css">
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.1/nouislider.min.js"></script>
<script>
    // All JavaScript functions remain EXACTLY the same
    $(document).on('click', '.add-to-cart-btn', function(e) {
        e.preventDefault();
        let productId = $(this).data('id');
        let button = $(this);
        $.ajax({
            url: "{{ route('user.cart.add') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: productId,
                quantity: 1
            },
            success: function(response) {
                button.html('<i class="fas fa-check"></i> Added');
                button.prop('disabled', true);
                setTimeout(function() {
                    if(productId) {
                        button.html('<i class="fas fa-shopping-cart"></i> Add to Cart');
                    }
                    button.prop('disabled', false);
                }, 1500);
            },
            error: function() {
                alert('Something went wrong!');
            }
        });
    });

    $(document).ready(function() {
        const minPrice = {{ \App\Models\Product::where('is_active', true)->where('status', 'in_stock')->min('price') ?? 0 }};
        const maxPrice = {{ \App\Models\Product::where('is_active', true)->where('status', 'in_stock')->max('price') ?? 100000 }};
        const priceSlider = document.getElementById('price-slider');
        if (priceSlider) {
            noUiSlider.create(priceSlider, {
                start: [parseInt($('#min_price').val()), parseInt($('#max_price').val())],
                connect: true,
                range: {
                    'min': minPrice,
                    'max': maxPrice
                },
                step: 100
            });
            priceSlider.noUiSlider.on('update', function(values) {
                $('#min-price-input').val(Math.round(values[0]));
                $('#max-price-input').val(Math.round(values[1]));
            });
            $('#min-price-input').on('change', function() {
                priceSlider.noUiSlider.set([this.value, null]);
            });
            $('#max-price-input').on('change', function() {
                priceSlider.noUiSlider.set([null, this.value]);
            });
        }
    });
    
    function applyPriceFilter() {
        const minPrice = $('#min-price-input').val();
        const maxPrice = $('#max-price-input').val();
        let url = new URL(window.location.href);
        if (minPrice > 0) url.searchParams.set('min_price', minPrice);
        else url.searchParams.delete('min_price');
        if (maxPrice > 0 && maxPrice < 1000000) url.searchParams.set('max_price', maxPrice);
        else url.searchParams.delete('max_price');
        window.location.href = url.toString();
    }
    
    function sortProducts(sortBy) {
        let url = new URL(window.location.href);
        url.searchParams.set('sort', sortBy);
        window.location.href = url.toString();
    }
    
    function changeView(viewType) {
        const gridView = $('#products-grid');
        const listView = $('#products-list');
        const gridBtn = $('button[onclick="changeView(\'grid\')"]');
        const listBtn = $('button[onclick="changeView(\'list\')"]');
        let url = new URL(window.location.href);
        url.searchParams.set('view', viewType);
        window.history.pushState({}, '', url);
        if (viewType === 'grid') {
            gridView.show();
            listView.hide();
            gridBtn.css({'background': '#1e3a5f', 'color': 'white'});
            listBtn.css({'background': 'transparent', 'color': '#64748b'});
        } else {
            gridView.hide();
            listView.show();
            gridBtn.css({'background': 'transparent', 'color': '#64748b'});
            listBtn.css({'background': '#1e3a5f', 'color': 'white'});
        }
    }
    
    function toggleStockFilter() {
        const inStockOnly = $('#in-stock').is(':checked');
        let url = new URL(window.location.href);
        if (inStockOnly) url.searchParams.set('stock', 'in_stock');
        else url.searchParams.delete('stock');
        window.location.href = url.toString();
    }
    
    function addToWishlist(productId) {
        $.ajax({
            url: "{{ route('user.wishlist.add') }}",
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                product_id: productId
            },
            success: function(response) {
                if (response.success) {
                    alert('Product added to wishlist successfully!');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    window.location.href = "{{ route('login') }}";
                } else {
                    alert('Something went wrong!');
                }
            }
        });
    }
    
    function quickView(productId) {
        console.log('Quick view:', productId);
    }
</script>
@endpush