@extends('admin.layouts.app')

@section('title', 'Order Details')
@section('page-title', 'Order Details - ' . $order->order_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
<li class="breadcrumb-item active">{{ $order->order_number }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Order Items -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Order Items</h5>
                <span class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->status) }}</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $item->product->main_image_url }}" 
                                             alt="{{ $item->product->name }}"
                                             width="50"
                                             height="50"
                                             style="object-fit: cover; border-radius: 5px; margin-right: 10px;">
                                        <div>
                                            <strong>{{ $item->product->name }}</strong>
                                            <br>
                                            <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>₹{{ number_format($item->price, 2) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₹{{ number_format($item->total, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td>₹{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                <td>
                                    @if($order->shipping_cost > 0)
                                        ₹{{ number_format($order->shipping_cost, 2) }}
                                    @else
                                        <span class="text-success">Free</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                <td>₹{{ number_format($order->tax, 2) }}</td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end"><strong>Discount:</strong></td>
                                <td class="text-success">- ₹{{ number_format($order->discount_amount, 2) }}</td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Update Status Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Update Order Status</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label">Current Status</label>
                        <div class="status-badge bg-{{ $order->status_color }} p-2 text-center rounded">
                            {{ ucfirst($order->status) }}
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="status" class="form-label">Change Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="">Select Status</option>
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-sync-alt me-2"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Tracking Information Card -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Tracking Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.updateTracking', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label for="tracking_number" class="form-label">Tracking Number</label>
                        <input type="text" 
                               class="form-control" 
                               id="tracking_number" 
                               name="tracking_number" 
                               value="{{ $order->tracking_number }}"
                               placeholder="Enter tracking number">
                    </div>
                    
                    <div class="mb-3">
                        <label for="shipping_carrier" class="form-label">Shipping Carrier</label>
                        <input type="text" 
                               class="form-control" 
                               id="shipping_carrier" 
                               name="shipping_carrier" 
                               value="{{ $order->shipping_carrier }}"
                               placeholder="e.g., FedEx, DHL, India Post">
                    </div>
                    
                    <div class="mb-3">
                        <label for="tracking_url" class="form-label">Tracking URL</label>
                        <input type="url" 
                               class="form-control" 
                               id="tracking_url" 
                               name="tracking_url" 
                               value="{{ $order->tracking_url }}"
                               placeholder="https://...">
                    </div>
                    
                    <button type="submit" class="btn btn-info w-100">
                        <i class="fas fa-truck me-2"></i> Update Tracking
                    </button>
                </form>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Customer Information</h5>
            </div>
            <div class="card-body">
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
                <p><strong>Phone:</strong> {{ $order->user->phone ?? 'N/A' }}</p>
                
                <hr>
                
                <h6>Shipping Address</h6>
                <p>
                    {{ $order->shippingAddress->address_line_1 }}<br>
                    @if($order->shippingAddress->address_line_2)
                        {{ $order->shippingAddress->address_line_2 }}<br>
                    @endif
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}<br>
                    {{ $order->shippingAddress->zip_code }}<br>
                    {{ $order->shippingAddress->country }}
                </p>
                
                <h6>Billing Address</h6>
                <p>
                    {{ $order->billingAddress->address_line_1 }}<br>
                    @if($order->billingAddress->address_line_2)
                        {{ $order->billingAddress->address_line_2 }}<br>
                    @endif
                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }}<br>
                    {{ $order->billingAddress->zip_code }}<br>
                    {{ $order->billingAddress->country }}
                </p>
                
                <hr>
                
                <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                <p><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                <p><strong>Payment Status:</strong> 
                    <span class="badge bg-{{ $order->payment_status_color }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </p>
                
                @if($order->notes)
                <hr>
                <h6>Order Notes</h6>
                <p>{{ $order->notes }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection