@extends('admin.layouts.app')

@section('title', 'Create Coupon')
@section('page-title', 'Create New Coupon')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="{{ route('admin.coupons.index') }}">Coupons</a></li>
<li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.coupons.store') }}" method="POST">
                    @csrf
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Coupon Code *</label>
                            <div class="input-group">
                                <input type="text" name="code" class="form-control" 
                                       value="{{ request('code') ?? old('code') }}" required>
                                <button class="btn btn-outline-primary" type="button" id="generateCode">
                                    <i class="fas fa-magic"></i> Generate
                                </button>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Type *</label>
                            <select name="type" class="form-select" required>
                                <option value="percentage">Percentage Discount</option>
                                <option value="fixed">Fixed Amount Discount</option>
                                <option value="free_shipping">Free Shipping</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Value *</label>
                            <input type="number" name="value" class="form-control" step="0.01" required>
                            <small class="text-muted">For percentage: 10 = 10%, For fixed: enter amount</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Minimum Order Amount</label>
                            <input type="number" name="min_amount" class="form-control" step="0.01">
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Max Discount Amount</label>
                            <input type="number" name="max_discount" class="form-control" step="0.01">
                            <small class="text-muted">Maximum discount for percentage coupons</small>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Usage Limit</label>
                            <input type="number" name="usage_limit" class="form-control">
                            <small class="text-muted">Leave empty for unlimited</small>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Per User Limit</label>
                            <input type="number" name="per_user_limit" class="form-control" value="1">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Date</label>
                            <input type="datetime-local" name="starts_at" class="form-control">
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Expiry Date</label>
                            <input type="datetime-local" name="expires_at" class="form-control">
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Create Coupon</button>
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Coupon Tips</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Percentage:</strong> Best for site-wide sales
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Fixed:</strong> Great for specific products
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Free Shipping:</strong> Encourage larger orders
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check-circle text-success me-2"></i>
                        <strong>Set limits</strong> to prevent abuse
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('#generateCode').click(function() {
        $.ajax({
            url: '{{ route("admin.coupons.generateCode") }}',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    $('input[name="code"]').val(response.code);
                }
            }
        });
    });
</script>
@endpush