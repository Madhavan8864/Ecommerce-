@extends('admin.layouts.app')

@section('title', 'Reviews')
@section('page-title', 'Customer Reviews')

@section('breadcrumbs')
<li class="breadcrumb-item active">Reviews</li>
@endsection

@section('page-actions')
<div class="btn-group gap-2">
    <select class="form-select" id="bulkAction" style="width: 150px;">
        <option value="">Bulk Actions</option>
        <option value="approve">Approve Selected</option>
        <option value="reject">Reject Selected</option>
        <option value="delete">Delete Selected</option>
    </select>
    <button class="btn btn-primary" id="applyBulkAction">Apply</button>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-xl-12 mb-4">
        <div class="row">
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-label">Total Reviews</div>
                    <div class="stat-value">{{ $stats['total'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-label">Pending</div>
                    <div class="stat-value">{{ $stats['pending'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-label">Approved</div>
                    <div class="stat-value">{{ $stats['approved'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-label">Rejected</div>
                    <div class="stat-value">{{ $stats['rejected'] }}</div>
                </div>
            </div>
            
            <div class="col-xl-2 col-md-4 col-6 mb-3">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="stat-label">Avg Rating</div>
                    <div class="stat-value">{{ number_format($stats['avg_rating'], 1) }}/5</div>
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
                               placeholder="Search reviews..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="all">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="rating" class="form-select">
                            <option value="all">All Ratings</option>
                            <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Star</option>
                            <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Star</option>
                            <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Star</option>
                            <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Star</option>
                            <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Reviews Table -->
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="30px">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reviews as $review)
                            <tr id="review-row-{{ $review->id }}">
                                <td>
                                    <input type="checkbox" class="review-checkbox" value="{{ $review->id }}">
                                </td>
                                <td>
                                    <a href="{{ route('admin.products.show', $review->product_id) }}">
                                        {{ Str::limit($review->product->name, 30) }}
                                    </a>
                                </td>
                                <td>
                                    <strong>{{ $review->user->name }}</strong>
                                    <br>
                                    <small>{{ $review->user->email }}</small>
                                </td>
                                <td>
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= $review->rating)
                                            <i class="fas fa-star text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </td>
                                <td>
                                    <strong>{{ $review->title }}</strong>
                                    <br>
                                    <small>{{ Str::limit($review->comment, 50) }}</small>
                                </td>
                                <td>{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ 
                                        $review->status == 'approved' ? 'success' : 
                                        ($review->status == 'pending' ? 'warning' : 'danger') 
                                    }}">
                                        {{ ucfirst($review->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.reviews.show', $review->id) }}" 
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($review->status == 'pending')
                                            <button class="btn btn-sm btn-success approve-review" 
                                                    data-id="{{ $review->id }}" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-sm btn-warning reject-review" 
                                                    data-id="{{ $review->id }}" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                        <button class="btn btn-sm btn-danger delete-review" 
                                                data-id="{{ $review->id }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>{{ $reviews->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Select All
        $('#selectAll').change(function() {
            $('.review-checkbox').prop('checked', $(this).prop('checked'));
        });
        
        // Bulk Action
        $('#applyBulkAction').click(function() {
            const action = $('#bulkAction').val();
            const ids = $('.review-checkbox:checked').map(function() {
                return $(this).val();
            }).get();
            
            if (!action) {
                toastr.error('Please select an action');
                return;
            }
            
            if (ids.length === 0) {
                toastr.error('Please select reviews');
                return;
            }
            
            Swal.fire({
                title: 'Confirm Action',
                text: `Are you sure you want to ${action} ${ids.length} reviews?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.reviews.bulkAction") }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            action: action,
                            ids: ids
                        },
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
        
        // Approve Review
        $('.approve-review').click(function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Approve Review?',
                text: 'This review will be published on the product page',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Approve'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.reviews.approve", ":id") }}'.replace(':id', id),
                        type: 'POST',
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
        
        // Reject Review
        $('.reject-review').click(function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Reject Review?',
                text: 'This review will be hidden from the product page',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Reject'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.reviews.reject", ":id") }}'.replace(':id', id),
                        type: 'POST',
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
        
        // Delete Review
        $('.delete-review').click(function() {
            const id = $(this).data('id');
            
            Swal.fire({
                title: 'Delete Review?',
                text: 'This action cannot be undone',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Delete'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ route("admin.reviews.destroy", ":id") }}'.replace(':id', id),
                        type: 'DELETE',
                        data: { _token: '{{ csrf_token() }}' },
                        success: function(response) {
                            if (response.success) {
                                $('#review-row-' + id).fadeOut();
                                toastr.success(response.message);
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endpush