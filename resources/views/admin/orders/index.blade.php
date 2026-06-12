@extends('admin.layouts.app')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('breadcrumbs')
<li class="breadcrumb-item active">Orders</li>
@endsection

@section('page-actions')
<div class="d-flex">
    <div class="me-3">
        <select class="form-select" id="statusFilter" onchange="filterOrders()">
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>
    <a href="{{ route('admin.orders.export') }}" class="btn btn-success">
        <i class="fas fa-download me-2"></i> Export
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Orders Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover datatable" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Date</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>{{ $order->items_count }}</td>
                                <td>₹{{ $order->total_amount }}</td>
                                <td>{{ $order->payment_status }}</td>
                                <td>{{ $order->status }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-info">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $orders->links() }}

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function filterOrders() {
    const status = document.getElementById('statusFilter').value;
    let url = '{{ route("admin.orders.index") }}';

    if (status !== 'all') {
        url += '?status=' + status;
    }

    window.location.href = url;
}

$(document).ready(function () {

    let table = $('#ordersTable');

    // ✅ Fix: Prevent reinitialization error
    if ($.fn.DataTable.isDataTable(table)) {
        table.DataTable().destroy();
    }

    table.DataTable({
        paging: false,
        searching: false,
        info: false,
        order: [[2, 'desc']]
    });

});
</script>
@endpush