@extends('admin.layouts.app')

@section('title', 'Warehouses')
@section('page-title', 'Warehouse Management')

@section('breadcrumbs')
<li class="breadcrumb-item active">Warehouses</li>
@endsection

@section('page-actions')
<div class="btn-group gap-2">
    <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Warehouse
    </a>
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
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <div class="stat-label">Total Warehouses</div>
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
                    <div class="stat-icon">
                        <i class="fas fa-weight-hanging"></i>
                    </div>
                    <div class="stat-label">Total Capacity</div>
                    <div class="stat-value">{{ number_format($stats['total_capacity']) }} units</div>
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
                               placeholder="Search by name, code or city..." 
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
                        <a href="{{ route('admin.warehouses.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Warehouses Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Warehouse</th>
                                <th>Code</th>
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Capacity</th>
                                <th>Features</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($warehouses as $warehouse)
                            <tr id="warehouse-row-{{ $warehouse->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong>{{ $warehouse->name }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $warehouse->code }}</span>
                                </td>
                                <td>
                                    <div>{{ $warehouse->city }}, {{ $warehouse->state }}</div>
                                    <small class="text-muted">{{ $warehouse->country }}</small>
                                </td>
                                <td>
                                    <div>{{ $warehouse->contact_person ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $warehouse->contact_phone ?? '' }}</small>
                                </td>
                                <td>
                                    @if($warehouse->capacity)
                                        <div class="progress" style="height: 6px;">
                                            <div class="progress-bar bg-primary" 
                                                 style="width: {{ $warehouse->capacity_percentage }}%"></div>
                                        </div>
                                        <small>{{ number_format($warehouse->capacity_used) }} / {{ number_format($warehouse->capacity) }} units</small>
                                    @else
                                        <span class="text-muted">Not specified</span>
                                    @endif
                                </td>
                                <td>
                                    @if($warehouse->temperature_controlled)
                                        <span class="badge bg-info" title="Temperature Controlled">
                                            <i class="fas fa-thermometer-half"></i>
                                        </span>
                                    @endif
                                    @if($warehouse->hazmat_certified)
                                        <span class="badge bg-warning" title="Hazmat Certified">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $warehouse->status == 'active' ? 'success' : 'danger' }}">
                                        {{ ucfirst($warehouse->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.warehouses.edit', $warehouse->id) }}" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('admin.warehouses.stock', $warehouse->id) }}" 
                                           class="btn btn-sm btn-info" title="View Stock">
                                            <i class="fas fa-boxes"></i>
                                        </a>
                                        <button class="btn btn-sm btn-{{ $warehouse->status == 'active' ? 'warning' : 'success' }} toggle-status" 
                                                data-id="{{ $warehouse->id }}"
                                                title="{{ $warehouse->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $warehouse->status == 'active' ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-warehouse" 
                                                data-id="{{ $warehouse->id }}"
                                                data-name="{{ $warehouse->name }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-warehouse fa-3x text-muted mb-3"></i>
                                    <h5>No Warehouses Found</h5>
                                    <p class="text-muted">Add your first warehouse to manage inventory.</p>
                                    <a href="{{ route('admin.warehouses.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Warehouse
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
                        Showing {{ $warehouses->firstItem() ?? 0 }} to {{ $warehouses->lastItem() ?? 0 }} of {{ $warehouses->total() }} warehouses
                    </div>
                    <div>
                        {{ $warehouses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle Status
        $('.toggle-status').click(function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Change Status?',
                text: 'Are you sure you want to change this warehouse\'s status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Yes, change it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.warehouses.toggleStatus", ":id") }}'.replace(':id', id),
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
        
        // Delete Warehouse
        $('.delete-warehouse').click(function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            
            Swal.fire({
                title: 'Delete Warehouse?',
                html: `Are you sure you want to delete <strong>${name}</strong>?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.warehouses.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                $('#warehouse-row-' + id).fadeOut();
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
</script>
@endpush