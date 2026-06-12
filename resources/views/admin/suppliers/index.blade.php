@extends('admin.layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Supplier Management')

@section('breadcrumbs')
<li class="breadcrumb-item active">Suppliers</li>
@endsection

@section('page-actions')
<div class="btn-group gap-2">
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Supplier
    </a>
    <button class="btn btn-success" onclick="exportSuppliers()">
        <i class="fas fa-download"></i> Export
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-12 mb-4">
        <div class="row">
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <div class="stat-label">Total Suppliers</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-label">Active</div>
                    <div class="stat-value">{{ $stats['active'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-4 mb-3">
                <div class="stat-card">
                    <div class="stat-icon text-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-label">Inactive</div>
                    <div class="stat-value">{{ $stats['inactive'] }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by name, company, email or phone..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.suppliers.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Suppliers Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Supplier</th>
                                <th>Contact Person</th>
                                <th>Contact Info</th>
                                <th>GST Number</th>
                                <th>Products</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suppliers as $supplier)
                            <tr id="supplier-row-{{ $supplier->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            <div class="avatar-placeholder rounded-circle">
                                                {{ substr($supplier->name, 0, 1) }}
                                            </div>
                                        </div>
                                        <div>
                                            <strong>{{ $supplier->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $supplier->company ?? 'Individual' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $supplier->contact_person ?? $supplier->name }}</td>
                                <td>
                                    <div>{{ $supplier->email }}</div>
                                    <small class="text-muted">{{ $supplier->phone }}</small>
                                </td>
                                <td>
                                    @if($supplier->gst_number)
                                        <span class="badge bg-info">{{ $supplier->gst_number }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $supplier->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $supplier->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($supplier->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.suppliers.edit', $supplier->id) }}" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-{{ $supplier->status == 'active' ? 'warning' : 'success' }} toggle-status" 
                                                data-id="{{ $supplier->id }}"
                                                title="{{ $supplier->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $supplier->status == 'active' ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-supplier" 
                                                data-id="{{ $supplier->id }}"
                                                data-name="{{ $supplier->name }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-truck fa-3x text-muted mb-3"></i>
                                    <h5>No Suppliers Found</h5>
                                    <p class="text-muted">Add your first supplier to get started.</p>
                                    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Supplier
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $suppliers->firstItem() ?? 0 }} to {{ $suppliers->lastItem() ?? 0 }} of {{ $suppliers->total() }} suppliers
                    </div>
                    <div>
                        {{ $suppliers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar {
        width: 40px;
        height: 40px;
    }
    
    .avatar-placeholder {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle Status
        $('.toggle-status').click(function() {
            const id = $(this).data('id');
            const button = $(this);
            
            Swal.fire({
                title: 'Change Status?',
                text: 'Are you sure you want to change this supplier\'s status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.suppliers.toggleStatus", ":id") }}'.replace(':id', id),
                        type: 'PATCH',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => location.reload(), 1000);
                            }
                        },
                        error: function() {
                            toastr.error('Something went wrong!');
                        }
                    });
                }
            });
        });
        
        // Delete Supplier
        $('.delete-supplier').click(function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            Swal.fire({
                title: 'Delete Supplier?',
                html: `Are you sure you want to delete <strong>${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.suppliers.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                $('#supplier-row-' + id).fadeOut();
                                toastr.success(response.message);
                            } else {
                                toastr.error(response.message);
                            }
                        },
                        error: function() {
                            toastr.error('Something went wrong!');
                        }
                    });
                }
            });
        });
    });
    
    // Export function
    window.exportSuppliers = function() {
        window.location.href = '{{ route("admin.suppliers.export") }}';
    };
</script>
@endpush