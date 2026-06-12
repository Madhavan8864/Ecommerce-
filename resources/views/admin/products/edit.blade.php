@extends('admin.layouts.app')

@section('title', 'Edit Product')
@section('page-title', 'Edit Product')

@section('breadcrumbs')
<li class="breadcrumb-item">
    <a href="{{ route('admin.products.index') }}">Products</a>
</li>
<li class="breadcrumb-item active">Edit Product</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="name" class="form-label">Product Name *</label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $product->name) }}"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="sku" class="form-label">SKU *</label>
                                            <input type="text" 
                                                   class="form-control @error('sku') is-invalid @enderror" 
                                                   id="sku" 
                                                   name="sku" 
                                                   value="{{ old('sku', $product->sku) }}"
                                                   required>
                                            @error('sku')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="category_id" class="form-label">Category *</label>
                                            <select class="form-control select2 @error('category_id') is-invalid @enderror" 
                                                    id="category_id" 
                                                    name="category_id" 
                                                    required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" 
                                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="brand_id" class="form-label">Brand</label>
                                            <select class="form-control select2 @error('brand_id') is-invalid @enderror" 
                                                    id="brand_id" 
                                                    name="brand_id">
                                                <option value="">Select Brand</option>
                                                @foreach($brands as $brand)
                                                    <option value="{{ $brand->id }}" 
                                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                        {{ $brand->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('brand_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="short_description" class="form-label">Short Description</label>
                                        <textarea class="form-control @error('short_description') is-invalid @enderror" 
                                                  id="short_description" 
                                                  name="short_description" 
                                                  rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                                        @error('short_description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Max 500 characters</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description *</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror summernote" 
                                                  id="description" 
                                                  name="description" 
                                                  rows="5">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pricing -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Pricing</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="price" class="form-label">Price (₹) *</label>
                                            <input type="number" 
                                                   class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" 
                                                   name="price" 
                                                   value="{{ old('price', $product->price) }}"
                                                   step="0.01"
                                                   min="0"
                                                   required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="discount_price" class="form-label">Discount Price (₹)</label>
                                            <input type="number" 
                                                   class="form-control @error('discount_price') is-invalid @enderror" 
                                                   id="discount_price" 
                                                   name="discount_price" 
                                                   value="{{ old('discount_price', $product->discount_price) }}"
                                                   step="0.01"
                                                   min="0">
                                            @error('discount_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-4 mb-3">
                                            <label for="quantity" class="form-label">Quantity *</label>
                                            <input type="number" 
                                                   class="form-control @error('quantity') is-invalid @enderror" 
                                                   id="quantity" 
                                                   name="quantity" 
                                                   value="{{ old('quantity', $product->quantity) }}"
                                                   min="0"
                                                   required>
                                            @error('quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="min_order_quantity" class="form-label">Min Order Quantity</label>
                                            <input type="number" 
                                                   class="form-control @error('min_order_quantity') is-invalid @enderror" 
                                                   id="min_order_quantity" 
                                                   name="min_order_quantity" 
                                                   value="{{ old('min_order_quantity', $product->min_order_quantity) }}"
                                                   min="1">
                                            @error('min_order_quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="col-md-6 mb-3">
                                            <label for="max_order_quantity" class="form-label">Max Order Quantity</label>
                                            <input type="number" 
                                                   class="form-control @error('max_order_quantity') is-invalid @enderror" 
                                                   id="max_order_quantity" 
                                                   name="max_order_quantity" 
                                                   value="{{ old('max_order_quantity', $product->max_order_quantity) }}"
                                                   min="1">
                                            @error('max_order_quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Images -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Images</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Main Image -->
                                    <div class="mb-3">
                                        <label for="main_image" class="form-label">Main Image</label>
                                        @if($product->main_image)
                                            <div class="mb-2">
                                                <img src="{{ $product->main_image_url }}" 
                                                     class="img-thumbnail" 
                                                     width="150" 
                                                     height="150"
                                                     alt="{{ $product->name }}">
                                            </div>
                                        @endif
                                        <input type="file" 
                                               class="form-control @error('main_image') is-invalid @enderror" 
                                               id="main_image" 
                                               name="main_image"
                                               accept="image/*">
                                        @error('main_image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Main product image. Max file size: 2MB</small>
                                        <div class="mt-2 image-preview" id="mainImagePreview"></div>
                                    </div>
                                    
                                    <!-- Additional Images -->
                                    <div class="mb-3">
                                        <label class="form-label">Additional Images</label>
                                        
                                        <!-- Existing Images -->
                                        @if($product->images)
                                            <div class="row mb-3" id="existingImages">
                                                @foreach($product->images_array as $index => $image)
                                                    <div class="col-md-3 mb-2 image-item" data-index="{{ $index }}">
                                                        <div class="card">
                                                            <img src="{{ $image }}" 
                                                                 class="card-img-top" 
                                                                 alt="Product Image"
                                                                 height="100">
                                                            <div class="card-body p-2 text-center">
                                                                <button type="button" 
                                                                        class="btn btn-sm btn-danger remove-existing-image"
                                                                        data-index="{{ $index }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                        
                                        <!-- New Images -->
                                        <input type="file" 
                                               class="form-control @error('images') is-invalid @enderror" 
                                               id="images" 
                                               name="images[]"
                                               accept="image/*"
                                               multiple>
                                        @error('images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Additional product images. Max 5 images, 2MB each</small>
                                        <div class="mt-2 image-preview" id="additionalImagesPreview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <!-- Status & Settings -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Status & Settings</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Stock Status *</label>
                                        <select class="form-control @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status" 
                                                required>
                                            <option value="">Select Status</option>
                                            <option value="in_stock" {{ old('status', $product->status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                            <option value="out_of_stock" {{ old('status', $product->status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                            <option value="discontinued" {{ old('status', $product->status) == 'discontinued' ? 'selected' : '' }}>Discontinued</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_active" 
                                                   name="is_active" 
                                                   value="1"
                                                   {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Active Product
                                            </label>
                                        </div>
                                        <small class="text-muted">Inactive products won't be visible on the website</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_featured" 
                                                   name="is_featured" 
                                                   value="1"
                                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                Featured Product
                                            </label>
                                        </div>
                                        <small class="text-muted">Featured products will be highlighted on the homepage</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Specifications -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Specifications</h5>
                                </div>
                                <div class="card-body">
                                    <div id="specifications-container">
                                        @php
                                            $specCount = 0;
                                            $specifications = old('specifications', $product->specifications ?? []);
                                            if (!empty($specifications) && is_array($specifications)) {
                                                foreach($specifications as $key => $value) {
                                                    if (is_array($value)) {
                                                        $specKey = $value['key'] ?? '';
                                                        $specValue = $value['value'] ?? '';
                                                    } else {
                                                        $specKey = $key;
                                                        $specValue = $value;
                                                    }
                                        @endphp
                                        <div class="specification-item mb-2">
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="specifications[{{ $specCount }}][key]" 
                                                       placeholder="Key"
                                                       value="{{ $specKey }}">
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="specifications[{{ $specCount }}][value]" 
                                                       placeholder="Value"
                                                       value="{{ $specValue }}">
                                                <button type="button" class="btn btn-danger remove-spec">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @php
                                                    $specCount++;
                                                }
                                            } else {
                                        @endphp
                                        <div class="specification-item mb-2">
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="specifications[0][key]" 
                                                       placeholder="Key">
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="specifications[0][value]" 
                                                       placeholder="Value">
                                                <button type="button" class="btn btn-danger remove-spec">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @php
                                                $specCount = 1;
                                            }
                                        @endphp
                                    </div>
                                    <button type="button" id="add-specification" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Add Specification
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Features -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Features</h5>
                                </div>
                                <div class="card-body">
                                    <div id="features-container">
                                        @php
                                            $featureCount = 0;
                                            $features = old('features', $product->features ?? []);
                                            if (!empty($features) && is_array($features)) {
                                                foreach($features as $feature) {
                                                    if (is_array($feature)) {
                                                        $featureValue = $feature['value'] ?? '';
                                                    } else {
                                                        $featureValue = $feature;
                                                    }
                                        @endphp
                                        <div class="feature-item mb-2">
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="features[{{ $featureCount }}]" 
                                                       placeholder="Feature"
                                                       value="{{ $featureValue }}">
                                                <button type="button" class="btn btn-danger remove-feature">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @php
                                                    $featureCount++;
                                                }
                                            } else {
                                        @endphp
                                        <div class="feature-item mb-2">
                                            <div class="input-group">
                                                <input type="text" 
                                                       class="form-control" 
                                                       name="features[0]" 
                                                       placeholder="Feature">
                                                <button type="button" class="btn btn-danger remove-feature">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                        @php
                                                $featureCount = 1;
                                            }
                                        @endphp
                                    </div>
                                    <button type="button" id="add-feature" class="btn btn-sm btn-outline-primary mt-2">
                                        <i class="fas fa-plus me-1"></i> Add Feature
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Weight & Dimensions -->
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Shipping Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="weight" class="form-label">Weight (kg)</label>
                                        <input type="number" 
                                               class="form-control @error('weight') is-invalid @enderror" 
                                               id="weight" 
                                               name="weight" 
                                               value="{{ old('weight', $product->weight) }}"
                                               step="0.01"
                                               min="0">
                                        @error('weight')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="dimensions" class="form-label">Dimensions (LxWxH)</label>
                                        <input type="text" 
                                               class="form-control @error('dimensions') is-invalid @enderror" 
                                               id="dimensions" 
                                               name="dimensions" 
                                               value="{{ old('dimensions', $product->dimensions) }}"
                                               placeholder="e.g., 10x5x2">
                                        @error('dimensions')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- SEO Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">SEO Settings</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" 
                                           class="form-control @error('meta_title') is-invalid @enderror" 
                                           id="meta_title" 
                                           name="meta_title" 
                                           value="{{ old('meta_title', $product->meta_title) }}">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                    <input type="text" 
                                           class="form-control @error('meta_keywords') is-invalid @enderror" 
                                           id="meta_keywords" 
                                           name="meta_keywords" 
                                           value="{{ old('meta_keywords', $product->meta_keywords) }}">
                                    @error('meta_keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Separate keywords with commas</small>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                          id="meta_description" 
                                          name="meta_description" 
                                          rows="3">{{ old('meta_description', $product->meta_description) }}</textarea>
                                @error('meta_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Update Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let specCount = {{ $specCount }};
    let featureCount = {{ $featureCount }};
    
    $(document).ready(function() {
        // Image preview
        $('#main_image').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#mainImagePreview').html(`
                        <img src="${e.target.result}" 
                             class="img-thumbnail" 
                             width="150" 
                             height="150"
                             alt="Preview">
                    `);
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Multiple image preview
        $('#images').change(function() {
            const files = this.files;
            $('#additionalImagesPreview').html('');
            
            for (let i = 0; i < files.length; i++) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#additionalImagesPreview').append(`
                        <img src="${e.target.result}" 
                             class="img-thumbnail me-2 mb-2" 
                             width="100" 
                             height="100"
                             alt="Preview">
                    `);
                }
                reader.readAsDataURL(files[i]);
            }
        });
        
        // Remove existing image
        $('.remove-existing-image').click(function() {
            const index = $(this).data('index');
            const productId = {{ $product->id }};
            
            Swal.fire({
                title: 'Are you sure?',
                text: "This image will be removed from the product.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, remove it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.products.deleteImage", [$product->id, "__INDEX__"]) }}'.replace('__INDEX__', index),
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            image_index: index
                        },
                        success: function(response) {
                            if (response.success) {
                                $(`[data-index="${index}"]`).remove();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Something went wrong!');
                        }
                    });
                }
            });
        });
        
        // Add specification
        $('#add-specification').click(function() {
            const html = `
                <div class="specification-item mb-2">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="specifications[${specCount}][key]" 
                               placeholder="Key">
                        <input type="text" 
                               class="form-control" 
                               name="specifications[${specCount}][value]" 
                               placeholder="Value">
                        <button type="button" class="btn btn-danger remove-spec">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#specifications-container').append(html);
            specCount++;
        });
        
        // Remove specification
        $(document).on('click', '.remove-spec', function() {
            $(this).closest('.specification-item').remove();
        });
        
        // Add feature
        $('#add-feature').click(function() {
            const html = `
                <div class="feature-item mb-2">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="features[${featureCount}]" 
                               placeholder="Feature">
                        <button type="button" class="btn btn-danger remove-feature">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#features-container').append(html);
            featureCount++;
        });
        
        // Remove feature
        $(document).on('click', '.remove-feature', function() {
            $(this).closest('.feature-item').remove();
        });
        
        // Calculate discount percentage
        $('#price, #discount_price').on('input', function() {
            const price = parseFloat($('#price').val()) || 0;
            const discountPrice = parseFloat($('#discount_price').val()) || 0;
            
            if (discountPrice > 0 && price > 0) {
                const discountPercentage = ((price - discountPrice) / price) * 100;
                $('#discount_percentage').val(discountPercentage.toFixed(2));
            } else {
                $('#discount_percentage').val('');
            }
        });
    });
</script>
@endpush