<div class="product-card card h-100 border-0 shadow-sm hover-shadow-lg transition-all">
    <div class="position-relative overflow-hidden" style="aspect-ratio: 1 / 1; background: #f8f9fa;">
        <!-- Product Image -->
        <a href="{{ route('user.products.show', $product->slug) }}" class="d-block h-100 w-100">
            <img src="{{ $product->main_image_url }}" 
                 class="product-image w-100 h-100" 
                 alt="{{ $product->name }}"
                 style="object-fit: contain;"
                 onerror="this.onerror=null; this.src='{{ asset('images/default-product.png') }}';">
        </a>
        
        <!-- Wishlist Button (Top Right) -->
        <button class="btn btn-sm btn-light position-absolute top-0 end-0 mt-2 me-2 rounded-circle shadow-sm wishlist-btn" 
                onclick="toggleWishlist({{ $product->id }})"
                style="width: 36px; height: 36px; padding: 0; z-index: 10;">
            <i class="far fa-heart"></i>
        </button>
    </div>
    
    <div class="card-body d-flex flex-column">
        <!-- Product Badges - Now Below Image -->
        <div class="product-badges mb-2">
            @if($product->is_featured)
                <span class="badge-featured">
                    <i class="fas fa-star me-1"></i> Featured
                </span>
            @endif
            
            @if($product->isNew())
                <span class="badge-new">
                    <i class="fas fa-gift me-1"></i> New Arrival
                </span>
            @endif
            
            @if($product->has_discount)
                <span class="badge-discount">
                    <i class="fas fa-tag me-1"></i> -{{ round($product->discount_percentage) }}% OFF
                </span>
            @endif
        </div>
        
        <!-- Category & Brand -->
        <div class="mb-2">
            <small class="text-muted">
                @if($product->category)
                    <a href="{{ route('user.products.index', ['category' => $product->category->slug ?? '']) }}" 
                       class="text-decoration-none text-muted category-link">
                        <i class="fas fa-folder-open me-1"></i> {{ $product->category->name ?? 'Uncategorized' }}
                    </a>
                @else
                    <i class="fas fa-folder-open me-1"></i> Uncategorized
                @endif
                
                @if($product->brand)
                    <span class="mx-1">•</span>
                    <a href="{{ route('user.products.index', ['brand' => $product->brand->slug ?? '']) }}" 
                       class="text-decoration-none text-muted brand-link">
                        <i class="fas fa-tag me-1"></i> {{ $product->brand->name }}
                    </a>
                @endif
            </small>
        </div>
        
        <!-- Product Name -->
        <h5 class="card-title product-name mb-2">
            <a href="{{ route('user.products.show', $product->slug) }}"
               class="text-decoration-none text-dark">
                {{ \Illuminate\Support\Str::limit($product->name, 50) }}
            </a>
        </h5>
        
        <!-- Product Rating -->
        <div class="mb-2">
            <div class="d-flex align-items-center">
                <div class="star-rating me-2">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $product->rating_avg)
                            <i class="fas fa-star text-warning"></i>
                        @elseif($i - 0.5 <= $product->rating_avg)
                            <i class="fas fa-star-half-alt text-warning"></i>
                        @else
                            <i class="far fa-star text-warning"></i>
                        @endif
                    @endfor
                </div>
                <small class="text-muted">({{ $product->rating_count }} reviews)</small>
            </div>
        </div>
        
        <!-- Product Price & Stock -->
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div>
                    @if($product->has_discount)
                        <h5 class="text-danger mb-0 d-inline-block">
                            ₹{{ number_format($product->current_price, 2) }}
                        </h5>
                        <small class="text-muted text-decoration-line-through ms-2">
                            ₹{{ number_format($product->price, 2) }}
                        </small>
                    @else
                        <h5 class="text-primary mb-0">
                            ₹{{ number_format($product->price, 2) }}
                        </h5>
                    @endif
                </div>
                
                <!-- Stock Status -->
                @if($product->quantity <= 0)
                    <span class="badge-stock-out">
                        <i class="fas fa-times-circle me-1"></i> Out of Stock
                    </span>
                @elseif($product->quantity <= 10)
                    <span class="badge-stock-low">
                        <i class="fas fa-exclamation-triangle me-1"></i> Only {{ $product->quantity }} left
                    </span>
                @else
                    <span class="badge-stock-in">
                        <i class="fas fa-check-circle me-1"></i> In Stock
                    </span>
                @endif
            </div>
            
            <!-- Add to Cart Button -->
            <div class="d-grid gap-2 mt-3">
                @if($product->quantity <= 0)
                    <button class="btn btn-outline-secondary" disabled>
                        <i class="fas fa-ban me-2"></i> Out of Stock
                    </button>
                @else
                    <button class="btn btn-primary add-to-cart" 
                            onclick="addToCart({{ $product->id }})"
                            data-product-id="{{ $product->id }}">
                        <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .product-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
        background: white;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
    }
    
    .product-image {
        transition: transform 0.5s ease;
        object-fit: contain;
        background: #f8f9fa;
        padding: 20px;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    .product-name {
        height: 48px;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        font-size: 1rem;
        line-height: 1.4;
    }
    
    .product-name a {
        color: #212529;
        transition: color 0.3s;
    }
    
    .product-name a:hover {
        color: #0d6efd;
    }
    
    .star-rating {
        color: #ffc107;
        font-size: 12px;
    }
    
    .star-rating i {
        margin-right: 2px;
    }
    
    .add-to-cart {
        transition: all 0.3s ease;
        background: #0d6efd;
        border: none;
    }
    
    .add-to-cart:hover {
        background: #0b5ed7;
        transform: translateY(-2px);
    }
    
    /* Badge Styles - Clean Design */
    .product-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 12px;
    }
    
    .badge-featured {
        background: linear-gradient(135deg, #ffd89b 0%, #c7e9fb 100%);
        color: #856404;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.3px;
    }
    
    .badge-new {
        background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        color: #155724;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.3px;
    }
    
    .badge-discount {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        letter-spacing: 0.3px;
    }
    
    .badge-stock-in {
        background: #d4edda;
        color: #155724;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-stock-low {
        background: #fff3cd;
        color: #856404;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .badge-stock-out {
        background: #f8d7da;
        color: #721c24;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    
    .category-link,
    .brand-link {
        transition: color 0.2s;
        font-size: 12px;
    }
    
    .category-link:hover,
    .brand-link:hover {
        color: #0d6efd !important;
    }
    
    .wishlist-btn {
        background: white;
        border: 1px solid #dee2e6;
        transition: all 0.3s;
    }
    
    .wishlist-btn:hover {
        background: #dc3545;
        color: white;
        border-color: #dc3545;
    }
    
    .wishlist-btn:hover i {
        color: white;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .product-name {
            font-size: 14px;
            height: 42px;
        }
        
        .badge-featured,
        .badge-new,
        .badge-discount,
        .badge-stock-in,
        .badge-stock-low,
        .badge-stock-out {
            font-size: 10px;
            padding: 3px 8px;
        }
        
        .product-badges {
            gap: 6px;
        }
        
        .wishlist-btn {
            width: 30px !important;
            height: 30px !important;
        }
        
        .wishlist-btn i {
            font-size: 12px;
        }
    }
    
    @media (max-width: 576px) {
        .product-image {
            padding: 10px;
        }
        
        .category-link,
        .brand-link {
            font-size: 11px;
        }
    }
</style>

<script>
    function toggleWishlist(productId) {
        const button = event.currentTarget;
        const icon = button.querySelector('i');
        
        fetch('/user/wishlist/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                icon.classList.remove('far');
                icon.classList.add('fas');
                icon.style.color = '#dc3545';
                toastr.success('Added to wishlist!');
            } else if(data.requires_login) {
                window.location.href = '/login';
            } else {
                toastr.error(data.message || 'Failed to add to wishlist');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Something went wrong!');
        });
    }
    
    function addToCart(productId) {
        const quantity = 1;
        
        fetch('/user/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                toastr.success('Product added to cart!');
                updateCartCount();
            } else if(data.requires_login) {
                window.location.href = '/login';
            } else {
                toastr.error(data.message || 'Failed to add to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Something went wrong!');
        });
    }
    
    function updateCartCount() {
        fetch('/user/cart/count')
            .then(response => response.json())
            .then(data => {
                $('.cart-count').text(data.count);
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            });
    }
</script>