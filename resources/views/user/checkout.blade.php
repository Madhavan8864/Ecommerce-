@extends('user.layouts.app')

@section('title', 'Checkout')
@section('page-title', 'Checkout')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Checkout Steps -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="checkout-steps">
                        <div class="step completed">
                            <div class="step-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="step-text">Cart</div>
                        </div>
                        <div class="step active">
                            <div class="step-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="step-text">Checkout</div>
                        </div>
                        <div class="step">
                            <div class="step-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="step-text">Complete</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-truck me-2"></i> Shipping Address
                    </h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus me-1"></i> Add New Address
                    </button>
                </div>
                <div class="card-body">
                    @if($addresses->count() > 0)
                        <div class="row">
                            @foreach($addresses as $address)
                                <div class="col-md-6 mb-3">
                                    <div class="address-card {{ $defaultShippingAddress && $defaultShippingAddress->id == $address->id ? 'selected' : '' }}" 
                                         data-address-id="{{ $address->id }}"
                                         data-address-type="shipping">
                                        <div class="form-check">
                                            <input class="form-check-input shipping-address" 
                                                   type="radio" 
                                                   name="shipping_address_id" 
                                                   id="shipping_{{ $address->id }}"
                                                   value="{{ $address->id }}"
                                                   {{ $defaultShippingAddress && $defaultShippingAddress->id == $address->id ? 'checked' : '' }}
                                                   required>
                                            <label class="form-check-label" for="shipping_{{ $address->id }}">
                                                <div class="address-details">
                                                    <strong>{{ $address->address_line_1 }}</strong>
                                                    @if($address->address_line_2)
                                                        <br>{{ $address->address_line_2 }}
                                                    @endif
                                                    <br>{{ $address->city }}, {{ $address->state }} - {{ $address->zip_code }}
                                                    <br>{{ $address->country }}
                                                    @if($address->is_default)
                                                        <span class="badge bg-primary ms-2">Default</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                            <h6>No addresses found</h6>
                            <p class="text-muted mb-3">Please add a shipping address to continue</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-1"></i> Add New Address
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Billing Address -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-file-invoice me-2"></i> Billing Address
                    </h5>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="sameAsShipping" checked>
                        <label class="form-check-label" for="sameAsShipping">
                            Same as shipping address
                        </label>
                    </div>
                </div>
                <div class="card-body" id="billingAddressSection" style="display: none;">
                    <div class="row">
                        @foreach($addresses as $address)
                            <div class="col-md-6 mb-3">
                                <div class="address-card">
                                    <div class="form-check">
                                        <input class="form-check-input billing-address" 
                                               type="radio" 
                                               name="billing_address_id" 
                                               id="billing_{{ $address->id }}"
                                               value="{{ $address->id }}"
                                               {{ $defaultBillingAddress && $defaultBillingAddress->id == $address->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="billing_{{ $address->id }}">
                                            <div class="address-details">
                                                <strong>{{ $address->address_line_1 }}</strong>
                                                @if($address->address_line_2)
                                                    <br>{{ $address->address_line_2 }}
                                                @endif
                                                <br>{{ $address->city }}, {{ $address->state }} - {{ $address->zip_code }}
                                                <br>{{ $address->country }}
                                                @if($address->is_default)
                                                    <span class="badge bg-primary ms-2">Default</span>
                                                @endif
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2"></i> Payment Method
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <div class="payment-card">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="payment_method" 
                                           id="payment_cod" 
                                           value="cod"
                                           checked>
                                    <label class="form-check-label" for="payment_cod">
                                        <div class="payment-details">
                                            <i class="fas fa-money-bill-wave fa-2x mb-2"></i>
                                            <h6 class="mb-1">Cash on Delivery</h6>
                                            <small class="text-muted">Pay when you receive</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="payment-card">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="payment_method" 
                                           id="payment_card" 
                                           value="card">
                                    <label class="form-check-label" for="payment_card">
                                        <div class="payment-details">
                                            <i class="fas fa-credit-card fa-2x mb-2"></i>
                                            <h6 class="mb-1">Credit/Debit Card</h6>
                                            <small class="text-muted">Visa, Mastercard, RuPay</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="payment-card">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="payment_method" 
                                           id="payment_upi" 
                                           value="upi">
                                    <label class="form-check-label" for="payment_upi">
                                        <div class="payment-details">
                                            <i class="fas fa-mobile-alt fa-2x mb-2"></i>
                                            <h6 class="mb-1">UPI</h6>
                                            <small class="text-muted">Google Pay, PhonePe, Paytm</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="payment-card">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="payment_method" 
                                           id="payment_netbanking" 
                                           value="netbanking">
                                    <label class="form-check-label" for="payment_netbanking">
                                        <div class="payment-details">
                                            <i class="fas fa-university fa-2x mb-2"></i>
                                            <h6 class="mb-1">Net Banking</h6>
                                            <small class="text-muted">All major banks</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="payment-card">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="payment_method" 
                                           id="payment_wallet" 
                                           value="wallet">
                                    <label class="form-check-label" for="payment_wallet">
                                        <div class="payment-details">
                                            <i class="fas fa-wallet fa-2x mb-2"></i>
                                            <h6 class="mb-1">Wallet</h6>
                                            <small class="text-muted">Paytm, Amazon Pay</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <div class="payment-card">
                                <div class="form-check">
                                    <input class="form-check-input" 
                                           type="radio" 
                                           name="payment_method" 
                                           id="payment_emi" 
                                           value="emi">
                                    <label class="form-check-label" for="payment_emi">
                                        <div class="payment-details">
                                            <i class="fas fa-calendar-alt fa-2x mb-2"></i>
                                            <h6 class="mb-1">EMI</h6>
                                            <small class="text-muted">No cost EMI available</small>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Notes -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-pen me-2"></i> Order Notes (Optional)
                    </h5>
                </div>
                <div class="card-body">
                    <textarea class="form-control" 
                              id="notes" 
                              name="notes" 
                              rows="3"
                              placeholder="Any special instructions for delivery?"></textarea>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card sticky-top" style="top: 100px; z-index: 100;">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <!-- Order Items -->
                    <div class="order-items mb-4">
                        <h6 class="mb-3">Order Items ({{ $totalItems }})</h6>
                        @foreach($cartItems as $item)
                            <div class="order-item d-flex mb-2">
                                <div class="order-item-image me-3">
                                    <img src="{{ $item->product->main_image_url }}" 
                                         alt="{{ $item->product->name }}"
                                         width="50"
                                         height="50"
                                         style="object-fit: cover;">
                                </div>
                                <div class="order-item-info flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($item->product->name, 30) }}</h6>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                        <small>₹{{ number_format($item->product->current_price * $item->quantity, 2) }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Price Details -->
                    <div class="price-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span class="subtotal-amount">₹{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping</span>
                            <span class="shipping-amount">
                                @if($shipping > 0)
                                    ₹{{ number_format($shipping, 2) }}
                                @else
                                    <span class="text-success">Free</span>
                                @endif
                            </span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (8% GST)</span>
                            <span class="tax-amount">₹{{ number_format($tax, 2) }}</span>
                        </div>
                        
                        <!-- Coupon Section -->
                        <div class="coupon-section mb-3 pt-2 border-top">
                            <div class="coupon-form" id="couponForm">
                                <div class="input-group mb-2">
                                    <input type="text" 
                                           class="form-control" 
                                           id="coupon_code" 
                                           placeholder="Enter coupon code"
                                           value="{{ $appliedCoupon['code'] ?? '' }}">
                                    <button class="btn btn-outline-primary" type="button" id="applyCouponBtn">
                                        Apply
                                    </button>
                                </div>
                                <div id="couponMessage" class="small"></div>
                            </div>
                            <div class="applied-coupon" id="appliedCoupon" style="{{ isset($appliedCoupon) ? 'display: block;' : 'display: none;' }}">
                                <div class="alert alert-success py-2 px-3 mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>
                                            <i class="fas fa-tag me-1"></i>
                                            Coupon <strong id="couponCode">{{ $appliedCoupon['code'] ?? '' }}</strong> applied
                                        </span>
                                        <button type="button" class="btn-close btn-sm" id="removeCouponBtn"></button>
                                    </div>
                                    <small id="couponDiscountInfo">
                                        You saved: ₹{{ number_format($appliedCoupon['discount'] ?? 0, 2) }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between mb-2">
                            <span>Discount</span>
                            <span class="text-success discount-amount">
                                - ₹{{ number_format($appliedCoupon['discount'] ?? 0, 2) }}
                            </span>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3 pt-3 border-top">
                            <h5 class="mb-0">Total</h5>
                            <h5 class="mb-0 text-primary total-amount">₹{{ number_format($total, 2) }}</h5>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <button type="button" class="btn btn-primary btn-lg w-100 mt-4" id="placeOrderBtn">
                        <i class="fas fa-lock me-2"></i> Place Order
                    </button>
                    
                    <p class="text-muted small text-center mt-3 mb-0">
                        <i class="fas fa-shield-alt me-1"></i> Secure checkout. Your information is protected.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Address Modal -->
<div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Address</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAddressForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Address Type *</label>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_shipping" value="shipping" checked>
                                    <label class="form-check-label" for="type_shipping">
                                        Shipping Address
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_billing" value="billing">
                                    <label class="form-check-label" for="type_billing">
                                        Billing Address
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="type" id="type_both" value="both">
                                    <label class="form-check-label" for="type_both">
                                        Both
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line_1" class="form-label">Address Line 1 *</label>
                        <input type="text" class="form-control" id="address_line_1" name="address_line_1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="address_line_2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address_line_2" name="address_line_2">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City *</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State *</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="zip_code" class="form-label">ZIP/PIN Code *</label>
                            <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country *</label>
                            <input type="text" class="form-control" id="country" name="country" value="India" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_default" name="is_default" value="1">
                            <label class="form-check-label" for="is_default">
                                Set as default address
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Address</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .checkout-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
        position: relative;
    }
    
    .checkout-steps::before {
        content: '';
        position: absolute;
        top: 25px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e9ecef;
        z-index: 1;
    }
    
    .step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    
    .step-icon {
        width: 50px;
        height: 50px;
        background: white;
        border: 2px solid #dee2e6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        color: #6c757d;
        transition: all 0.3s;
    }
    
    .step.completed .step-icon {
        background: #28a745;
        border-color: #28a745;
        color: white;
    }
    
    .step.active .step-icon {
        background: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    
    .step-text {
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
    }
    
    .step.active .step-text,
    .step.completed .step-text {
        color: #212529;
    }
    
    .address-card {
        padding: 15px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        height: 100%;
    }
    
    .address-card:hover {
        border-color: #0d6efd;
        background: #f8f9fa;
    }
    
    .address-card.selected {
        border-color: #0d6efd;
        background: #e7f1ff;
    }
    
    .address-card .form-check {
        margin-bottom: 0;
    }
    
    .address-card .form-check-input:checked ~ .form-check-label .address-details {
        color: #0d6efd;
    }
    
    .payment-card {
        padding: 15px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s;
        height: 100%;
        text-align: center;
    }
    
    .payment-card:hover {
        border-color: #0d6efd;
        background: #f8f9fa;
    }
    
    .payment-card .form-check-input:checked {
        border-color: #0d6efd;
        background-color: #0d6efd;
    }
    
    .payment-card .form-check-input:checked ~ .form-check-label {
        color: #0d6efd;
    }
    
    .payment-details {
        padding-top: 5px;
    }
    
    .order-item {
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
    }
    
    .order-item-image img {
        border-radius: 5px;
        object-fit: cover;
    }
    
    .sticky-top {
        position: sticky;
        top: 100px;
        z-index: 100;
    }
    
    @media (max-width: 991.98px) {
        .sticky-top {
            position: relative;
            top: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Same as shipping address toggle
        $('#sameAsShipping').change(function() {
            if ($(this).is(':checked')) {
                $('#billingAddressSection').slideUp();
                $('.billing-address').prop('disabled', true);
            } else {
                $('#billingAddressSection').slideDown();
                $('.billing-address').prop('disabled', false);
            }
        });

        // Auto-select billing address from shipping if same
        $('.shipping-address').change(function() {
            if ($('#sameAsShipping').is(':checked')) {
                const addressId = $(this).val();
                $(`#billing_${addressId}`).prop('checked', true);
            }
        });

        // Address card selection
        $('.address-card').click(function(e) {
            if (!$(e.target).is('input[type="radio"]')) {
                $(this).find('input[type="radio"]').prop('checked', true);
                if ($(this).find('.shipping-address').length) {
                    $('.address-card').removeClass('selected');
                    $(this).addClass('selected');
                }
            }
        });

        // Apply coupon
        $('#applyCouponBtn').click(function() {
            const couponCode = $('#coupon_code').val();
            
            if (!couponCode) {
                toastr.warning('Please enter a coupon code');
                return;
            }
            
            $.ajax({
                url: '{{ route("user.checkout.applyCoupon") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    coupon_code: couponCode
                },
                success: function(response) {
                    if (response.success) {
                        $('#couponForm').hide();
                        $('#appliedCoupon').show();
                        $('#couponCode').text(response.coupon.code);
                        $('#couponDiscountInfo').text('You saved: ₹' + response.coupon.discount);
                        
                        // Update totals
                        $('.subtotal-amount').text('₹' + response.totals.subtotal);
                        $('.discount-amount').text('- ₹' + response.totals.discount);
                        $('.shipping-amount').text(response.totals.shipping === '0.00' ? 'Free' : '₹' + response.totals.shipping);
                        $('.tax-amount').text('₹' + response.totals.tax);
                        $('.total-amount').text('₹' + response.totals.total);
                        
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Something went wrong!');
                }
            });
        });

        // Remove coupon
        $('#removeCouponBtn').click(function() {
            $.ajax({
                url: '{{ route("user.checkout.removeCoupon") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#appliedCoupon').hide();
                        $('#couponForm').show();
                        $('#coupon_code').val('');
                        
                        // Update totals
                        $('.subtotal-amount').text('₹' + response.totals.subtotal);
                        $('.discount-amount').text('- ₹' + response.totals.discount);
                        $('.shipping-amount').text(response.totals.shipping === '0.00' ? 'Free' : '₹' + response.totals.shipping);
                        $('.tax-amount').text('₹' + response.totals.tax);
                        $('.total-amount').text('₹' + response.totals.total);
                        
                        toastr.success(response.message);
                    }
                }
            });
        });

        // Add address form submission
        $('#addAddressForm').submit(function(e) {
            e.preventDefault();
            
            $.ajax({
                url: '{{ route("user.checkout.addAddress") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        $('#addAddressModal').modal('hide');
                        location.reload();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    const errors = xhr.responseJSON.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            toastr.error(value[0]);
                        });
                    } else {
                        toastr.error('Something went wrong!');
                    }
                }
            });
        });

        // Place order
        $('#placeOrderBtn').click(function() {
            // Validate shipping address
            if (!$('input[name="shipping_address_id"]:checked').val()) {
                toastr.error('Please select a shipping address');
                return;
            }
            
            // Validate billing address
            if (!$('#sameAsShipping').is(':checked') && !$('input[name="billing_address_id"]:checked').val()) {
                toastr.error('Please select a billing address');
                return;
            }
            
            // Validate payment method
            if (!$('input[name="payment_method"]:checked').val()) {
                toastr.error('Please select a payment method');
                return;
            }
            
            // Get billing address ID
            let billingAddressId;
            if ($('#sameAsShipping').is(':checked')) {
                billingAddressId = $('input[name="shipping_address_id"]:checked').val();
            } else {
                billingAddressId = $('input[name="billing_address_id"]:checked').val();
            }
            
            // Prepare form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('shipping_address_id', $('input[name="shipping_address_id"]:checked').val());
            formData.append('billing_address_id', billingAddressId);
            formData.append('payment_method', $('input[name="payment_method"]:checked').val());
            formData.append('notes', $('#notes').val());
            
            if ($('#appliedCoupon').is(':visible')) {
                formData.append('coupon_code', $('#couponCode').text());
            }
            
            // Disable button and show loading
            const btn = $(this);
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Processing...');
            
            $.ajax({
                url: '{{ route("user.checkout.process") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.redirect) {
                        window.location.href = response.redirect;
                    }
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('<i class="fas fa-lock me-2"></i> Place Order');
                    
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        if (errors) {
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                        }
                    } else {
                        toastr.error(xhr.responseJSON.message || 'Something went wrong!');
                    }
                }
            });
        });
    });
</script>
@endpush