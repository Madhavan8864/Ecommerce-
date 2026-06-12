@extends('user.layouts.app')

@section('title', 'My Wishlist - eCart Electronics')
@section('content')

<section style="padding: 20px 0 70px; background: #f5f7fa;">
    <div class="container">
        @if($wishlistItems->count() > 0)
            <!-- Quick Actions Bar -->
            <div style="background: white; border-radius: 20px; padding: 20px 25px; margin-bottom: 35px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: linear-gradient(145deg, #00ffff20, #00ffff05); width: 50px; height: 50px; border-radius: 16px; display: flex; align-items: center; justify-content: center; border: 1px solid #00ffff30;">
                        <i class="fas fa-heart" style="color: #00c8c8; font-size: 22px;"></i>
                    </div>
                    <div>
                        <h6 style="font-size: 18px; font-weight: 700; color: #0d1b2a; margin: 0;">Your Electronics Wishlist</h6>
                        <p style="color: #5f6c80; font-size: 14px; margin: 5px 0 0;">
                            <span style="font-weight: 700; color: #00a8a8;">{{ $wishlistItems->count() }}</span> items saved • Last added {{ $wishlistItems->first()->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 15px;">
                    <form action="{{ route('user.wishlist.clear') }}" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to clear your entire wishlist?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                style="background: #fff5f5; border: 1px solid #ff4757; border-radius: 14px; padding: 12px 25px; color: #ff4757; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 10px; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#ff4757'; this.style.color='white'"
                                onmouseout="this.style.background='#fff5f5'; this.style.color='#ff4757'">
                            <i class="fas fa-trash-alt"></i>
                            Clear All
                        </button>
                    </form>
                    <a href="{{ route('user.products.index') }}" 
                       style="display: flex; align-items: center; gap: 10px; background: #f0f7ff; border-radius: 14px; padding: 12px 25px; color: #0d1b2a; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s; border: 1px solid #e0e7ff;"
                       onmouseover="this.style.background='#0d1b2a'; this.style.color='white'"
                       onmouseout="this.style.background='#f0f7ff'; this.style.color='#0d1b2a'">
                        <i class="fas fa-microchip"></i>
                        Discover More
                    </a>
                </div>
            </div>

            <!-- Products Grid - Electronics Style -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px;">
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->product;
                        $badgeColor = match($product->category->name ?? '') {
                            'Laptops' => ['bg' => '#4158D0', 'icon' => 'fa-laptop'],
                            'Smartphones' => ['bg' => '#FF8008', 'icon' => 'fa-mobile-alt'],
                            'Headphones' => ['bg' => '#8E2DE2', 'icon' => 'fa-headphones'],
                            'Gaming' => ['bg' => '#F7971E', 'icon' => 'fa-gamepad'],
                            'Cameras' => ['bg' => '#1A2980', 'icon' => 'fa-camera'],
                            default => ['bg' => '#2C3E50', 'icon' => 'fa-microchip']
                        };
                    @endphp
                    <div class="wishlist-item-card" 
                         data-id="{{ $item->id }}"
                         style="background: white; border-radius: 24px; overflow: hidden; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); border: 1px solid #edf2f7; position: relative; box-shadow: 0 5px 20px rgba(0,0,0,0.02);"
                         onmouseover="this.style.boxShadow='0 20px 35px -8px rgba(13,27,42,0.15)'; this.style.transform='translateY(-6px)'"
                         onmouseout="this.style.boxShadow='0 5px 20px rgba(0,0,0,0.02)'; this.style.transform='translateY(0)'">
                        
                        <!-- Tech Badge - Category Based -->
                        <div style="position: absolute; top: 15px; left: 15px; z-index: 10; background: {{ $badgeColor['bg'] }}; color: white; padding: 6px 16px; border-radius: 40px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px; box-shadow: 0 5px 15px {{ $badgeColor['bg'] }}40;">
                            <i class="fas {{ $badgeColor['icon'] }}"></i>
                            {{ $product->category->name ?? 'Electronics' }}
                        </div>

                        <!-- Remove Button - FIXED: Using a form for each item -->
                        <form action="{{ route('user.wishlist.remove', $item->id) }}" method="POST" style="position: absolute; top: 15px; right: 15px; z-index: 20; margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="button" 
                                    class="remove-wishlist-btn"
                                    onclick="confirmRemove(this)"
                                    style="width: 40px; height: 40px; border-radius: 50%; border: none; background: rgba(255,255,255,0.95); color: #ff4757; display: flex; align-items: center; justify-content: center; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.1); transition: all 0.2s; opacity: 0; transform: scale(0.8);"
                                    onmouseover="this.style.background='#ff4757'; this.style.color='white'; this.style.transform='scale(1.1)'"
                                    onmouseout="this.style.background='rgba(255,255,255,0.95)'; this.style.color='#ff4757'; this.style.transform='scale(1)'">
                                <i class="fas fa-times" style="font-size: 16px;"></i>
                            </button>
                        </form>

                        <!-- Product Image - Electronics Style -->
                        <div style="background: linear-gradient(145deg, #f8fafc, #f1f5f9); padding: 30px 20px; display: flex; align-items: center; justify-content: center; position: relative; height: 200px; border-bottom: 1px solid #f0f0f0;">
                            <a href="{{ route('user.products.show', $product->slug) }}" style="display: block; text-decoration: none;">
                                <div style="position: relative; width: 100%; height: 150px; display: flex; align-items: center; justify-content: center;">
                                    <img src="{{ $product->main_image ? asset('storage/' . $product->main_image) : asset('images/no-image.png') }}" 
                                         alt="{{ $product->name }}"
                                         style="max-width: 100%; max-height: 100%; width: auto; height: auto; object-fit: contain; transition: all 0.4s; filter: drop-shadow(0 5px 10px rgba(0,0,0,0.05));"
                                         onmouseover="this.style.transform='scale(1.08)'; this.style.filter='drop-shadow(0 10px 20px rgba(0,0,0,0.1))'"
                                         onmouseout="this.style.transform='scale(1)'; this.style.filter='drop-shadow(0 5px 10px rgba(0,0,0,0.05))'">
                                </div>
                            </a>
                            
                            @if($product->has_discount)
                                <div style="position: absolute; bottom: 15px; left: 15px; background: linear-gradient(145deg, #ff416c, #ff4b2b); color: white; padding: 6px 16px; border-radius: 40px; font-size: 12px; font-weight: 800; letter-spacing: 0.5px; box-shadow: 0 5px 15px rgba(255,75,43,0.3);">
                                    🔥 {{ round($product->discount_percentage) }}% OFF
                                </div>
                            @endif

                            @if($product->stock_level == 'out_of_stock')
                                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.9); backdrop-filter: blur(2px); display: flex; align-items: center; justify-content: center;">
                                    <span style="background: #1e293b; color: white; padding: 10px 25px; border-radius: 40px; font-size: 13px; font-weight: 700; letter-spacing: 1px;">OUT OF STOCK</span>
                                </div>
                            @endif
                        </div>

                        <!-- Product Details -->
                        <div style="padding: 20px 20px 25px;">
                            <!-- Product Title -->
                            <h3 style="margin: 0 0 12px 0; font-size: 16px; font-weight: 700; line-height: 1.45; color: #0d1b2a; height: 46px; overflow: hidden;">
                                <a href="{{ route('user.products.show', $product->slug) }}" 
                                   style="color: inherit; text-decoration: none; transition: color 0.2s;"
                                   onmouseover="this.style.color='#00a8a8'"
                                   onmouseout="this.style.color='#0d1b2a'">
                                    {{ Str::limit($product->name, 45) }}
                                </a>
                            </h3>

                            <!-- Specs Badges - Electronics Style -->
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 15px; flex-wrap: wrap;">
                                @if($product->brand)
                                    <span style="background: #f0f7ff; color: #0d1b2a; padding: 4px 14px; border-radius: 40px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-certificate" style="color: #00a8a8;"></i>
                                        {{ $product->brand->name }}
                                    </span>
                                @endif
                                
                                @if($product->specifications && isset($product->specifications['processor']))
                                    <span style="background: #edf2f7; color: #4a5568; padding: 4px 12px; border-radius: 40px; font-size: 11px; font-weight: 600;">
                                        <i class="fas fa-microchip"></i> {{ $product->specifications['processor'] }}
                                    </span>
                                @endif
                                
                                @if($product->specifications && isset($product->specifications['ram']))
                                    <span style="background: #edf2f7; color: #4a5568; padding: 4px 12px; border-radius: 40px; font-size: 11px; font-weight: 600;">
                                        <i class="fas fa-memory"></i> {{ $product->specifications['ram'] }}
                                    </span>
                                @endif
                            </div>

                            <!-- Rating -->
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 16px;">
                                <div style="display: flex; align-items: center; gap: 4px;">
                                    <div style="display: flex; gap: 2px;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $product->rating_avg)
                                                <i class="fas fa-star" style="color: #ffb800; font-size: 12px;"></i>
                                            @elseif($i - 0.5 <= $product->rating_avg)
                                                <i class="fas fa-star-half-alt" style="color: #ffb800; font-size: 12px;"></i>
                                            @else
                                                <i class="far fa-star" style="color: #cbd5e0; font-size: 12px;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <span style="color: #4a5568; font-size: 12px; font-weight: 600; margin-left: 4px;">{{ number_format($product->rating_avg, 1) }}</span>
                                </div>
                                <span style="color: #94a3b8; font-size: 12px;">({{ $product->rating_count ?? 0 }} reviews)</span>
                            </div>

                            <!-- Price & Stock -->
                            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 18px;">
                                <div>
                                    <span style="font-size: 24px; font-weight: 800; color: #0d1b2a; letter-spacing: -0.5px;">₹{{ number_format($product->current_price, 0) }}</span>
                                    @if($product->has_discount)
                                        <span style="display: block; font-size: 13px; color: #94a3b8; text-decoration: line-through; margin-top: 2px;">₹{{ number_format($product->price, 0) }}</span>
                                    @endif
                                </div>
                                
                                <div>
                                    @if($product->stock_level == 'in_stock')
                                        <span style="background: #00ff8808; border: 1px solid #00ff8840; color: #00a86b; padding: 6px 14px; border-radius: 40px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-circle" style="font-size: 8px;"></i> In Stock
                                        </span>
                                    @elseif($product->stock_level == 'low')
                                        <span style="background: #ffb80010; border: 1px solid #ffb80040; color: #b76e00; padding: 6px 14px; border-radius: 40px; font-size: 12px; font-weight: 700;">
                                            <i class="fas fa-exclamation-triangle"></i> Low Stock
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons - Tech Style -->
                            <div style="display: flex; gap: 10px;">
                                <form action="{{ route('user.cart.add') }}" method="POST" style="flex: 1;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" 
                                            {{ $product->stock_level == 'out_of_stock' ? 'disabled' : '' }}
                                            style="width: 100%; background: linear-gradient(145deg, #0d1b2a, #1a2e40); border: none; border-radius: 14px; padding: 14px 10px; color: white; font-size: 14px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px; cursor: pointer; transition: all 0.3s; box-shadow: 0 5px 15px rgba(13,27,42,0.3);"
                                            onmouseover="if(!this.disabled) this.style.background='linear-gradient(145deg, #00a8a8, #007a7a)'; this.style.transform='translateY(-2px)'"
                                            onmouseout="if(!this.disabled) this.style.background='linear-gradient(145deg, #0d1b2a, #1a2e40)'; this.style.transform='translateY(0)'">
                                        <i class="fas fa-shopping-cart"></i>
                                        Add to Cart
                                    </button>
                                </form>
                                
                                <a href="{{ route('user.wishlist.moveToCart', $item->id) }}" 
                                   style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 16px; color: #4a5568; display: flex; align-items: center; justify-content: center; transition: all 0.2s;"
                                   onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#0d1b2a'; this.style.color='#0d1b2a'"
                                   onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.color='#4a5568'">
                                    <i class="fas fa-exchange-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Wishlist Footer -->
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 50px; padding: 30px 0 0; border-top: 1px solid #edf2f7;">
                <div style="display: flex; align-items: center; gap: 30px;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-bolt" style="color: #00a8a8; font-size: 20px;"></i>
                        <span style="color: #4a5568; font-size: 14px;">Express checkout available</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <i class="fas fa-shield-alt" style="color: #00a8a8; font-size: 20px;"></i>
                        <span style="color: #4a5568; font-size: 14px;">Secure payment</span>
                    </div>
                </div>
                
                <div style="display: flex; gap: 15px;">
                    <a href="{{ route('user.products.index') }}" 
                       style="background: transparent; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 28px; color: #4a5568; text-decoration: none; font-size: 14px; font-weight: 700; transition: all 0.2s;"
                       onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#0d1b2a'"
                       onmouseout="this.style.background='transparent'; this.style.borderColor='#e2e8f0'">
                        <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                    </a>
                    <a href="{{ route('user.cart.index') }}" 
                       style="background: #00a8a8; border: none; border-radius: 14px; padding: 14px 28px; color: white; text-decoration: none; font-size: 14px; font-weight: 700; transition: all 0.2s; box-shadow: 0 5px 15px rgba(0,168,168,0.3);"
                       onmouseover="this.style.background='#008080'; this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.background='#00a8a8'; this.style.transform='translateY(0)'">
                        <i class="fas fa-shopping-cart me-2"></i> View Cart
                    </a>
                </div>
            </div>
        @else
            <!-- Empty Wishlist - Electronics Theme -->
            <div style="background: white; border-radius: 40px; padding: 80px 40px; text-align: center; max-width: 800px; margin: 20px auto; box-shadow: 0 20px 40px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                <div style="width: 180px; height: 180px; background: linear-gradient(145deg, #00ffff10, #00a8a810); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; border: 2px dashed #00a8a8;">
                    <i class="fas fa-heart" style="font-size: 70px; color: #00a8a8;"></i>
                </div>
                
                <h2 style="font-size: 36px; font-weight: 800; color: #0d1b2a; margin-bottom: 16px; letter-spacing: -1px;">
                    Your wishlist is <span style="color: #00a8a8; border-bottom: 4px solid #00a8a8;">empty</span>
                </h2>
                
                <p style="color: #5f6c80; font-size: 18px; margin-bottom: 35px; max-width: 500px; margin-left: auto; margin-right: auto;">
                    Save your favorite electronics, gadgets, and tech accessories for later!
                </p>
                
                <div style="display: flex; gap: 20px; justify-content: center; margin-bottom: 50px;">
                    <a href="{{ route('user.products.index') }}" 
                       style="display: inline-flex; align-items: center; gap: 12px; background: linear-gradient(145deg, #0d1b2a, #1a2e40); color: white; padding: 18px 40px; border-radius: 40px; text-decoration: none; font-size: 16px; font-weight: 700; transition: all 0.3s; box-shadow: 0 10px 25px rgba(13,27,42,0.3);"
                       onmouseover="this.style.background='linear-gradient(145deg, #00a8a8, #007a7a)'; this.style.transform='translateY(-3px)'"
                       onmouseout="this.style.background='linear-gradient(145deg, #0d1b2a, #1a2e40)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-microchip"></i>
                        Explore Electronics
                    </a>
                    
                    <a href="{{ route('user.products.index', ['category' => 'deals']) }}" 
                       style="display: inline-flex; align-items: center; gap: 12px; background: white; border: 1px solid #e2e8f0; color: #4a5568; padding: 18px 40px; border-radius: 40px; text-decoration: none; font-size: 16px; font-weight: 700; transition: all 0.3s;"
                       onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#00a8a8'; this.style.color='#00a8a8'"
                       onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'; this.style.color='#4a5568'">
                        <i class="fas fa-fire"></i>
                        Today's Deals
                    </a>
                </div>
                
                <!-- Popular Categories - Electronics -->
                <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #edf2f7;">
                    <p style="color: #5f6c80; font-size: 14px; letter-spacing: 2px; margin-bottom: 20px; text-transform: uppercase;">
                        Popular Electronics Categories
                    </p>
                    <div style="display: flex; align-items: center; justify-content: center; gap: 15px; flex-wrap: wrap;">
                        <a href="{{ route('user.products.index', ['category' => 'laptops']) }}" style="background: #f8fafc; padding: 12px 28px; border-radius: 40px; color: #0d1b2a; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#00a8a8'; this.style.color='white'" onmouseout="this.style.background='#f8fafc'; this.style.color='#0d1b2a'">
                            <i class="fas fa-laptop"></i> Laptops
                        </a>
                        <a href="{{ route('user.products.index', ['category' => 'smartphones']) }}" style="background: #f8fafc; padding: 12px 28px; border-radius: 40px; color: #0d1b2a; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#00a8a8'; this.style.color='white'" onmouseout="this.style.background='#f8fafc'; this.style.color='#0d1b2a'">
                            <i class="fas fa-mobile-alt"></i> Smartphones
                        </a>
                        <a href="{{ route('user.products.index', ['category' => 'headphones']) }}" style="background: #f8fafc; padding: 12px 28px; border-radius: 40px; color: #0d1b2a; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#00a8a8'; this.style.color='white'" onmouseout="this.style.background='#f8fafc'; this.style.color='#0d1b2a'">
                            <i class="fas fa-headphones"></i> Audio
                        </a>
                        <a href="{{ route('user.products.index', ['category' => 'gaming']) }}" style="background: #f8fafc; padding: 12px 28px; border-radius: 40px; color: #0d1b2a; text-decoration: none; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: all 0.2s;" onmouseover="this.style.background='#00a8a8'; this.style.color='white'" onmouseout="this.style.background='#f8fafc'; this.style.color='#0d1b2a'">
                            <i class="fas fa-gamepad"></i> Gaming
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Custom Electronics Styles -->
<style>
    /* Remove spinner from number inputs */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Smooth animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    [style*="display: grid; grid-template-columns: repeat(4, 1fr);"] > div {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    /* Show remove button on card hover */
    .wishlist-item-card:hover form[style*="position: absolute; top: 15px; right: 15px;"] button {
        opacity: 1 !important;
        transform: scale(1) !important;
    }
    
    /* Responsive */
    @media (max-width: 1200px) {
        [style*="grid-template-columns: repeat(4, 1fr);"] {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    
    @media (max-width: 992px) {
        [style*="grid-template-columns: repeat(4, 1fr);"] {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    @media (max-width: 576px) {
        [style*="grid-template-columns: repeat(4, 1fr);"] {
            grid-template-columns: 1fr !important;
        }
        
        [style*="padding: 30px 20px;"] {
            padding: 20px 15px !important;
        }
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    
    ::-webkit-scrollbar-thumb {
        background: #94a3b8;
        border-radius: 40px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #0d1b2a;
    }
</style>

<script>

     function confirmRemove(button) {
        if (confirm('Remove this item from your wishlist?')) {
            button.closest('form').submit();
        }
    }
    // jQuery hover effect for remove button
    $(document).ready(function() {
        $('.wishlist-item-card').hover(
            function() {
                $(this).find('.remove-wishlist-btn').css({
                    'opacity': '1',
                    'transform': 'scale(1)'
                });
            },
            function() {
                $(this).find('.remove-wishlist-btn').css({
                    'opacity': '0',
                    'transform': 'scale(0.8)'
                });
            }
        );
    });
</script>
@endsection