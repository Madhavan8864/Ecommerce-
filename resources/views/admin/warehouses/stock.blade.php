@extends('admin.layouts.app')

@section('title', 'Warehouse Stock')
@section('page-title', $warehouse->name . ' - Stock')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.warehouses.index') }}">Warehouses</a></li>
<li class="breadcrumb-item active">Stock</li>
@endsection

@section('page-actions')
<a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" class="btn btn-primary">
    <i class="fas fa-edit"></i> Edit Warehouse
</a>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Stock Movements - {{ $warehouse->name }}</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Type</th>
                                <th>Quantity</th>
                                <th>Old Stock</th>
                                <th>New Stock</th>
                                <th>Reason</th>
                                <th>Updated By</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($stock as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <strong>{{ $movement->product->name }}</strong>
                                    <br>
                                    <small>{{ $movement->product->sku }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $movement->type == 'in' ? 'success' : 'danger' }}">
                                        {{ ucfirst($movement->type) }}
                                    </span>
                                </td>
                                <td>{{ $movement->quantity }}</td>
                                <td>{{ $movement->old_quantity }}</td>
                                <td>{{ $movement->new_quantity }}</td>
                                <td>{{ $movement->reason }}</td>
                                <td>{{ $movement->user->name ?? 'System' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>{{ $stock->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection