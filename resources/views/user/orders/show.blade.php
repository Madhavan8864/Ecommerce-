@extends('user.layouts.app')

@section('title', 'Order Details - ' . $order->order_number)
@section('page-title', 'Order Details')

@section('content')
<div class="container-fluid">
    <!-- Order Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5>Order #{{ $order->order_number }}</h5>
                            <p class="text-muted mb-0">
                                Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                            </p>
                        </div>
                        <div>
                            <span class="badge bg-{{ $order->status_color }} p-3">
                                <i class="fas fa-{{ $order->status == 'delivered' ? 'check-circle' : ($order->status == 'cancelled' ? 'times-circle' : 'clock') }} me-2"></i>
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Order Progress -->
                    @if(!in_array($order->status, ['cancelled', 'refunded']))
                    <div class="order-progress mt-4">
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" 
                                 role="progressbar" 
                                 style="width: {{ $order->order_progress }}%"
                                 aria-valuenow="{{ $order->order_progress }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <span class="small {{ $order->status == 'pending' ? 'fw-bold text-primary' : '' }}">
                                <i class="fas fa-check-circle me-1"></i> Pending
                            </span>
                            <span class="small {{ $order->status == 'processing' ? 'fw-bold text-primary' : '' }}">
                                <i class="fas fa-cog me-1"></i> Processing
                            </span>
                            <span class="small {{ $order->status == 'shipped' ? 'fw-bold text-primary' : '' }}">
                                <i class="fas fa-truck me-1"></i> Shipped
                            </span>
                            <span class="small {{ $order->status == 'delivered' ? 'fw-bold text-primary' : '' }}">
                                <i class="fas fa-home me-1"></i> Delivered
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-1">Need Help?</h6>
                            <p class="text-muted small mb-0">Contact our support team</p>
                        </div>
                        <a href="#" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-headset me-2"></i> Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Order Items -->
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-borderless mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3">Product</th>
                                    <th class="py-3">Price</th>
                                    <th class="py-3">Quantity</th>
                                    <th class="py-3 text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td class="py-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->main_image_url ?? asset('images/default-product.png') }}" 
                                                 alt="{{ $item->product->name }}"
                                                 class="rounded"
                                                 width="50"
                                                 height="50"
                                                 style="object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-3">₹{{ number_format($item->price, 2) }}</td>
                                    <td class="py-3">{{ $item->quantity }}</td>
                                    <td class="py-3 text-end">₹{{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Tracking Information -->
            @if($order->tracking_number)
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tracking Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Carrier</label>
                            <p class="mb-0 fw-bold">{{ $order->shipping_carrier ?? 'Standard Shipping' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tracking Number</label>
                            <p class="mb-0 fw-bold">{{ $order->tracking_number }}</p>
                        </div>
                    </div>
                    @if($order->tracking_url)
                    <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-primary">
                        <i class="fas fa-truck me-2"></i> Track Package
                    </a>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <!-- Order Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Shipping</span>
                        <span>₹{{ number_format($order->shipping_cost, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Tax</span>
                        <span>₹{{ number_format($order->tax, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="d-flex justify-content-between mb-2 text-success">
                        <span>Discount</span>
                        <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($order->coupon_code)
                    <div class="mb-3">
                        <span class="badge bg-success">Coupon: {{ $order->coupon_code }}</span>
                    </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="fw-bold">Total</span>
                        <span class="fw-bold text-primary h5 mb-0">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Payment Method</span>
                        <span class="text-uppercase">{{ $order->payment_method }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Payment Status</span>
                        <span class="badge bg-{{ $order->payment_status_color }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Address</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $order->shippingAddress->full_name ?? Auth::user()->name }}</h6>
                    <p class="mb-1">{{ $order->shippingAddress->address_line_1 ?? $order->shippingAddress->address ?? '' }}</p>
                    @if(!empty($order->shippingAddress->address_line_2))
                    <p class="mb-1">{{ $order->shippingAddress->address_line_2 }}</p>
                    @endif
                    <p class="mb-1">
                        {{ $order->shippingAddress->city ?? '' }}, 
                        {{ $order->shippingAddress->state ?? '' }} 
                        {{ $order->shippingAddress->zip_code ?? '' }}
                    </p>
                    <p class="mb-0">{{ $order->shippingAddress->country ?? '' }}</p>
                    <hr>
                    <p class="mb-1"><i class="fas fa-phone me-2"></i> {{ $order->shippingAddress->phone ?? Auth::user()->phone }}</p>
                    <p class="mb-0"><i class="fas fa-envelope me-2"></i> {{ $order->shippingAddress->email ?? Auth::user()->email }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.orders.invoice', $order->id) }}" 
                           class="btn btn-outline-primary"
                           target="_blank">
                            <i class="fas fa-file-invoice me-2"></i> Download Invoice
                        </a>
                        
                        @if($order->canBeCancelled())
                            <button type="button" 
                                    class="btn btn-outline-danger"
                                    onclick="cancelOrder('{{ $order->id }}', '{{ $order->order_number }}')">
                                <i class="fas fa-times me-2"></i> Cancel Order
                            </button>
                        @endif
                        
                        @if($order->canBeReturned())
                            <a href="{{ route('user.orders.return', $order->id) }}" 
                               class="btn btn-outline-warning">
                                <i class="fas fa-undo-alt me-2"></i> Return Order
                            </a>
                        @endif
                        
                        <a href="{{ route('user.orders.reorder', $order->id) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-redo-alt me-2"></i> Reorder
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function cancelOrder(orderId, orderNumber) {
        Swal.fire({
            title: 'Cancel Order',
            html: `
                <p>Are you sure you want to cancel order <strong>${orderNumber}</strong>?</p>
                <div class="mb-3">
                    <label for="cancel_reason" class="form-label">Reason for cancellation</label>
                    <textarea id="cancel_reason" 
                              class="form-control" 
                              rows="3"
                              placeholder="Please tell us why you're cancelling this order"></textarea>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel order',
            cancelButtonText: 'No, keep order',
            preConfirm: () => {
                const reason = document.getElementById('cancel_reason').value;
                if (!reason) {
                    Swal.showValidationMessage('Please provide a reason for cancellation');
                    return false;
                }
                return reason;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ route("user.orders.cancel", $order->id) }}',
                    type: 'PATCH',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reason: result.value
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire(
                                'Cancelled!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Error!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            'Error!',
                            'Something went wrong!',
                            'error'
                        );
                    }
                });
            }
        });
    }
</script>
@endpush