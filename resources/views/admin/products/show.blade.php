@extends('admin.layouts.app')

@section('title', 'Product Details')
@section('page-title', 'Product Details')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('admin.products.index') }}">Products</a>
</li>
<li class="breadcrumb-item active">{{ $product->name }}</li>
@endsection

@section('page-actions')
<a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
    <i class="fas fa-edit me-2"></i> Edit Product
</a>
<a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left me-2"></i> Back
</a>
@endsection
@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <!-- Product Images -->
                <div class="row mb-4">
                    <div class="col-md-5">
                        <!-- Main Image -->
                        <div class="main-image mb-3">
                        
                            <img src="{{ $product->main_image_url }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $product->name }}"
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
                    
                    <div class="col-md-7">
                        <h2 class="mb-3">{{ $product->name }}</h2>
                        
                        <!-- Price -->
                        <div class="mb-4">
                            <h3 class="text-primary mb-2">
                                ₹{{ number_format($product->current_price, 2) }}
                                @if($product->has_discount)
                                    <small class="text-muted text-decoration-line-through fs-5">
                                        ₹{{ number_format($product->price, 2) }}
                                    </small>
                                    <span class="badge bg-danger fs-6">
                                        {{ round($product->discount_percentage) }}% OFF
                                    </span>
                                @endif
                            </h3>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <dl>
                                    <dt>SKU</dt>
                                    <dd>{{ $product->sku }}</dd>
                                    
                                    <dt>Category</dt>
                                    <dd>{{ $product->category->name ?? 'N/A' }}</dd>
                                    
                                    <dt>Brand</dt>
                                    <dd>{{ $product->brand->name ?? 'N/A' }}</dd>
                                </dl>
                            </div>
                            <div class="col-md-6">
                                <dl>
                                    <dt>Stock Status</dt>
                                    <dd>
                                        <span class="badge bg-{{ $product->stock_level == 'out_of_stock' ? 'danger' : ($product->stock_level == 'low' ? 'warning' : 'success') }}">
                                            {{ $product->stock_status }}
                                        </span>
                                    </dd>
                                    
                                    <dt>Quantity</dt>
                                    <dd>{{ $product->quantity }}</dd>
                                    
                                    <dt>Views</dt>
                                    <dd>{{ $product->views }}</dd>
                                    
                                    <dt>Sold</dt>
                                    <dd>{{ $product->sold_count }}</dd>
                                </dl>
                            </div>
                        </div>
                        
                        <!-- Status Badges -->
                        <div class="mb-4">
                            <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }} me-2">
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            @if($product->is_featured)
                                <span class="badge bg-warning me-2">Featured</span>
                            @endif
                            <span class="badge bg-info">{{ $product->status }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Tabs -->
                <ul class="nav nav-tabs mb-4" id="productTab" role="tablist">
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
                            Reviews ({{ $product->reviews_count ?? 0 }})
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content" id="productTabContent">
                    <!-- Description -->
                    <div class="tab-pane fade show active" id="description" role="tabpanel">
                        @if($product->short_description)
                            <div class="mb-4">
                                <h5>Short Description</h5>
                                <p class="text-muted">{{ $product->short_description }}</p>
                            </div>
                        @endif
                        
                        <div>
                            <h5>Full Description</h5>
                            <div class="product-description">
                                {!! $product->description !!}
                            </div>
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
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h5 class="mb-0">Customer Reviews</h5>
                                <div class="d-flex align-items-center">
                                    <div class="rating me-2">
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
                                    <span class="text-muted">({{ $product->rating_avg }} / 5)</span>
                                </div>
                            </div>
                            <a href="{{ route('admin.reviews.index', ['product' => $product->id]) }}" 
                               class="btn btn-sm btn-primary">
                                View All Reviews
                            </a>
                        </div>
                        
                        @if($product->reviews && $product->reviews->count() > 0)
                            @foreach($product->reviews->take(5) as $review)
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <div>
                                                <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
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
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if($review->title)
                                            <h6 class="mb-2">{{ $review->title }}</h6>
                                        @endif
                                        <p class="mb-0">{{ $review->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="alert alert-info">No reviews yet.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <!-- Product Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Product Stats</h5>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-6">Created</dt>
                    <dd class="col-sm-6">{{ $product->created_at->format('d M Y, h:i A') }}</dd>
                    
                    <dt class="col-sm-6">Last Updated</dt>
                    <dd class="col-sm-6">{{ $product->updated_at->format('d M Y, h:i A') }}</dd>
                    
                    <dt class="col-sm-6">Slug</dt>
                    <dd class="col-sm-6">{{ $product->slug }}</dd>
                    
                    <dt class="col-sm-6">Weight</dt>
                    <dd class="col-sm-6">{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}</dd>
                    
                    <dt class="col-sm-6">Dimensions</dt>
                    <dd class="col-sm-6">{{ $product->dimensions ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-6">Min Order</dt>
                    <dd class="col-sm-6">{{ $product->min_order_quantity }}</dd>
                    
                    <dt class="col-sm-6">Max Order</dt>
                    <dd class="col-sm-6">{{ $product->max_order_quantity }}</dd>
                </dl>
            </div>
        </div>
        
        <!-- Sales Stats -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Sales Statistics</h5>
            </div>
            <div class="card-body">
                @php
                    $totalSales = $product->sold_count * $product->current_price;
                    $monthlySales = \App\Models\OrderItem::where('product_id', $product->id)
                        ->whereHas('order', function($q) {
                            $q->where('created_at', '>=', now()->subDays(30));
                        })
                        ->sum('total');
                @endphp
                
                <div class="text-center mb-4">
                    <h1 class="text-primary">{{ $product->sold_count }}</h1>
                    <p class="text-muted mb-0">Total Units Sold</p>
                </div>
                
                <dl class="row mb-0">
                    <dt class="col-sm-6">Total Revenue</dt>
                    <dd class="col-sm-6">₹{{ number_format($totalSales, 2) }}</dd>
                    
                    <dt class="col-sm-6">Last 30 Days</dt>
                    <dd class="col-sm-6">₹{{ number_format($monthlySales, 2) }}</dd>
                    
                    <dt class="col-sm-6">Average Rating</dt>
                    <dd class="col-sm-6">
                        <div class="d-flex align-items-center">
                            <div class="rating me-2">
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
                            <span>{{ $product->rating_avg }}/5</span>
                        </div>
                    </dd>
                    
                    <dt class="col-sm-6">Total Reviews</dt>
                    <dd class="col-sm-6">{{ $product->rating_count }}</dd>
                </dl>
            </div>
        </div>
        
        <!-- SEO Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">SEO Information</h5>
            </div>
            <div class="card-body">
                @if($product->meta_title || $product->meta_description)
                    <dl class="row mb-0">
                        @if($product->meta_title)
                            <dt class="col-sm-4">Meta Title</dt>
                            <dd class="col-sm-8">{{ Str::limit($product->meta_title, 50) }}</dd>
                        @endif
                        
                        @if($product->meta_description)
                            <dt class="col-sm-4">Meta Description</dt>
                            <dd class="col-sm-8">{{ Str::limit($product->meta_description, 100) }}</dd>
                        @endif
                        
                        @if($product->meta_keywords)
                            <dt class="col-sm-4">Meta Keywords</dt>
                            <dd class="col-sm-8">{{ Str::limit($product->meta_keywords, 100) }}</dd>
                        @endif
                    </dl>
                @else
                    <div class="alert alert-info mb-0">No SEO information set.</div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i> Edit Product
                    </a>
                    
                    @if($product->is_active)
                        <form action="{{ route('admin.products.toggleStatus', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="0">
                            <button type="submit" class="btn btn-warning w-100">
                                <i class="fas fa-ban me-2"></i> Deactivate
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.products.toggleStatus', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="1">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-check me-2"></i> Activate
                            </button>
                        </form>
                    @endif
                    
                    <form action="{{ route('admin.products.duplicate', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-copy me-2"></i> Duplicate Product
                        </button>
                    </form>
                    
                    <button type="button" 
                            class="btn btn-danger w-100 delete-product"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name }}">
                        <i class="fas fa-trash me-2"></i> Delete Product
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Image thumbnail click
        $('.thumbnail-img').click(function() {
            const fullImage = $(this).data('full');
            $('#mainProductImage').attr('src', fullImage);
            
            // Update active thumbnail
            $('.thumbnail-img').removeClass('active');
            $(this).addClass('active');
        });
        
        // Delete product
        $('.delete-product').click(function() {
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to delete product: " + productName,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url("admin/products") }}/' + productId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire(
                                    'Deleted!',
                                    response.message,
                                    'success'
                                ).then(() => {
                                    window.location.href = '{{ route("admin.products.index") }}';
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    response.message,
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'Something went wrong!',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>

<style>
    .thumbnail-img {
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .thumbnail-img:hover,
    .thumbnail-img.active {
        border-color: #0d6efd;
        transform: scale(1.05);
    }
    
    .rating {
        color: #ffc107;
    }
    
    .product-description {
        line-height: 1.8;
    }
    
    .product-description img {
        max-width: 100%;
        height: auto;
    }
</style>
@endpush