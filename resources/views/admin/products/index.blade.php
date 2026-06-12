@extends('admin.layouts.app')

@section('title', 'Products')
@section('page-title', 'Products')

@section('breadcrumbs')
<li class="breadcrumb-item active">Products</li>
@endsection
@section('page-actions')
<div class="d-flex">
    <div class="me-3">
        <select class="form-select" id="statusFilter">
            <option value="">All Status</option>
            <option value="in_stock">In Stock</option>
            <option value="out_of_stock">Out of Stock</option>
            <option value="discontinued">Discontinued</option>
        </select>
    </div>
    <div class="me-3">
        <select class="form-select" id="categoryFilter">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i> Add Product
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover datatable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>SKU</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <img src="{{ $product->main_image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="img-thumbnail" 
                                         width="50" 
                                         height="50">
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $product->id) }}">
                                        {{ Str::limit($product->name, 30) }}
                                    </a>
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>
                                    <div>
                                        <strong>₹{{ number_format($product->current_price, 2) }}</strong>
                                        @if($product->has_discount)
                                            <br>
                                            <small class="text-muted text-decoration-line-through">
                                                ₹{{ number_format($product->price, 2) }}
                                            </small>
                                            <span class="badge bg-danger ms-1">
                                                {{ round($product->discount_percentage) }}% OFF
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($product->stock_level == 'out_of_stock')
                                        <span class="badge bg-danger">Out of Stock</span>
                                    @elseif($product->stock_level == 'low')
                                        <span class="badge bg-warning">Low ({{ $product->quantity }})</span>
                                    @else
                                        <span class="badge bg-success">{{ $product->quantity }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $product->is_active ? 'success' : 'danger' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-featured" 
                                               type="checkbox" 
                                               role="switch" 
                                               data-id="{{ $product->id }}"
                                               {{ $product->is_featured ? 'checked' : '' }}>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.products.show', $product->id) }}" 
                                           class="btn btn-sm btn-info" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                           class="btn btn-sm btn-primary" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger delete-product" 
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function () {

    /* =========================
       DELETE PRODUCT (FIXED)
    ========================== */
    $(document).on('click', '.delete-product', function () {

    let productId = $(this).data('id');
    let productName = $(this).data('name');

    Swal.fire({
        title: 'Are you sure?',
        text: 'Delete product: ' + productName,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: "{{ route('admin.products.destroy', '__id__') }}".replace('__id__', productId),
                type: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                },
                success: function (res) {
                    Swal.fire('Deleted!', res.message, 'success')
                        .then(() => location.reload());
                },
                error: function () {
                    Swal.fire('Error', 'Delete failed', 'error');
                }
            });
        }
    });
});



    /* =========================
       TOGGLE FEATURED (SAME)
    ========================== */
    $('.toggle-featured').change(function () {
        var productId = $(this).data('id');
        var isFeatured = $(this).is(':checked') ? 1 : 0;

        $.ajax({
            url: '{{ route("admin.products.toggleFeatured") }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                product_id: productId,
                is_featured: isFeatured
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                    $(this).prop('checked', !isFeatured);
                }
            },
            error: function () {
                toastr.error('Something went wrong!');
                $(this).prop('checked', !isFeatured);
            }
        });
    });


    /* =========================
       FILTER PRODUCTS (SAME)
    ========================== */
    $('#statusFilter, #categoryFilter').change(function () {

        var status = $('#statusFilter').val();
        var category = $('#categoryFilter').val();

        var url = '{{ route("admin.products.index") }}?';

        if (status) {
            url += 'status=' + status + '&';
        }

        if (category) {
            url += 'category=' + category;
        }

        window.location.href = url;
    });

});
</script>
@endpush
