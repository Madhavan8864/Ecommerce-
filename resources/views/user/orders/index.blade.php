@extends('user.layouts.app')

@section('title', 'My Orders')
@section('page-title', 'My Orders')

@section('content')
<div class="container-fluid" style="background: #f8fafc; min-height: 100vh; padding: 30px;">
    <!-- Simple Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div style="background: white; border-radius: 16px; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); border: 1px solid #eef2f6;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h2 style="font-size: 24px; font-weight: 600; color: #1a2634; margin-bottom: 4px;">
                            <i class="fas fa-shopping-bag" style="color: #2563eb; margin-right: 10px;"></i>My Orders
                        </h2>
                        <p style="color: #5f6c80; font-size: 14px; margin: 0;">View and manage all your orders</p>
                    </div>
                    <span style="background: #eef2f6; color: #1a2634; padding: 8px 18px; border-radius: 40px; font-size: 14px; font-weight: 500;">
                        <i class="fas fa-box me-2"></i>{{ $orders->total() }} Total
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #eef2f6;">
                <div class="d-flex align-items-center">
                    <div style="width: 40px; height: 40px; background: #e6f0ff; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i class="fas fa-clock" style="color: #2563eb; font-size: 18px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 20px; font-weight: 700; color: #1a2634; margin: 0;">{{ $pendingCount ?? 0 }}</h4>
                        <span style="color: #5f6c80; font-size: 13px;">Pending</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #eef2f6;">
                <div class="d-flex align-items-center">
                    <div style="width: 40px; height: 40px; background: #fff3e6; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i class="fas fa-truck" style="color: #f97316; font-size: 18px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 20px; font-weight: 700; color: #1a2634; margin: 0;">{{ $shippedCount ?? 0 }}</h4>
                        <span style="color: #5f6c80; font-size: 13px;">Shipped</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #eef2f6;">
                <div class="d-flex align-items-center">
                    <div style="width: 40px; height: 40px; background: #e6f7e6; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i class="fas fa-check-circle" style="color: #10b981; font-size: 18px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 20px; font-weight: 700; color: #1a2634; margin: 0;">{{ $deliveredCount ?? 0 }}</h4>
                        <span style="color: #5f6c80; font-size: 13px;">Delivered</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div style="background: white; border-radius: 12px; padding: 16px; border: 1px solid #eef2f6;">
                <div class="d-flex align-items-center">
                    <div style="width: 40px; height: 40px; background: #fee6e6; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-right: 12px;">
                        <i class="fas fa-times-circle" style="color: #ef4444; font-size: 18px;"></i>
                    </div>
                    <div>
                        <h4 style="font-size: 20px; font-weight: 700; color: #1a2634; margin: 0;">{{ $cancelledCount ?? 0 }}</h4>
                        <span style="color: #5f6c80; font-size: 13px;">Cancelled</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div style="background: white; border-radius: 12px; padding: 20px; border: 1px solid #eef2f6;">
                <form method="GET" action="{{ route('user.orders.index') }}">
                    <div class="row g-2">
                        <div class="col-lg-3 col-md-6">
                            <select name="status" style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; color: #1a2634;">
                                <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <input type="date" name="from_date" value="{{ request('from_date') }}" style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px;">
                        </div>
                        <div class="col-lg-2 col-md-6">
                            <input type="date" name="to_date" value="{{ request('to_date') }}" style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px;">
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex gap-2">
                                <input type="text" name="search" placeholder="Order number" value="{{ request('search') }}" style="flex: 1; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px;">
                                <button type="submit" style="background: #2563eb; color: white; border: none; border-radius: 10px; padding: 0 20px; cursor: pointer;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-12">
                            @if(request()->has('status') || request()->has('from_date') || request()->has('to_date') || request()->has('search'))
                                <a href="{{ route('user.orders.index') }}" style="display: block; text-align: center; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; color: #5f6c80; text-decoration: none; font-size: 14px;">
                                    Clear Filters
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="row">
        <div class="col-12">
            <div style="background: white; border-radius: 16px; border: 1px solid #eef2f6; overflow: hidden;">
                @if($orders->isEmpty())
                    <!-- Empty State -->
                    <div style="padding: 60px 20px; text-align: center;">
                        <i class="fas fa-box-open" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
                        <h4 style="font-size: 18px; font-weight: 600; color: #1a2634; margin-bottom: 8px;">No orders found</h4>
                        <p style="color: #5f6c80; font-size: 14px; margin-bottom: 20px;">You haven't placed any orders yet.</p>
                        <a href="{{ route('user.products.index') }}" style="background: #2563eb; color: white; padding: 12px 28px; border-radius: 40px; text-decoration: none; font-size: 14px; font-weight: 500;">
                            <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                        </a>
                    </div>
                @else
                    <!-- Table -->
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #f8fafc; border-bottom: 1px solid #eef2f6;">
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #5f6c80;">Order</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #5f6c80;">Date</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #5f6c80;">Items</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #5f6c80;">Total</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #5f6c80;">Status</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #5f6c80;">Payment</th>
                                    <th style="padding: 16px 20px; text-align: left; font-size: 13px; font-weight: 600; color: #5f6c80;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orders as $order)
                                <tr style="border-bottom: 1px solid #eef2f6;">
                                    <td style="padding: 16px 20px;">
                                        <span style="font-weight: 600; color: #2563eb;">{{ $order->order_number }}</span>
                                    </td>
                                    <td style="padding: 16px 20px;">
                                        <span style="color: #1a2634;">{{ $order->created_at->format('d M Y') }}</span>
                                        <span style="color: #9aa6b5; font-size: 12px; display: block;">{{ $order->created_at->format('h:i A') }}</span>
                                    </td>
                                    <td style="padding: 16px 20px;">
                                        <span style="background: #eef2f6; padding: 4px 12px; border-radius: 20px; font-size: 13px;">{{ $order->items_count }}</span>
                                    </td>
                                    <td style="padding: 16px 20px;">
                                        <span style="font-weight: 600; color: #1a2634;">₹{{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td style="padding: 16px 20px;">
                                        @php
                                            $statusColors = [
                                                'pending' => ['bg' => '#fff7ed', 'text' => '#c2410c'],
                                                'processing' => ['bg' => '#e6f0ff', 'text' => '#1e40af'],
                                                'shipped' => ['bg' => '#f3e8ff', 'text' => '#6b21a8'],
                                                'delivered' => ['bg' => '#e6f7e6', 'text' => '#166534'],
                                                'cancelled' => ['bg' => '#fee6e6', 'text' => '#991b1b']
                                            ];
                                            $color = $statusColors[$order->status] ?? ['bg' => '#eef2f6', 'text' => '#475569'];
                                        @endphp
                                        <span style="background: {{ $color['bg'] }}; color: {{ $color['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 16px 20px;">
                                        @php
                                            $paymentColors = [
                                                'paid' => ['bg' => '#e6f7e6', 'text' => '#166534'],
                                                'pending' => ['bg' => '#fff7ed', 'text' => '#c2410c'],
                                                'failed' => ['bg' => '#fee6e6', 'text' => '#991b1b'],
                                                'refunded' => ['bg' => '#eef2f6', 'text' => '#475569']
                                            ];
                                            $pColor = $paymentColors[$order->payment_status] ?? ['bg' => '#eef2f6', 'text' => '#475569'];
                                        @endphp
                                        <span style="background: {{ $pColor['bg'] }}; color: {{ $pColor['text'] }}; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500;">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td style="padding: 16px 20px;">
                                        <div style="display: flex; gap: 8px;">
                                            <a href="{{ route('user.orders.show', $order->id) }}" 
                                               style="width: 36px; height: 36px; border-radius: 8px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; text-decoration: none;"
                                               title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('user.orders.track', $order->id) }}" 
                                               style="width: 36px; height: 36px; border-radius: 8px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; text-decoration: none;"
                                               title="Track">
                                                <i class="fas fa-truck"></i>
                                            </a>
                                            @if($order->canBeCancelled())
                                                <button type="button" class="cancel-order"
                                                        data-order-id="{{ $order->id }}"
                                                        data-order-number="{{ $order->order_number }}"
                                                        style="width: 36px; height: 36px; border-radius: 8px; background: #fee2e2; color: #ef4444; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer;"
                                                        title="Cancel">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            @if($order->status == 'delivered')
                                                <a href="{{ route('user.orders.invoice', $order->id) }}" target="_blank"
                                                   style="width: 36px; height: 36px; border-radius: 8px; background: #f1f5f9; color: #475569; display: flex; align-items: center; justify-content: center; text-decoration: none;"
                                                   title="Invoice">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div style="padding: 20px; border-top: 1px solid #eef2f6; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                        <span style="color: #5f6c80; font-size: 14px;">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }}
                        </span>
                        <div>
                            {{ $orders->withQueryString()->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Cancel Order Modal with 10 Reasons -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div style="padding: 24px;">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 60px; height: 60px; background: #fee2e2; border-radius: 30px; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                        <i class="fas fa-exclamation-triangle" style="color: #ef4444; font-size: 24px;"></i>
                    </div>
                    <h5 style="font-size: 18px; font-weight: 600; color: #1a2634; margin-bottom: 5px;">Cancel Order</h5>
                    <p style="color: #5f6c80; font-size: 14px;">Order #<span id="orderNumberDisplay" style="font-weight: 600; color: #2563eb;"></span></p>
                </div>

                <form id="cancelOrderForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 14px; font-weight: 500; color: #1a2634; margin-bottom: 8px;">Reason for cancellation</label>
                        <select name="reason" required style="width: 100%; padding: 12px 16px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; color: #1a2634;">
                            <option value="" disabled selected>Select a reason</option>
                            <option value="changed_mind">Changed my mind</option>
                            <option value="found_cheaper">Found cheaper elsewhere</option>
                            <option value="wrong_item">Ordered wrong item</option>
                            <option value="delivery_time">Delivery time too long</option>
                            <option value="payment_issue">Payment issue</option>
                            <option value="duplicate_order">Duplicate order</option>
                            <option value="address_mistake">Wrong shipping address</option>
                            <option value="not_required">No longer required</option>
                            <option value="better_deal">Got better deal</option>
                            <option value="other">Other reason</option>
                        </select>
                    </div>

                    <div style="background: #fff7ed; border-radius: 10px; padding: 12px; margin-bottom: 20px;">
                        <p style="color: #c2410c; font-size: 13px; margin: 0; display: flex; align-items: center; gap: 8px;">
                            <i class="fas fa-info-circle"></i>
                            This action cannot be undone
                        </p>
                    </div>

                    <div style="display: flex; gap: 10px;">
                        <button type="button" data-bs-dismiss="modal"
                                style="flex: 1; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; background: white; color: #5f6c80; font-weight: 500; cursor: pointer;">
                            Close
                        </button>
                        <button type="submit"
                                style="flex: 1; padding: 12px; border-radius: 10px; border: none; background: #ef4444; color: white; font-weight: 500; cursor: pointer;">
                            Cancel Order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Simple Pagination */
    .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .pagination .page-item .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #5f6c80;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
    }
    .pagination .page-item.active .page-link {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    .pagination .page-item .page-link:hover {
        background: #f1f5f9;
        border-color: #2563eb;
        color: #2563eb;
    }
    
    /* Hover Effects */
    tbody tr:hover {
        background: #f8fafc;
    }
    
    button:hover, a:hover {
        opacity: 0.9;
    }
    
    /* Input Focus */
    input:focus, select:focus {
        outline: none;
        border-color: #2563eb !important;
    }
</style>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Cancel order
        $('.cancel-order').click(function() {
            const orderId = $(this).data('order-id');
            const orderNumber = $(this).data('order-number');
            
            $('#orderNumberDisplay').text(orderNumber);
            $('#cancelOrderForm').attr('action', '{{ route("user.orders.cancel", ":id") }}'.replace(':id', orderId));
            $('#cancelOrderModal').modal('show');
        });

        // Auto submit filters
        $('#status, #from_date, #to_date').change(function() {
            $(this).closest('form').submit();
        });
    });
</script>
@endpush