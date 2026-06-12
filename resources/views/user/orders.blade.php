@extends('user.layouts.app')

@section('title', 'My Orders - eCart Electronics')
@section('content')
<!-- Page Header -->
<section class="page-header py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1 class="page-title">My Orders</h1>
                <p class="page-subtitle">Track and manage your orders</p>
            </div>
            <div class="col-md-6">
                <nav aria-label="breadcrumb" class="text-md-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('user.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">My Orders</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</section>

<!-- Orders Section -->
<section class="orders-section py-5">
    <div class="container">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('user.orders.index') }}" 
                                       class="btn {{ !request('status') || request('status') == 'all' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        All ({{ $orderStats['total'] }})
                                    </a>
                                    <a href="{{ route('user.orders.index', ['status' => 'pending']) }}" 
                                       class="btn {{ request('status') == 'pending' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Pending ({{ $orderStats['pending'] }})
                                    </a>
                                    <a href="{{ route('user.orders.index', ['status' => 'processing']) }}" 
                                       class="btn {{ request('status') == 'processing' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Processing ({{ $orderStats['processing'] }})
                                    </a>
                                    <a href="{{ route('user.orders.index', ['status' => 'shipped']) }}" 
                                       class="btn {{ request('status') == 'shipped' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Shipped ({{ $orderStats['shipped'] }})
                                    </a>
                                    <a href="{{ route('user.orders.index', ['status' => 'delivered']) }}" 
                                       class="btn {{ request('status') == 'delivered' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Delivered ({{ $orderStats['delivered'] }})
                                    </a>
                                    <a href="{{ route('user.orders.index', ['status' => 'cancelled']) }}" 
                                       class="btn {{ request('status') == 'cancelled' ? 'btn-primary' : 'btn-outline-primary' }}">
                                        Cancelled ({{ $orderStats['cancelled'] }})
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <form class="d-flex">
                                    <input type="text" 
                                           class="form-control" 
                                           placeholder="Search order #"
                                           name="search"
                                           value="{{ request('search') }}">
                                    <button class="btn btn-primary ms-2" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="row">
            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="col-12 mb-4">
                        <div class="card order-card">
                            <div class="card-header bg-light">
                                <div class="row align-items-center">
                                    <div class="col-md-6">
                                        <div class="order-info">
                                            <h6 class="mb-1">
                                                Order #{{ $order->order_number }}
                                                <span class="badge bg-{{ $order->status_color }} ms-2">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </h6>
                                            <small class="text-muted">
                                                Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-md-end">
                                        <div class="order-total">
                                            <strong>Total: ₹{{ number_format($order->total_amount, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- Order Items -->
                                @foreach($order->orderItems as $item)
                                    <div class="order-item d-flex mb-3 pb-3 border-bottom">
                                        <div class="order-item-image me-3">
                                            <img src="{{ $item->product->main_image_url }}" 
                                                 alt="{{ $item->product->name }}"
                                                 width="80">
                                        </div>
                                        <div class="order-item-info flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="{{ route('user.products.show', $item->product->slug) }}">
                                                    {{ $item->product->name }}
                                                </a>
                                            </h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                </div>
                                                <div class="col-md-4">
                                                    <small class="text-muted">Price: ₹{{ number_format($item->price, 2) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="order-item-total">
                                            <strong>₹{{ number_format($item->total, 2) }}</strong>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Order Actions -->
                                <div class="order-actions mt-4">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="order-status-info">
                                                @if($order->status == 'shipped' && $order->tracking_number)
                                                    <div class="tracking-info">
                                                        <small class="text-muted d-block">Tracking Number:</small>
                                                        <strong>{{ $order->tracking_number }}</strong>
                                                        @if($order->tracking_url)
                                                            <a href="{{ $order->tracking_url }}" target="_blank" class="ms-2">
                                                                Track <i class="fas fa-external-link-alt"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-outline-primary">
                                                    <i class="fas fa-eye me-2"></i> View Details
                                                </a>
                                                <a href="{{ route('user.orders.invoice', $order->id) }}" class="btn btn-outline-success" target="_blank">
                                                    <i class="fas fa-file-invoice me-2"></i> Invoice
                                                </a>
                                                @if($order->canBeCancelled())
                                                    <button class="btn btn-outline-danger" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#cancelOrderModal"
                                                            data-order-id="{{ $order->id }}">
                                                        <i class="fas fa-times me-2"></i> Cancel
                                                    </button>
                                                @endif
                                                @if($order->canBeReturned())
                                                    <button class="btn btn-outline-warning"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#returnOrderModal"
                                                            data-order-id="{{ $order->id }}">
                                                        <i class="fas fa-undo me-2"></i> Return
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="row">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            {{ $orders->links() }}
                        </nav>
                    </div>
                </div>
            @else
                <!-- No Orders -->
                <div class="col-12">
                    <div class="empty-orders text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h4>No Orders Found</h4>
                        <p class="text-muted mb-4">
                            @if(request('status') || request('search'))
                                No orders match your criteria.
                            @else
                                You haven't placed any orders yet.
                            @endif
                        </p>
                        <a href="{{ route('user.products.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i> Start Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="cancelOrderForm">
                @csrf
                <input type="hidden" name="order_id" id="cancel_order_id">
                <div class="modal-header">
                    <h5 class="modal-title">Cancel Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel this order?</p>
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Reason for cancellation *</label>
                        <textarea class="form-control" id="cancel_reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Order</button>
                    <button type="submit" class="btn btn-danger">Yes, Cancel Order</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Return Order Modal -->
<div class="modal fade" id="returnOrderModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="returnOrderForm">
                @csrf
                <input type="hidden" name="order_id" id="return_order_id">
                <div class="modal-header">
                    <h5 class="modal-title">Return Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Select items you want to return:</p>
                    <div id="returnItemsContainer">
                        <!-- Items will be loaded here -->
                    </div>
                    <div class="mb-3">
                        <label for="return_reason" class="form-label">Reason for return *</label>
                        <textarea class="form-control" id="return_reason" name="reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Submit Return Request</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Cancel Order
    $('#cancelOrderModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const orderId = button.data('order-id');
        $('#cancel_order_id').val(orderId);
    });
    
    $('#cancelOrderForm').submit(function(e) {
        e.preventDefault();
        
        const orderId = $('#cancel_order_id').val();
        const url = '{{ route("user.orders.cancel", ":id") }}'.replace(':id', orderId);
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#cancelOrderModal').modal('hide');
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
    
    // Return Order
    $('#returnOrderModal').on('show.bs.modal', function(event) {
        const button = $(event.relatedTarget);
        const orderId = button.data('order-id');
        $('#return_order_id').val(orderId);
        
        // Load returnable items
        $.ajax({
            url: '{{ route("user.orders.returnableItems", ":id") }}'.replace(':id', orderId),
            type: 'GET',
            success: function(response) {
                if (response.success) {
                    $('#returnItemsContainer').html(response.html);
                }
            }
        });
    });
    
    $('#returnOrderForm').submit(function(e) {
        e.preventDefault();
        
        // Validate at least one item selected
        const selectedItems = $('input[name="items[]"]:checked');
        if (selectedItems.length === 0) {
            toastr.error('Please select at least one item to return.');
            return;
        }
        
        // Validate quantities
        let valid = true;
        selectedItems.each(function() {
            const itemId = $(this).val();
            const maxQty = $(this).data('max-quantity');
            const inputQty = $(`#quantity_${itemId}`).val();
            
            if (inputQty < 1 || inputQty > maxQty) {
                valid = false;
                toastr.error(`Quantity for item ${itemId} must be between 1 and ${maxQty}`);
            }
        });
        
        if (!valid) return;
        
        const orderId = $('#return_order_id').val();
        const url = '{{ route("user.orders.return", ":id") }}'.replace(':id', orderId);
        
        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    $('#returnOrderModal').modal('hide');
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });
    
    // Toggle item selection for return
    $(document).on('change', 'input[name="items[]"]', function() {
        const itemId = $(this).val();
        const quantityInput = $(`#quantity_${itemId}`);
        
        if ($(this).is(':checked')) {
            quantityInput.prop('disabled', false);
            quantityInput.val(1);
        } else {
            quantityInput.prop('disabled', true);
            quantityInput.val(0);
        }
    });
</script>

<style>
    .order-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .order-item-image img {
        border-radius: 4px;
    }
    
    .order-actions .btn {
        min-width: 120px;
    }
    
    .empty-orders {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 60px 20px;
    }
    
    .return-item {
        padding: 10px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        margin-bottom: 10px;
    }
    
    .return-item.selected {
        border-color: #0d6efd;
        background-color: rgba(13, 110, 253, 0.05);
    }
</style>
@endpush    