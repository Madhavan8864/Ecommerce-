@extends('admin.layouts.app')

@section('title', 'Stock Alerts')
@section('page-title', 'Stock Alerts')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.stock.index') }}">Stock</a></li>
<li class="breadcrumb-item active">Alerts</li>
@endsection

@section('content')
<div class="row">
    <!-- Critical Alert -->
    @if($criticalStock > 0)
    <div class="col-12 mb-4">
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Critical Alert!</strong> {{ $criticalStock }} products are critically low (≤ 5 units).
            <a href="#critical-stock" class="alert-link">View Details</a>
        </div>
    </div>
    @endif
    
    <!-- Low Stock -->
    <div class="col-12 mb-4" id="low-stock">
        <div class="card border-warning">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Low Stock Products ({{ $lowStock->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($lowStock->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Current Stock</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStock as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->main_image_url }}" width="40" height="40" class="rounded me-2">
                                            <strong>{{ $product->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge bg-warning fs-6">{{ $product->quantity }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">Low Stock</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary adjust-stock" 
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-stock="{{ $product->quantity }}">
                                            <i class="fas fa-edit"></i> Restock
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No low stock products</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Out of Stock -->
    <div class="col-12 mb-4" id="out-of-stock">
        <div class="card border-danger">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0">
                    <i class="fas fa-times-circle me-2"></i>
                    Out of Stock Products ({{ $outOfStock->count() }})
                </h5>
            </div>
            <div class="card-body">
                @if($outOfStock->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Last Updated</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($outOfStock as $product)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $product->main_image_url }}" width="40" height="40" class="rounded me-2">
                                            <strong>{{ $product->name }}</strong>
                                        </div>
                                    </td>
                                    <td>{{ $product->sku }}</td>
                                    <td>{{ $product->category->name ?? 'N/A' }}</td>
                                    <td>{{ $product->updated_at->format('d M Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary adjust-stock" 
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-stock="0">
                                            <i class="fas fa-plus"></i> Add Stock
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted text-center py-3">No out of stock products</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Adjust Stock Modal (same as in index) -->
@include('admin.stock.partials.adjust-modal')
@endsection