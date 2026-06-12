@extends('user.layouts.app')

@section('title', $product->name . ' - eCart Electronics')
@section('content')

<!-- Page Header -->
<section class="page-header py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('user.products.index') }}">Products</a></li>
                        @if($product->category)
                            <li class="breadcrumb-item">
                                <a href="{{ route('user.products.index', ['category' => $product->category->slug]) }}">
                                    {{ $product->category->name }}
                                </a>
                            </li>
                        @endif
                        <li class="breadcrumb-item active">{{ $product->name }}</li>
                    </ol>
                </nav>
            </div>
            <div class="col-md-6 text-md-end">
                <div class="product-sku">
                    <small class="text-muted">SKU: {{ $product->sku }}</small>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details -->
<section class="product-details py-5">
    <div class="container">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-5">
                <div class="product-images">
                    <!-- Main Image -->
                    <div class="main-image mb-3">
                        <img src="{{ $product->main_image_url }}" 
                            alt="{{ $product->name }}" 
                            class="img-fluid rounded" 
                            id="mainProductImage">
                    </div>
                    
                    <!-- Thumbnails -->
                    @if($product->images && count($product->images_array) > 1)
                        <div class="thumbnails">
                            <div class="row">
                                @foreach($product->images_array as $image)
                                    <div class="col-3 mb-2">
                                        <img src="{{ $image }}" 
                                             class="img-thumbnail thumbnail-img" 
                                             alt="Thumbnail"
                                             width="80"
                                             data-full="{{ $image }}">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="col-lg-7">
                <div class="product-info">
                    <!-- Product Title -->
                    <h1 class="product-title mb-2">{{ $product->name }}</h1>
                    
                    <!-- Rating -->
                    <div class="product-rating mb-3">
                        <div class="rating">
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
                        <span class="ms-2">
                            {{ number_format($product->rating_avg, 1) }} ({{ $product->rating_count }} reviews)
                        </span>
                        @if($product->sold_count > 0)
                            <span class="ms-3">
                                <i class="fas fa-shopping-bag me-1"></i> {{ $product->sold_count }} sold
                            </span>
                        @endif
                    </div>
                    
                    <!-- Price -->
                    <div class="product-price mb-4">
                        <h2 class="text-primary mb-2">
                            ₹{{ number_format($product->current_price, 2) }}
                            @if($product->has_discount)
                                <small class="text-muted text-decoration-line-through fs-4">
                                    ₹{{ number_format($product->price, 2) }}
                                </small>
                                <span class="badge bg-danger fs-6">
                                    {{ round($product->discount_percentage) }}% OFF
                                </span>
                            @endif
                        </h2>
                        <div class="text-success">
                            <i class="fas fa-check-circle me-1"></i>
                            Inclusive of all taxes
                        </div>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="product-stock mb-4">
                        @if($product->stock_level == 'out_of_stock')
                            <div class="alert alert-danger mb-0">
                                <i class="fas fa-times-circle me-2"></i>
                                Out of Stock
                            </div>
                        @elseif($product->stock_level == 'low')
                            <div class="alert alert-warning mb-0">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Only {{ $product->quantity }} items left in stock
                            </div>
                        @else
                            <div class="alert alert-success mb-0">
                                <i class="fas fa-check-circle me-2"></i>
                                In Stock
                            </div>
                        @endif
                    </div>
                    
                    <!-- Short Description -->
                    @if($product->short_description)
                        <div class="product-short-desc mb-4">
                            <p class="mb-0">{{ $product->short_description }}</p>
                        </div>
                    @endif
                    
                    <!-- Product Meta -->
                    <div class="product-meta mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="meta-item mb-2">
                                    <strong>Category:</strong>
                                    <a href="{{ route('user.products.index', ['category' => $product->category->slug]) }}">
                                        {{ $product->category->name ?? 'N/A' }}
                                    </a>
                                </div>
                                <div class="meta-item mb-2">
                                    <strong>Brand:</strong>
                                    @if($product->brand)
                                        <a href="{{ route('user.products.index', ['brand' => $product->brand->slug]) }}">
                                            {{ $product->brand->name }}
                                        </a>
                                    @else
                                        N/A
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="meta-item mb-2">
                                    <strong>SKU:</strong> {{ $product->sku }}
                                </div>
                                <div class="meta-item mb-2">
                                    <strong>Weight:</strong> {{ $product->weight ? $product->weight . ' kg' : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quantity & Actions -->
                    <div class="product-actions mb-5">
                        <form id="addToCartForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <div class="row align-items-center">
                                <div class="col-lg-4 col-md-6 mb-3 mb-md-0">
                                    <div class="quantity-selector">
                                        <label class="form-label fw-bold">Quantity:</label>
                                        <div class="input-group" style="width: 140px;">
                                            <button type="button" class="btn btn-outline-secondary" id="decreaseQty">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" 
                                                   class="form-control text-center" 
                                                   id="quantity" 
                                                   name="quantity" 
                                                   value="1" 
                                                   min="{{ $product->min_order_quantity }}" 
                                                   max="{{ $product->max_order_quantity }}"
                                                   readonly
                                                   style="background: white;">
                                            <button type="button" class="btn btn-outline-secondary" id="increaseQty">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                        <div class="mt-2">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-info-circle me-1"></i>
                                                Min: {{ $product->min_order_quantity }} | Max: {{ $product->max_order_quantity }}
                                            </small>
                                            <small class="text-info d-block mt-1">
                                                <i class="fas fa-box me-1"></i>
                                                Available Stock: <strong id="stockCount">{{ $product->quantity }}</strong> units
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8 col-md-4">
                                    <div class="action-buttons">
                                        <button type="button" 
                                                class="btn btn-primary btn-lg me-3" 
                                                onclick="addToCartDirect({{ $product->id }})"
                                                {{ $product->stock_level == 'out_of_stock' ? 'disabled' : '' }}>
                                            <i class="fas fa-cart-plus "></i> Add to Cart
                                        </button><br><br>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-lg" 
                                                onclick="addToWishlist({{ $product->id }})">
                                            <i class="fas fa-heart me-2"></i> Add to Wishlist
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Share Product -->
                    <div class="product-share">
                        <h6 class="mb-3">Share this product:</h6>
                        <div class="share-buttons">
                            <a href="#" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="btn btn-outline-info btn-sm me-2">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="btn btn-outline-danger btn-sm me-2">
                                <i class="fab fa-pinterest"></i>
                            </a>
                            <a href="#" class="btn btn-outline-success btn-sm">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Tabs -->
        <div class="row mt-5">
            <div class="col-12">
                <ul class="nav nav-tabs" id="productTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button">
                            Description
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="specifications-tab" data-bs-toggle="tab" data-bs-target="#specifications" type="button">
                            Specifications
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button">
                            Features
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                            Reviews ({{ $reviews->total() }})
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content p-4 border border-top-0 rounded-bottom" id="productTabContent">
                    <!-- Description -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        <div class="product-description">
                            {!! $product->description !!}
                        </div>
                    </div>
                    
                    <!-- Specifications -->
                    <div class="tab-pane fade" id="specifications" role="tabpanel">
                        @if($product->specifications && !empty($product->specifications))
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach($product->specifications as $key => $value)
                                        @if(is_array($value))
                                            <tr>
                                                <th width="30%">{{ $value['key'] ?? $key }}</th>
                                                <td>{{ $value['value'] ?? '' }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <th width="30%">{{ $key }}</th>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-info">No specifications available.</div>
                        @endif
                    </div>
                    
                    <!-- Features -->
                    <div class="tab-pane fade" id="features" role="tabpanel">
                        @if($product->features && !empty($product->features))
                            <ul class="list-group">
                                @foreach($product->features as $feature)
                                    <li class="list-group-item">
                                        <i class="fas fa-check text-success me-2"></i>
                                        @if(is_array($feature))
                                            {{ $feature['value'] ?? '' }}
                                        @else
                                            {{ $feature }}
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="alert alert-info">No features available.</div>
                        @endif
                    </div>
                    
                    <!-- Reviews -->
                    <div class="tab-pane fade" id="reviews" role="tabpanel">
                        <div class="row">
                            <!-- Review Summary -->
                            <div class="col-md-4">
                                <div class="review-summary text-center p-4 border rounded">
                                    <h2 class="text-primary mb-2">{{ number_format($averageRating, 1) }}</h2>
                                    <div class="rating mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $averageRating)
                                                <i class="fas fa-star text-warning fs-4"></i>
                                            @elseif($i - 0.5 <= $averageRating)
                                                <i class="fas fa-star-half-alt text-warning fs-4"></i>
                                            @else
                                                <i class="far fa-star text-warning fs-4"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <p class="text-muted mb-0">{{ $ratingCount }} customer reviews</p>
                                    
                                    <!-- Rating Distribution -->
                                    <div class="rating-distribution mt-4">
                                        @foreach($ratingDistribution as $dist)
                                            <div class="d-flex align-items-center mb-2">
                                                <small class="me-2">{{ $dist->rating }} stars</small>
                                                <div class="progress flex-grow-1">
                                                    <div class="progress-bar bg-warning" 
                                                         style="width: {{ $ratingCount > 0 ? ($dist->count / $ratingCount * 100) : 0 }}%"></div>
                                                </div>
                                                <small class="ms-2">{{ $dist->count }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- Write Review Button -->
                                    @if(auth()->check())
                                        @if($hasReviewed)
                                            <div class="alert alert-info mt-4">
                                                <i class="fas fa-check-circle me-2"></i>
                                                You have already reviewed this product.
                                            </div>
                                        @else
                                            <button class="btn btn-primary mt-4 w-100" data-bs-toggle="modal" data-bs-target="#writeReviewModal">
                                                <i class="fas fa-pen me-2"></i> Write a Review
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary mt-4 w-100">
                                            <i class="fas fa-sign-in-alt me-2"></i> Login to Write Review
                                        </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Reviews List -->
                            <div class="col-md-8">
                                @if($reviews->count() > 0)
                                    @foreach($reviews as $review)
                                        @php
                                            $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8', '#F7D794', '#F3A683', '#786FA6', '#F19066', '#F5CD79', '#E77F67', '#CF6F8B', '#B53471', '#00B894', '#00CEC9', '#0984E3', '#6C5CE7', '#A8E6CF'];
                                            $name = $review->user->name ?? 'Anonymous';
                                            $firstLetter = strtoupper(substr($name, 0, 1));
                                            $colorIndex = abs(crc32($name)) % count($colors);
                                            $bgColor = $colors[$colorIndex];
                                        @endphp
                                        <div class="review-item mb-4 pb-4 border-bottom">
                                            <div class="d-flex justify-content-between mb-2">
                                                <div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-sm">
                                                            <div class="avatar-letter rounded-circle" style="background: {{ $bgColor }};">
                                                                {{ $firstLetter }}
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $review->user->name ?? 'Anonymous User' }}</h6>
                                                            <div class="rating">
                                                                @for($i = 1; $i <= 5; $i++)
                                                                    @if($i <= $review->rating)
                                                                        <i class="fas fa-star text-warning"></i>
                                                                    @else
                                                                        <i class="far fa-star text-warning"></i>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                            @if($review->title)
                                                <h6 class="mb-2 mt-2">{{ $review->title }}</h6>
                                            @endif
                                            <p class="mb-0">{{ $review->comment }}</p>
                                        </div>
                                    @endforeach
                                    
                                    <!-- Pagination -->
                                    <div class="mt-4">
                                        {{ $reviews->links() }}
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                                        <h4>No Reviews Yet</h4>
                                        <p class="text-muted">Be the first to review this product!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Products -->
@php
    $relatedProducts = \App\Models\Product::where('is_active', true)
        ->where('status', 'in_stock')
        ->where('category_id', $product->category_id)
        ->where('id', '!=', $product->id)
        ->with(['category', 'brand'])
        ->limit(8)
        ->get();
@endphp

@if($relatedProducts->count() > 0)
<section class="related-products py-5 bg-light">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">Related Products</h2>
            <p class="section-subtitle">You might also like these products</p>
        </div>
        
        <div class="row">
            @foreach($relatedProducts as $relatedProduct)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    @include('user.partials.product-card', ['product' => $relatedProduct])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Recently Viewed -->
@if(session('recently_viewed'))
<section class="recently-viewed py-5">
    <div class="container">
        <div class="section-header mb-5">
            <h2 class="section-title">Recently Viewed</h2>
        </div>
        
        <div class="row">
            @php
                $recentIds = session('recently_viewed', []);
                $recentProducts = \App\Models\Product::where('is_active', true)
                    ->where('status', 'in_stock')
                    ->whereIn('id', $recentIds)
                    ->where('id', '!=', $product->id)
                    ->with(['category', 'brand'])
                    ->limit(4)
                    ->get();
            @endphp
            
            @foreach($recentProducts as $recentProduct)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    @include('user.partials.product-card', ['product' => $recentProduct])
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Write Review Modal -->
@if(auth()->check() && !$hasReviewed)
<div class="modal fade" id="writeReviewModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('user.products.review', $product->id) }}" method="POST" id="reviewForm">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Write a Review for {{ $product->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Rating *</label>
                        <div class="rating-input">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="far fa-star fa-2x star" data-rating="{{ $i }}"></i>
                            @endfor
                            <input type="hidden" name="rating" id="selected-rating" required>
                            <div id="rating-error" class="text-danger small mt-1" style="display: none;">Please select a rating</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="review-title" class="form-label fw-bold">Review Title *</label>
                        <input type="text" class="form-control" id="review-title" name="title" 
                               placeholder="Summarize your experience" required>
                    </div>
                    <div class="mb-3">
                        <label for="review-comment" class="form-label fw-bold">Your Review *</label>
                        <textarea class="form-control" id="review-comment" name="comment" rows="4" 
                                  placeholder="Share your experience with this product" required></textarea>
                    </div>
                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-1"></i> 
                        Your review will be visible immediately. Thank you for sharing your feedback!
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitReviewBtn">
                        <i class="fas fa-paper-plane me-1"></i> Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('styles')
<style>
    .thumbnail-img {
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .thumbnail-img:hover,
    .thumbnail-img.active {
        border-color: #0d6efd;
    }
    
    .rating {
        color: #ffc107;
    }
    
    .product-description {
        line-height: 1.8;
    }
    
    .product-description img {
        max-width: 100% !important;
        height: auto !important;
    }
    
    .star {
        cursor: pointer;
        color: #ffc107;
        margin-right: 5px;
        transition: all 0.2s;
    }
    
    .star:hover {
        transform: scale(1.1);
    }
    
    .quantity-selector .input-group {
        width: 140px;
    }
    
    .quantity-selector input {
        font-weight: bold;
        background: white;
    }
    
    .quantity-selector .btn {
        font-weight: bold;
    }
    
    .share-buttons .btn {
        width: 40px;
        height: 40px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-sm {
        width: 40px;
        height: 40px;
        flex-shrink: 0;
    }
    
    .avatar-letter {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
    }
    
    .review-item {
        transition: all 0.2s;
    }
    
    .review-item:hover {
        background: #f8f9fa;
        padding-left: 10px;
    }
    
    .rating-distribution .progress {
        height: 8px;
        border-radius: 10px;
        background-color: #e9ecef;
    }
    
    .rating-distribution .progress-bar {
        border-radius: 10px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Thumbnail click
        $('.thumbnail-img').click(function() {
            const fullImage = $(this).data('full');
            $('#mainProductImage').attr('src', fullImage);
            $('.thumbnail-img').removeClass('active');
            $(this).addClass('active');
        });

        // Quantity variables
        const minQty = {{ $product->min_order_quantity }};
        const maxQty = {{ $product->max_order_quantity }};
        const stockQty = {{ $product->quantity }};
        const $quantityInput = $('#quantity');
        const $decreaseBtn = $('#decreaseQty');
        const $increaseBtn = $('#increaseQty');
        const $stockCount = $('#stockCount');
        
        // Function to update quantity
        function updateQuantity(value) {
            $quantityInput.val(value);
            // Optional: Update any other elements that show quantity
        }
        
        // Decrease quantity
        $decreaseBtn.click(function() {
            let currentVal = parseInt($quantityInput.val());
            if (currentVal > minQty) {
                updateQuantity(currentVal - 1);
                toastr.info('Quantity: ' + (currentVal - 1));
            } else {
                toastr.warning('Minimum quantity is ' + minQty);
            }
        });
        
        // Increase quantity
        $increaseBtn.click(function() {
            let currentVal = parseInt($quantityInput.val());
            if (currentVal < maxQty && currentVal < stockQty) {
                updateQuantity(currentVal + 1);
                toastr.info('Quantity: ' + (currentVal + 1));
            } else if (currentVal >= stockQty) {
                toastr.warning('Only ' + stockQty + ' items available in stock');
            } else if (currentVal >= maxQty) {
                toastr.warning('Maximum quantity is ' + maxQty);
            }
        });
        
        // Manual input change
        $quantityInput.on('change', function() {
            let value = parseInt($(this).val());
            
            if (isNaN(value) || value < minQty) {
                $(this).val(minQty);
                toastr.warning('Minimum quantity is ' + minQty);
            } else if (value > maxQty) {
                $(this).val(maxQty);
                toastr.warning('Maximum quantity is ' + maxQty);
            } else if (value > stockQty) {
                $(this).val(stockQty);
                toastr.warning('Only ' + stockQty + ' items available in stock');
            }
        });
        
        // Add to cart with quantity
        window.addToCartDirect = function(productId) {
            const quantity = $('#quantity').val();
            addToCart(productId, quantity);
        }
        
        // Rating stars in modal
        $('.star').hover(function() {
            const rating = $(this).data('rating');
            $('.star').each(function(i, star) {
                if ($(star).data('rating') <= rating) {
                    $(star).removeClass('far').addClass('fas');
                } else {
                    $(star).removeClass('fas').addClass('far');
                }
            });
        });

        $('.rating-input').mouseleave(function() {
            const selectedRating = $('#selected-rating').val();
            $('.star').each(function(i, star) {
                if ($(star).data('rating') <= selectedRating) {
                    $(star).removeClass('far').addClass('fas');
                } else {
                    $(star).removeClass('fas').addClass('far');
                }
            });
        });

        $('.star').click(function() {
            const rating = $(this).data('rating');
            $('#selected-rating').val(rating);
            $('#rating-error').hide();
            $('.star').each(function(i, star) {
                if ($(star).data('rating') <= rating) {
                    $(star).removeClass('far').addClass('fas');
                } else {
                    $(star).removeClass('fas').addClass('far');
                }
            });
        });
        
        // Review Form Submission
        $('#reviewForm').submit(function(e) {
            const rating = $('#selected-rating').val();
            if (!rating) {
                e.preventDefault();
                $('#rating-error').show();
                toastr.error('Please select a rating');
                return false;
            }
            
            const title = $('#review-title').val().trim();
            if (!title) {
                e.preventDefault();
                toastr.error('Please enter a review title');
                return false;
            }
            
            const comment = $('#review-comment').val().trim();
            if (!comment) {
                e.preventDefault();
                toastr.error('Please enter your review');
                return false;
            }
            
            $('#submitReviewBtn').html('<i class="fas fa-spinner fa-spin me-1"></i> Submitting...').prop('disabled', true);
        });
        
        // Reset modal on close
        $('#writeReviewModal').on('hidden.bs.modal', function() {
            $('#reviewForm')[0].reset();
            $('#selected-rating').val('');
            $('.star').removeClass('fas').addClass('far');
            $('#submitReviewBtn').html('<i class="fas fa-paper-plane me-1"></i> Submit Review').prop('disabled', false);
            $('#rating-error').hide();
        });
    });
</script>
@endpush