@extends('admin.layouts.app')

@section('title', 'Stock Management')
@section('page-title', 'Stock Management')

@section('breadcrumbs')
<li class="breadcrumb-item active">Stock</li>
@endsection

@section('page-actions')
<div class="btn-group gap-2">
    <button class="btn btn-success" onclick="exportStock()">
        <i class="fas fa-download"></i> Export
    </button>
    <a href="{{ route('admin.stock.movements') }}" class="btn btn-info">
        <i class="fas fa-history"></i> Movements
    </a>
    <a href="{{ route('admin.stock.alerts') }}" class="btn btn-warning">
        <i class="fas fa-exclamation-triangle"></i> Alerts
        @if($stats['low_stock'] > 0)
            <span class="badge bg-danger">{{ $stats['low_stock'] }}</span>
        @endif
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-12 mb-4">
        <div class="row">
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="stat-label">Total Products</div>
                    <div class="stat-value">{{ $stats['total_products'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <div class="stat-label">Total Stock</div>
                    <div class="stat-value">{{ $stats['total_stock'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div class="stat-label">In Stock</div>
                    <div class="stat-value">{{ $stats['in_stock'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-exclamation-triangle text-warning"></i>
                    </div>
                    <div class="stat-label">Low Stock</div>
                    <div class="stat-value">{{ $stats['low_stock'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle text-danger"></i>
                    </div>
                    <div class="stat-label">Out of Stock</div>
                    <div class="stat-value">{{ $stats['out_of_stock'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-rupee-sign"></i>
                    </div>
                    <div class="stat-label">Stock Value</div>
                    <div class="stat-value">₹{{ number_format($stats['total_value'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by product name or SKU..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="stock_status" class="form-select">
                            <option value="all">All Stock Status</option>
                            <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>In Stock (>10)</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock (1-10)</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock (0)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="category" class="form-select">
                            <option value="all">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Filter</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Stock Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Current Stock</th>
                                <th>Price</th>
                                <th>Stock Value</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr id="product-row-{{ $product->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $product->main_image_url }}" alt="{{ $product->name }}" 
                                             width="40" height="40" class="rounded me-2">
                                        <div>
                                            <strong>{{ Str::limit($product->name, 30) }}</strong>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $product->quantity > 10 ? 'success' : 
                                        ($product->quantity > 0 ? 'warning' : 'danger') 
                                    }} fs-6">
                                        {{ $product->quantity }}
                                    </span>
                                </td>
                                <td>₹{{ number_format($product->price, 2) }}</td>
                                <td>₹{{ number_format($product->quantity * $product->price, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $product->quantity > 10 ? 'success' : 
                                        ($product->quantity > 0 ? 'warning' : 'danger') 
                                    }}">
                                        {{ $product->stock_status }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary adjust-stock" 
                                            data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"
                                            data-stock="{{ $product->quantity }}">
                                        <i class="fas fa-edit"></i> Adjust
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>{{ $products->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Adjust Stock Modal -->
<div class="modal fade" id="adjustStockModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adjust Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="adjustStockForm">
                @csrf
                <input type="hidden" id="product_id" name="product_id">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" id="product_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Current Stock</label>
                        <input type="text" class="form-control" id="current_stock" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Adjustment Type</label>
                        <select class="form-select" name="type" id="adjust_type" required>
                            <option value="addition">Add Stock (+)</option>
                            <option value="removal">Remove Stock (-)</option>
                            <option value="adjustment">Set Exact Quantity</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Reason</label>
                        <textarea class="form-control" name="reason" rows="2" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stock</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Adjust stock button click
        $('.adjust-stock').click(function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const stock = $(this).data('stock');
            
            $('#product_id').val(id);
            $('#product_name').val(name);
            $('#current_stock').val(stock);
            $('#quantity').val('');
            $('#reason').val('');
            
            $('#adjustStockModal').modal('show');
        });
        
        // Adjust stock form submit
        $('#adjustStockForm').submit(function(e) {
            e.preventDefault();
            
            const productId = $('#product_id').val();
            const formData = {
                _token: '{{ csrf_token() }}',
                type: $('#adjust_type').val(),
                quantity: $('#quantity').val(),
                reason: $('#reason').val()
            };
            
            // Validate based on type
            const type = $('#adjust_type').val();
            const quantity = parseInt($('#quantity').val());
            const currentStock = parseInt($('#current_stock').val());
            
            if (type === 'removal' && quantity > currentStock) {
                toastr.error('Cannot remove more than current stock!');
                return;
            }
            
            $.ajax({
                url: '{{ route("admin.stock.adjust", ":id") }}'.replace(':id', productId),
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        $('#adjustStockModal').modal('hide');
                        toastr.success(response.message);
                        setTimeout(() => location.reload(), 1000);
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON?.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error(xhr.responseJSON?.message || 'Something went wrong!');
                    }
                }
            });
        });
        
        // Export function
        window.exportStock = function() {
            window.location.href = '{{ route("admin.stock.export") }}';
        };
    });
</script>
@endpush