@extends('admin.layouts.app')

@section('title', 'Stock Movements')
@section('page-title', 'Stock Movement History')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.stock.index') }}">Stock</a></li>
<li class="breadcrumb-item active">Movements</li>
@endsection

@section('page-actions')
<a href="{{ route('admin.stock.index') }}" class="btn btn-secondary">
    <i class="fas fa-arrow-left"></i> Back to Stock
</a>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-12 mb-4">
        <div class="row">
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-success">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                    <div class="stat-label">Total Stock In</div>
                    <div class="stat-value">{{ $stats['total_in'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-danger">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                    <div class="stat-label">Total Stock Out</div>
                    <div class="stat-value">{{ $stats['total_out'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-warning">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div class="stat-label">Adjustments</div>
                    <div class="stat-value">{{ $stats['total_adjustments'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search product..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="type" class="form-select">
                            <option value="all">All Types</option>
                            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In</option>
                            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out</option>
                            <option value="adjustment" {{ request('type') == 'adjustment' ? 'selected' : '' }}>Adjustment</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.stock.movements') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Movements Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
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
                            @foreach($movements as $movement)
                            <tr>
                                <td>{{ $movement->created_at->format('d M Y H:i') }}</td>
                                <td>
                                    <strong>{{ $movement->product->name }}</strong>
                                    <br>
                                    <small>{{ $movement->product->sku }}</small>
                                </td>
                                <td>{!! $movement->type_badge !!}</td>
                                <td class="{{ $movement->type == 'in' ? 'text-success' : 'text-danger' }}">
                                    <strong>{{ $movement->formatted_quantity }}</strong>
                                </td>
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
                    <div>{{ $movements->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection