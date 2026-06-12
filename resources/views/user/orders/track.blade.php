@extends('user.layouts.app')

@section('title', 'Track Order - ' . $order->order_number)
@section('page-title', 'Track Order')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Order Tracking Header -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <i class="fas fa-truck fa-3x text-primary"></i>
                    </div>
                    <h4 class="mb-2">Order #{{ $order->order_number }}</h4>
                    <p class="text-muted mb-0">
                        Placed on {{ $order->created_at->format('d M Y, h:i A') }}
                    </p>
                    <div class="mt-3">
                        <span class="badge bg-{{ $order->status_color }} p-3">
                            <i class="fas fa-{{ $order->status == 'delivered' ? 'check-circle' : ($order->status == 'cancelled' ? 'times-circle' : 'clock') }} me-2"></i>
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tracking Timeline -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Tracking Timeline</h5>
                </div>
                <div class="card-body">
                    @if(isset($trackingUpdates) && count($trackingUpdates) > 0)
                        <div class="timeline">
                            @foreach($trackingUpdates as $update)
                                <div class="timeline-item">
                                    <div class="timeline-marker">
                                        <div class="timeline-icon bg-{{ $loop->first ? 'success' : 'light' }}">
                                            <i class="fas fa-{{ $update['icon'] }}"></i>
                                        </div>
                                    </div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $update['status'] }}</h6>
                                                <p class="text-muted mb-1">{{ $update['description'] }}</p>
                                                <small class="text-muted">
                                                    <i class="fas fa-map-marker-alt me-1"></i> {{ $update['location'] }}
                                                </small>
                                            </div>
                                            <span class="badge bg-light text-dark">
                                                {{ \Carbon\Carbon::parse($update['date'])->format('d M Y, h:i A') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-truck-loading fa-3x text-muted"></i>
                            </div>
                            <h6>No tracking updates available yet</h6>
                            <p class="text-muted mb-0">We'll notify you once your order is shipped.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Estimated Delivery -->
            @if($order->estimated_delivery_date)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-calendar-check fa-2x text-success"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Estimated Delivery Date</h6>
                            <p class="mb-0 fw-bold">
                                {{ $order->estimated_delivery_date->format('l, d F Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Shipping Details -->
            @if($order->tracking_number)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Shipping Details</h5>
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
                        <a href="{{ $order->tracking_url }}" target="_blank" class="btn btn-primary w-100">
                            <i class="fas fa-external-link-alt me-2"></i> Track with Carrier
                        </a>
                    @endif
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('user.orders.show', $order->id) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Order Details
                </a>
                @if($order->status == 'delivered' && $order->canBeReturned())
                    <a href="{{ route('user.orders.return', $order->id) }}" class="btn btn-outline-warning">
                        <i class="fas fa-undo-alt me-2"></i> Request Return
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 30px;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item:before {
    content: '';
    position: absolute;
    left: -24px;
    top: 24px;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item:last-child:before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -30px;
    top: 0;
}

.timeline-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    border: 2px solid #fff;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.timeline-icon.bg-success {
    background: #198754 !important;
    color: white;
}

.timeline-icon.bg-light {
    background: #f8f9fa !important;
    color: #6c757d;
}

.timeline-content {
    padding-left: 20px;
}

@media (max-width: 768px) {
    .timeline-content .d-flex {
        flex-direction: column;
    }
    
    .timeline-content .badge {
        margin-top: 5px;
    }
}
</style>
@endsection