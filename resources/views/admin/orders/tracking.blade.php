@extends('admin.layouts.app')

@section('title', 'Order Tracking')
@section('page-title', 'Tracking - ' . $order->order_number)

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Orders</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.orders.show', $order->id) }}">{{ $order->order_number }}</a></li>
<li class="breadcrumb-item active">Tracking</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5>Tracking Information for Order #{{ $order->order_number }}</h5>
                <!-- Add tracking details here -->
            </div>
        </div>
    </div>
</div>
@endsection