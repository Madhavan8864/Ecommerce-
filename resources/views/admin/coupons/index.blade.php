@extends('admin.layouts.app')

@section('title', 'Coupons')
@section('page-title', 'Promotional Coupons')

@section('breadcrumbs')
<li class="breadcrumb-item active">Coupons</li>
@endsection

@section('page-actions')
<div class="btn-group gap-2">
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Coupon
    </a>
    <button class="btn btn-outline-primary" onclick="exportCoupons()">
        <i class="fas fa-download"></i> Export
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-12 mb-4">
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <div class="stat-label">Total Coupons</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle text-success"></i>
                    </div>
                    <div class="stat-label">Active</div>
                    <div class="stat-value">{{ $stats['active'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock text-warning"></i>
                    </div>
                    <div class="stat-label">Expired</div>
                    <div class="stat-value">{{ $stats['expired'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-users text-info"></i>
                    </div>
                    <div class="stat-label">Times Used</div>
                    <div class="stat-value">{{ $stats['used'] }}</div>
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
                               placeholder="Search by coupon code..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                    <div class="col-md-2 text-end">
                        <button type="button" class="btn btn-outline-success" id="generateCodeBtn">
                            <i class="fas fa-magic"></i> Generate Code
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Coupons Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Min Amount</th>
                                <th>Usage</th>
                                <th>Valid Period</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($coupons as $coupon)
                            <tr id="coupon-row-{{ $coupon->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-primary">{{ $coupon->code }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $coupon->description ?? 'No description' }}</small>
                                </td>
                                <td>
                                    @if($coupon->type == 'percentage')
                                        <span class="badge bg-info">Percentage</span>
                                    @elseif($coupon->type == 'fixed')
                                        <span class="badge bg-success">Fixed Amount</span>
                                    @else
                                        <span class="badge bg-warning">Free Shipping</span>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->type == 'percentage')
                                        <strong>{{ $coupon->value }}%</strong>
                                        @if($coupon->max_discount)
                                            <br><small>Max ₹{{ number_format($coupon->max_discount, 0) }}</small>
                                        @endif
                                    @elseif($coupon->type == 'fixed')
                                        <strong>₹{{ number_format($coupon->value, 0) }}</strong>
                                    @else
                                        <strong>Free Shipping</strong>
                                    @endif
                                </td>
                                <td>
                                    @if($coupon->min_amount)
                                        ₹{{ number_format($coupon->min_amount, 0) }}
                                    @else
                                        <span class="text-muted">No min</span>
                                    @endif
                                </td>
                                <td>
                                    <div>{{ $coupon->used_count }} / {{ $coupon->usage_limit ?? '∞' }}</div>
                                    @if($coupon->per_user_limit)
                                        <small class="text-muted">{{ $coupon->per_user_limit }} per user</small>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $now = now();
                                        $isExpired = $coupon->expires_at && $now->gt($coupon->expires_at);
                                        $isActive = $coupon->status == 'active' && !$isExpired;
                                    @endphp
                                    
                                    @if($isActive)
                                        <span class="badge bg-success">Active</span>
                                    @elseif($isExpired)
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($coupon->status) }}</span>
                                    @endif
                                    
                                    @if($coupon->starts_at && $now->lt($coupon->starts_at))
                                        <br><small class="text-info">Starts {{ $coupon->starts_at->format('d M') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.coupons.edit', $coupon->id) }}" 
                                           class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-{{ $coupon->status == 'active' ? 'warning' : 'success' }} toggle-status" 
                                                data-id="{{ $coupon->id }}"
                                                title="{{ $coupon->status == 'active' ? 'Deactivate' : 'Activate' }}">
                                            <i class="fas fa-{{ $coupon->status == 'active' ? 'ban' : 'check' }}"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-coupon" 
                                                data-id="{{ $coupon->id }}"
                                                data-code="{{ $coupon->code }}"
                                                title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                                    <h5>No Coupons Found</h5>
                                    <p class="text-muted">Create your first coupon to start promoting your store.</p>
                                    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Coupon
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
                        Showing {{ $coupons->firstItem() ?? 0 }} to {{ $coupons->lastItem() ?? 0 }} of {{ $coupons->total() }} coupons
                    </div>
                    <div>
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Generate Code Modal -->
<div class="modal fade" id="generateCodeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Coupon Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="display-4" id="generatedCode">NEWCODE</div>
                    <small class="text-muted">Click the button to generate a random code</small>
                </div>
                <div class="d-grid">
                    <button class="btn btn-primary" id="generateNewCode">
                        <i class="fas fa-sync-alt"></i> Generate New Code
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="useGeneratedCode">
                    <i class="fas fa-check"></i> Use This Code
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        let currentGeneratedCode = '';
        
        // ============================================
        // GENERATE CODE MODAL
        // ============================================
        $('#generateCodeBtn').click(function() {
            generateNewCode();
            $('#generateCodeModal').modal('show');
        });
        
        $('#generateNewCode').click(function() {
            generateNewCode();
        });
        
        function generateNewCode() {
            $.ajax({
                url: '{{ route("admin.coupons.generateCode") }}',
                type: 'POST',
                data: { _token: '{{ csrf_token() }}' },
                success: function(response) {
                    if (response.success) {
                        $('#generatedCode').text(response.code);
                        currentGeneratedCode = response.code;
                    }
                }
            });
        }
        
        $('#useGeneratedCode').click(function() {
            if (currentGeneratedCode) {
                window.location.href = '{{ route("admin.coupons.create") }}?code=' + currentGeneratedCode;
            }
        });
        
        // ============================================
        // TOGGLE STATUS
        // ============================================
        $('.toggle-status').click(function() {
            const id = $(this).data('id');
            const button = $(this);
            const currentStatus = button.hasClass('btn-warning') ? 'deactivate' : 'activate';
            
            Swal.fire({
                title: `Confirm ${currentStatus}`,
                text: `Are you sure you want to ${currentStatus} this coupon?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                confirmButtonText: `Yes, ${currentStatus}`
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.coupons.toggleStatus", ":id") }}'.replace(':id', id),
                        type: 'PATCH',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                                setTimeout(() => location.reload(), 1000);
                            }
                        }
                    });
                }
            });
        });
        
        // ============================================
        // DELETE COUPON
        // ============================================
        $('.delete-coupon').click(function() {
            const id = $(this).data('id');
            const code = $(this).data('code');
            
            Swal.fire({
                title: 'Delete Coupon?',
                html: `Are you sure you want to delete coupon <strong>${code}</strong>?`,
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.coupons.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                $('#coupon-row-' + id).fadeOut();
                                toastr.success(response.message);
                            }
                        }
                    });
                }
            });
        });
        
        // ============================================
        // EXPORT COUPONS
        // ============================================
        window.exportCoupons = function() {
            window.location.href = '{{ route("admin.coupons.export") }}';
        };
    });
</script>
@endpush