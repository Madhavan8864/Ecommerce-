@extends('admin.layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')

@section('breadcrumbs')
<li class="breadcrumb-item active">Customers</li>
@endsection

@section('page-actions')
<button type="button" class="btn btn-success" onclick="exportCustomers()">
    <i class="fas fa-download me-2"></i> Export CSV
</button>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards - Shows customer counts -->
    <div class="col-xl-12 mb-4">
        <div class="row">
            @foreach($stats as $key => $value)
                <div class="col-xl-2 col-md-4 col-6 mb-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $value }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <!-- Customers Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <!-- Simple Search Filter -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form method="GET" class="d-flex">
                            <input type="text" 
                                   name="search" 
                                   class="form-control me-2" 
                                   placeholder="Search by name, email or phone"
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                            @if(request()->has('search'))
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
                
                <!-- Customers Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="customers-table">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Orders</th>
                                <th>Total Spent</th>
                                <th>Joined</th>
                                <th>Action</th>
                            </thead>
                        <tbody>
                            @forelse($customers as $customer)
                            <tr id="customer-row-{{ $customer->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar me-3">
                                            @php
                                                $firstLetter = strtoupper(substr($customer->name, 0, 1));
                                                $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8', '#F7D794', '#F3A683', '#786FA6', '#F19066', '#F5CD79', '#E77F67', '#CF6F8B', '#B53471', '#00B894', '#00CEC9', '#0984E3', '#6C5CE7', '#A8E6CF'];
                                                $colorIndex = abs(crc32($customer->name)) % count($colors);
                                                $bgColor = $colors[$colorIndex];
                                            @endphp
                                            <div class="avatar-placeholder rounded-circle" style="background: {{ $bgColor }};">
                                                {{ $firstLetter }}
                                            </div>
                                        </div>
                                        <div>
                                            <strong>{{ $customer->name }}</strong>
                                            <br>
                                            <small class="text-muted">ID: {{ $customer->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>{{ $customer->email }}</div>
                                    <small class="text-muted">{{ $customer->phone ?? 'No phone' }}</small>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $customer->is_active ? 'success' : 'danger' }}">
                                        {{ $customer->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $customer->orders()->count() }}</span>
                                </td>
                                <td>
                                    <strong>₹{{ number_format($customer->totalSpent(), 2) }}</strong>
                                </td>
                                <td>
                                    {{ $customer->created_at->format('d M Y') }}
                                </td>
                                <td>
                                    <!-- DELETE BUTTON - AJAX -->
                                    <button type="button" 
                                            class="btn btn-sm btn-danger delete-customer" 
                                            data-id="{{ $customer->id }}"
                                            data-name="{{ $customer->name }}"
                                            title="Delete Customer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr id="no-customers-row">
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <h5>No Customers Found</h5>
                                    <p class="text-muted">There are no customers in the system yet.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $customers->firstItem() ?? 0 }} to {{ $customers->lastItem() ?? 0 }} of {{ $customers->total() }} customers
                    </div>
                    <div id="pagination-links">
                        {{ $customers->links() }}
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
        flex-shrink: 0;
    }
    
    .avatar-placeholder {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        font-weight: bold;
        color: white;
        text-transform: uppercase;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .table > :not(caption) > * > * {
        vertical-align: middle;
    }
    
    .btn-danger {
        transition: all 0.3s;
    }
    
    .btn-danger:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // ============================================
        // DELETE CUSTOMER - AJAX (No page reload)
        // ============================================
        $('.delete-customer').click(function(e) {
            e.preventDefault();
            
            const button = $(this);
            const customerId = button.data('id');
            const customerName = button.data('name');
            
            Swal.fire({
                title: 'Delete Customer?',
                html: `Are you sure you want to delete <strong>${customerName}</strong>?<br><br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return $.ajax({
                        url: '{{ url("admin/customers") }}/' + customerId,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            return response;
                        },
                        error: function(xhr) {
                            return xhr.responseJSON;
                        }
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed && result.value.success) {
                    // Remove the row from table
                    $('#customer-row-' + customerId).fadeOut(500, function() {
                        $(this).remove();
                        
                        // Check if table is empty
                        if ($('#customers-table tbody tr').length === 0) {
                            $('#customers-table tbody').append(`
                                <tr id="no-customers-row">
                                    <td colspan="8" class="text-center py-4">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5>No Customers Found</h5>
                                        <p class="text-muted">There are no customers in the system yet.</p>
                                    </td>
                                </tr>
                            `);
                        }
                    });
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: result.value.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                    
                } else if (result.value && !result.value.success) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: result.value.message || 'Could not delete customer.'
                    });
                }
            });
        });
        
        // ============================================
        // EXPORT CUSTOMERS
        // ============================================
        window.exportCustomers = function() {
            window.location.href = '{{ route("admin.customers.export") }}';
        };
        
        // ============================================
        // TOAST CONFIGURATION
        // ============================================
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };
    });
</script>
@endpush