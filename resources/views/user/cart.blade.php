@extends('user.layouts.app')

@section('title', 'Shopping Cart - eCart Electronics')
@section('page-title', 'Your Shopping Cart')

@section('content')
<!-- Cart Header Banner -->
<section style="background: linear-gradient(145deg, #0b2b4f 0%, #1a3e5c 100%); padding: 40px 0; margin-bottom: 40px;">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                    <span style="background: rgba(255,255,255,0.12); padding: 6px 16px; border-radius: 40px; color: #ffd966; font-size: 14px; font-weight: 600;">
                        <i class="fas fa-shopping-cart" style="margin-right: 6px;"></i> 
                        @if($cartItems && count($cartItems) > 0)
                            {{ $totalItems }} Items in Cart
                        @else
                            Cart Empty
                        @endif
                    </span>
                    <span style="color: rgba(255,255,255,0.7); font-size: 14px;">
                        <i class="fas fa-truck"></i> Free shipping on orders ₹999+
                    </span>
                </div>
                <h1 style="font-size: 38px; font-weight: 700; color: white; margin-bottom: 8px; line-height: 1.2;">
                    @if($cartItems && count($cartItems) > 0)
                        Your Cart <span style="color: #ffd966;">₹{{ number_format($subtotal, 0) }}</span>
                    @else
                        Your Cart is <span style="color: #ffd966;">Empty</span>
                    @endif
                </h1>
                <div style="display: flex; align-items: center; gap: 24px;">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-check-circle" style="color: #4ade80; font-size: 16px;"></i>
                        <span style="color: rgba(255,255,255,0.9); font-size: 14px;">Secure Checkout</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-lock" style="color: #4ade80; font-size: 16px;"></i>
                        <span style="color: rgba(255,255,255,0.9); font-size: 14px;">256-bit SSL</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-undo-alt" style="color: #4ade80; font-size: 16px;"></i>
                        <span style="color: rgba(255,255,255,0.9); font-size: 14px;">7 Day Returns</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border-radius: 16px; padding: 24px; border: 1px solid rgba(255,255,255,0.2);">
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 56px; height: 56px; background: rgba(255,215,0,0.2); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-gift" style="color: #ffd966; font-size: 28px;"></i>
                        </div>
                        <div>
                            <div style="color: rgba(255,255,255,0.8); font-size: 13px; margin-bottom: 4px;">Today's Offer</div>
                            <div style="color: white; font-size: 20px; font-weight: 700;">Extra 5% Off</div>
                            <div style="color: #ffd966; font-size: 12px;">Use code: CART5</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container" style="margin-bottom: 60px;">
    @if($cartItems && count($cartItems) > 0)
        <div class="row g-4">
            <!-- Cart Items Section -->
            <div class="col-lg-8">
                <!-- Cart Header -->
                <div style="background: white; border-radius: 20px; padding: 20px 24px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 4px 16px rgba(0,0,0,0.02); border: 1px solid #edf2f7;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 44px; height: 44px; background: #f0f7ff; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shopping-bag" style="color: #1e3a5f; font-size: 20px;"></i>
                        </div>
                        <div>
                            <h5 style="font-size: 18px; font-weight: 700; color: #0f172a; margin: 0;">Cart Items</h5>
                            <p style="color: #5b6f82; font-size: 14px; margin: 4px 0 0;">{{ $totalItems }} item(s) in your cart</p>
                        </div>
                    </div>
                    
                    <form action="{{ route('user.cart.clear') }}" method="POST" id="clearCartForm" style="margin: 0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to clear your cart?')" 
                                style="display: flex; align-items: center; gap: 8px; background: #fff5f5; border: 1px solid #fed7d7; border-radius: 12px; padding: 10px 20px; color: #e53e3e; font-size: 14px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                                onmouseover="this.style.background='#e53e3e'; this.style.color='white'; this.style.borderColor='#e53e3e'"
                                onmouseout="this.style.background='#fff5f5'; this.style.color='#e53e3e'; this.style.borderColor='#fed7d7'">
                            <i class="fas fa-trash-alt"></i>
                            Clear Cart
                        </button>
                    </form>
                </div>

                <!-- Cart Items List -->
                <div style="display: flex; flex-direction: column; gap: 16px;">
                    @foreach($cartItems as $item)
                    <div id="cart-item-{{ $item->id }}" 
                         data-id="{{ $item->id }}"
                         data-update-url="{{ route('user.cart.update', $item->id) }}"
                         data-remove-url="{{ route('user.cart.remove', $item->id) }}"
                         style="background: white; border-radius: 20px; padding: 24px; border: 1px solid #edf2f7; transition: all 0.3s; position: relative;"
                         onmouseover="this.style.boxShadow='0 12px 24px -8px rgba(30,58,95,0.12)'; this.style.borderColor='transparent'"
                         onmouseout="this.style.boxShadow='0 2px 8px rgba(0,0,0,0.02)'; this.style.borderColor='#edf2f7'">
                        
                        <div class="row align-items-center">
                            <!-- Product Image & Info -->
                            <div class="col-md-5">
                                <div style="display: flex; align-items: center; gap: 16px;">
                                    <!-- Product Image - PROPERLY DISPLAYED -->
                                    <div style="width: 100px; height: 100px; background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-radius: 16px; display: flex; align-items: center; justify-content: center; overflow: hidden; border: 1px solid #e2e8f0; position: relative; flex-shrink: 0;">
                                        <a href="{{ route('user.products.show', $item->product->slug) }}" style="display: block; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                                            <img src="{{ $item->product->main_image ? asset('storage/' . $item->product->main_image) : asset('images/no-image.png') }}" 
                                                 alt="{{ $item->product->name }}" 
                                                 style="max-width: 90%; max-height: 90%; width: auto; height: auto; object-fit: contain; border-radius: 8px; transition: transform 0.2s;"
                                                 onmouseover="this.style.transform='scale(1.08)'"
                                                 onmouseout="this.style.transform='scale(1)'">
                                        </a>
                                        @if($item->product->has_discount)
                                            <span style="position: absolute; top: -5px; left: -5px; background: #ef4444; color: white; padding: 4px 10px; border-radius: 40px; font-size: 11px; font-weight: 700; box-shadow: 0 4px 8px rgba(239,68,68,0.2);">
                                                -{{ round($item->product->discount_percentage) }}%
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div style="flex: 1;">
                                        <a href="{{ route('user.products.show', $item->product->slug) }}" style="text-decoration: none;">
                                            <h6 style="font-size: 16px; font-weight: 700; color: #0f172a; margin-bottom: 6px; line-height: 1.4;">
                                                {{ $item->product->name }}
                                            </h6>
                                        </a>
                                        
                                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px; flex-wrap: wrap;">
                                            @if($item->product->category)
                                                <span style="background: #f0f7ff; color: #1e3a5f; padding: 4px 12px; border-radius: 40px; font-size: 11px; font-weight: 600;">
                                                    {{ $item->product->category->name }}
                                                </span>
                                            @endif
                                            @if($item->product->brand)
                                                <span style="color: #5b6f82; font-size: 11px; display: flex; align-items: center; gap: 4px;">
                                                    <i class="fas fa-certificate" style="color: #94a3b8;"></i>
                                                    {{ $item->product->brand->name }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <small style="color: #94a3b8; font-size: 11px; display: flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-barcode"></i> SKU: {{ $item->product->sku ?? 'N/A' }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <div class="col-md-2">
                                <div style="text-align: left;">
                                    <span style="font-size: 18px; font-weight: 700; color: #1e3a5f;">₹{{ number_format($item->item_price, 0) }}</span>
                                    @if($item->product->has_discount)
                                        <div style="display: flex; align-items: center; gap: 6px; margin-top: 4px;">
                                            <span style="font-size: 13px; color: #94a3b8; text-decoration: line-through;">₹{{ number_format($item->product->price, 0) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Quantity Control -->
                            <div class="col-md-3">
                                <div style="display: flex; flex-direction: column;">
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <span style="font-size: 13px; color: #5b6f82; font-weight: 600; margin-right: 8px;">Qty:</span>
                                        <div style="display: flex; align-items: center; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">
                                            <button type="button" 
                                                    onclick="updateQuantity('{{ $item->id }}', {{ $item->quantity - 1 }})"
                                                    {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                    style="border: none; background: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: {{ $item->quantity <= 1 ? '#cbd5e1' : '#1e3a5f' }}; font-weight: 600; cursor: pointer; transition: all 0.2s; border-right: 1px solid #e2e8f0;"
                                                    onmouseover="if(!this.disabled) this.style.background='#1e3a5f'; this.style.color='white'"
                                                    onmouseout="if(!this.disabled) this.style.background='white'; this.style.color='#1e3a5f'">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            
                                            <input type="number" 
                                                   id="quantity-{{ $item->id }}"
                                                   value="{{ $item->quantity }}" 
                                                   min="1"
                                                   max="{{ $item->product->quantity }}"
                                                   data-item-id="{{ $item->id }}"
                                                   onchange="updateQuantity('{{ $item->id }}', this.value)"
                                                   style="width: 60px; border: none; text-align: center; padding: 10px 0; font-size: 15px; font-weight: 700; color: #0f172a; background: white; -moz-appearance: textfield;"
                                                   onfocus="this.style.outline='none'">
                                            
                                            <button type="button" 
                                                    onclick="updateQuantity('{{ $item->id }}', {{ $item->quantity + 1 }})"
                                                    {{ $item->quantity >= $item->product->quantity ? 'disabled' : '' }}
                                                    style="border: none; background: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: {{ $item->quantity >= $item->product->quantity ? '#cbd5e1' : '#1e3a5f' }}; font-weight: 600; cursor: pointer; transition: all 0.2s; border-left: 1px solid #e2e8f0;"
                                                    onmouseover="if(!this.disabled) this.style.background='#1e3a5f'; this.style.color='white'"
                                                    onmouseout="if(!this.disabled) this.style.background='white'; this.style.color='#1e3a5f'">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    @if($item->stock_status != 'available')
                                        <div style="margin-top: 8px;">
                                            <span style="background: #fee2e2; color: #dc2626; padding: 4px 12px; border-radius: 40px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                                <i class="fas fa-exclamation-circle"></i>
                                                @if($item->stock_status == 'insufficient')
                                                    Only {{ $item->product->quantity }} available
                                                @else
                                                    Out of stock
                                                @endif
                                            </span>
                                        </div>
                                    @else
                                        <span style="margin-top: 8px; font-size: 11px; color: #10b981; display: flex; align-items: center; gap: 4px;">
                                            <i class="fas fa-check-circle"></i> In Stock
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Total & Action -->
                            <div class="col-md-2">
                                <div style="display: flex; flex-direction: column; align-items: flex-end;">
                                    <span style="font-size: 20px; font-weight: 800; color: #1e3a5f; margin-bottom: 8px;">₹{{ number_format($item->item_total, 0) }}</span>
                                    
                                    <button type="button" 
                                            onclick="removeFromCart('{{ $item->id }}')"
                                            style="background: transparent; border: none; color: #94a3b8; display: flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; cursor: pointer; padding: 8px 12px; border-radius: 10px; transition: all 0.2s;"
                                            onmouseover="this.style.background='#fee2e2'; this.style.color='#dc2626'"
                                            onmouseout="this.style.background='transparent'; this.style.color='#94a3b8'">
                                        <i class="fas fa-trash-alt"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Continue Shopping & Wishlist -->
                <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 30px;">
                    <a href="{{ route('user.products.index') }}" 
                       style="display: flex; align-items: center; gap: 10px; background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 24px; color: #1e3a5f; text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.2s;"
                       onmouseover="this.style.background='#f8fafc'; this.style.borderColor='#1e3a5f'"
                       onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="fas fa-arrow-left"></i>
                        Continue Shopping
                    </a>
                    
                    <a href="{{ route('user.wishlist.index') }}" 
                       style="display: flex; align-items: center; gap: 10px; background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 24px; color: #e53e3e; text-decoration: none; font-weight: 600; font-size: 15px; transition: all 0.2s;"
                       onmouseover="this.style.background='#fff5f5'; this.style.borderColor='#e53e3e'"
                       onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                        <i class="fas fa-heart"></i>
                        Go to Wishlist
                    </a>
                </div>
            </div>

            <!-- Order Summary - Premium Sidebar -->
            <div class="col-lg-4">
                <!-- Order Summary Card -->
                <div style="background: white; border-radius: 24px; padding: 28px; box-shadow: 0 8px 24px rgba(30,58,95,0.08); border: 1px solid #edf2f7; position: sticky; top: 100px;">
                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 24px;">
                        <div style="width: 48px; height: 48px; background: #f0f7ff; border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-file-invoice" style="color: #1e3a5f; font-size: 22px;"></i>
                        </div>
                        <div>
                            <h5 style="font-size: 20px; font-weight: 700; color: #0f172a; margin: 0;">Order Summary</h5>
                            <p style="color: #5b6f82; font-size: 13px; margin: 4px 0 0;">Review your order</p>
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div style="background: #f8fafc; border-radius: 18px; padding: 20px; margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span style="color: #5b6f82; font-size: 15px;">Subtotal</span>
                            <span style="font-size: 18px; font-weight: 700; color: #0f172a;" id="subtotal">₹{{ number_format($subtotal, 0) }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span style="color: #5b6f82; font-size: 15px;">Shipping</span>
                            <span style="font-size: 16px; font-weight: 600; color: #10b981;" id="shipping">
                                @if($shipping > 0)
                                    ₹{{ number_format($shipping, 0) }}
                                @else
                                    Free
                                @endif
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                            <span style="color: #5b6f82; font-size: 15px;">Tax (8%)</span>
                            <span style="font-size: 16px; font-weight: 600; color: #0f172a;" id="tax">₹{{ number_format($tax, 0) }}</span>
                        </div>
                        
                        @if($subtotal < 1000)
                        <div style="background: #fff7ed; border-radius: 12px; padding: 16px; margin-top: 16px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                                <i class="fas fa-truck" style="color: #f97316;"></i>
                                <span style="font-size: 13px; font-weight: 700; color: #9a3412;">Add ₹{{ number_format(1000 - $subtotal, 0) }} more for FREE shipping!</span>
                            </div>
                            <div style="height: 6px; background: #fed7aa; border-radius: 40px; overflow: hidden;">
                                <div style="width: {{ ($subtotal / 1000) * 100 }}%; height: 100%; background: #f97316; border-radius: 40px;"></div>
                            </div>
                        </div>
                        @endif
                        
                        <hr style="border-top: 1px dashed #cbd5e1; margin: 20px 0;">
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 18px; font-weight: 700; color: #0f172a;">Total</span>
                            <span style="font-size: 28px; font-weight: 800; color: #1e3a5f;" id="total">₹{{ number_format($total, 0) }}</span>
                        </div>
                    </div>

                    <!-- Coupon Code -->
                    <div style="margin-bottom: 24px;">
                        <label style="display: block; font-size: 14px; font-weight: 600; color: #0f172a; margin-bottom: 10px;">
                            <i class="fas fa-tag" style="color: #1e3a5f; margin-right: 6px;"></i>
                            Apply Coupon
                        </label>
                        <div style="display: flex; gap: 8px;">
                            <div style="flex: 1; position: relative;">
                                <i class="fas fa-percent" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 14px;"></i>
                                <input type="text" 
                                       id="coupon" 
                                       placeholder="Enter coupon code"
                                       style="width: 100%; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 14px 14px 40px; font-size: 14px; color: #0f172a; background: white; transition: all 0.2s;"
                                       onfocus="this.style.borderColor='#1e3a5f'; this.style.boxShadow='0 0 0 3px rgba(30,58,95,0.1)'"
                                       onblur="this.style.borderColor='#e2e8f0'; this.style.boxShadow='none'">
                            </div>
                            <button onclick="applyCoupon()"
                                    style="background: #1e3a5f; color: white; border: none; border-radius: 14px; padding: 0 24px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(30,58,95,0.2);"
                                    onmouseover="this.style.background='#152c44'; this.style.transform='translateY(-2px)'"
                                    onmouseout="this.style.background='#1e3a5f'; this.style.transform='translateY(0)'">
                                Apply
                            </button>
                        </div>
                        <div id="coupon-message" style="margin-top: 10px; font-size: 13px;"></div>
                    </div>

                    <!-- Checkout Button -->
                    <a href="{{ route('user.checkout.index') }}" 
                       style="display: flex; align-items: center; justify-content: center; gap: 12px; background: linear-gradient(145deg, #1e3a5f, #152c44); color: white; border: none; border-radius: 18px; padding: 18px; font-size: 18px; font-weight: 700; text-decoration: none; transition: all 0.3s; margin-bottom: 20px; box-shadow: 0 8px 20px rgba(30,58,95,0.3);"
                       onmouseover="this.style.background='linear-gradient(145deg, #152c44, #0e1f2e)'; this.style.transform='translateY(-2px)'"
                       onmouseout="this.style.background='linear-gradient(145deg, #1e3a5f, #152c44)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-lock"></i>
                        Proceed to Checkout
                    </a>

                    <!-- Payment Methods -->
                    <div style="text-align: center; padding-top: 16px; border-top: 1px solid #edf2f7;">
                        <p style="color: #5b6f82; font-size: 13px; margin-bottom: 12px;">We accept secure payments</p>
                        <div style="display: flex; align-items: center; justify-content: center; gap: 16px;">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" alt="Visa" style="height: 30px; opacity: 0.7;">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" alt="Mastercard" style="height: 30px; opacity: 0.7;">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196549.png" alt="Amex" style="height: 30px; opacity: 0.7;">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196565.png" alt="PayPal" style="height: 30px; opacity: 0.7;">
                            <img src="https://cdn-icons-png.flaticon.com/512/196/196543.png" alt="Rupay" style="height: 30px; opacity: 0.7;">
                        </div>
                    </div>
                </div>

                <!-- Secure Shopping Guarantee -->
                <div style="background: white; border-radius: 20px; padding: 24px; margin-top: 20px; border: 1px solid #edf2f7;">
                    <div style="display: flex; align-items: center; gap: 16px; margin-bottom: 16px;">
                        <div style="width: 48px; height: 48px; background: #ecfdf3; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-shield-alt" style="color: #10b981; font-size: 22px;"></i>
                        </div>
                        <div>
                            <h6 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Secure Shopping</h6>
                            <p style="color: #5b6f82; font-size: 12px; margin: 0; line-height: 1.5;">Your data is protected with 256-bit SSL encryption</p>
                        </div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 16px;">
                        <div style="width: 48px; height: 48px; background: #f0f9ff; border-radius: 14px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-truck-fast" style="color: #0284c7; font-size: 22px;"></i>
                        </div>
                        <div>
                            <h6 style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">Free Shipping</h6>
                            <p style="color: #5b6f82; font-size: 12px; margin: 0;">On orders above ₹1000. Delivery in 3-5 days</p>
                        </div>
                    </div>
                    
                    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #edf2f7;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span style="display: flex; align-items: center; gap: 6px; color: #5b6f82; font-size: 12px;">
                                <i class="fas fa-check-circle" style="color: #10b981;"></i> 7 Day Returns
                            </span>
                            <span style="display: flex; align-items: center; gap: 6px; color: #5b6f82; font-size: 12px;">
                                <i class="fas fa-check-circle" style="color: #10b981;"></i> Warranty Included
                            </span>
                            <span style="display: flex; align-items: center; gap: 6px; color: #5b6f82; font-size: 12px;">
                                <i class="fas fa-check-circle" style="color: #10b981;"></i> Genuine Products
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart - Premium Design -->
        <div style="background: white; border-radius: 32px; padding: 80px 40px; text-align: center; border: 1px solid #edf2f7; max-width: 700px; margin: 0 auto;">
            <div style="width: 160px; height: 160px; background: linear-gradient(145deg, #f8fafc, #f1f5f9); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 32px; box-shadow: 0 12px 24px rgba(0,0,0,0.02);">
                <i class="fas fa-shopping-cart" style="font-size: 64px; color: #94a3b8;"></i>
            </div>
            
            <h2 style="font-size: 32px; font-weight: 800; color: #0f172a; margin-bottom: 12px;">Your cart is empty</h2>
            <p style="color: #5b6f82; font-size: 16px; margin-bottom: 32px; max-width: 400px; margin-left: auto; margin-right: auto;">
                Looks like you haven't added anything to your cart yet. Explore our collection and find your perfect product!
            </p>
            
            <div style="display: flex; gap: 16px; justify-content: center;">
                <a href="{{ route('user.products.index') }}" 
                   style="display: inline-flex; align-items: center; gap: 12px; background: linear-gradient(145deg, #1e3a5f, #152c44); color: white; padding: 16px 36px; border-radius: 40px; text-decoration: none; font-size: 16px; font-weight: 700; transition: all 0.2s; box-shadow: 0 8px 20px rgba(30,58,95,0.25);"
                   onmouseover="this.style.background='linear-gradient(145deg, #152c44, #0e1f2e)'; this.style.transform='translateY(-2px)'"
                   onmouseout="this.style.background='linear-gradient(145deg, #1e3a5f, #152c44)'; this.style.transform='translateY(0)'">
                    <i class="fas fa-shopping-bag"></i>
                    Start Shopping
                </a>
                
                <a href="{{ route('user.wishlist.index') }}" 
                   style="display: inline-flex; align-items: center; gap: 12px; background: white; border: 1px solid #e2e8f0; color: #e53e3e; padding: 16px 36px; border-radius: 40px; text-decoration: none; font-size: 16px; font-weight: 700; transition: all 0.2s;"
                   onmouseover="this.style.background='#fff5f5'; this.style.borderColor='#e53e3e'"
                   onmouseout="this.style.background='white'; this.style.borderColor='#e2e8f0'">
                    <i class="fas fa-heart"></i>
                    View Wishlist
                </a>
            </div>
            
            <!-- Popular Categories -->
            <div style="margin-top: 48px; padding-top: 32px; border-top: 1px solid #edf2f7;">
                <p style="color: #5b6f82; font-size: 14px; margin-bottom: 20px;">Popular Categories</p>
                <div style="display: flex; align-items: center; justify-content: center; gap: 16px; flex-wrap: wrap;">
                    <a href="{{ route('user.products.index', ['category' => 'electronics']) }}" style="background: #f8fafc; padding: 10px 24px; border-radius: 40px; color: #1e3a5f; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;">📱 Electronics</a>
                    <a href="{{ route('user.products.index', ['category' => 'laptops']) }}" style="background: #f8fafc; padding: 10px 24px; border-radius: 40px; color: #1e3a5f; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;">💻 Laptops</a>
                    <a href="{{ route('user.products.index', ['category' => 'mobiles']) }}" style="background: #f8fafc; padding: 10px 24px; border-radius: 40px; color: #1e3a5f; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;">📱 Mobiles</a>
                    <a href="{{ route('user.products.index', ['category' => 'accessories']) }}" style="background: #f8fafc; padding: 10px 24px; border-radius: 40px; color: #1e3a5f; text-decoration: none; font-size: 14px; font-weight: 600; transition: all 0.2s;">🎧 Accessories</a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal - Premium -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 24px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-body" style="padding: 32px; text-align: center;">
                <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <i class="fas fa-trash-alt" style="font-size: 32px; color: #dc2626;"></i>
                </div>
                <h5 style="font-size: 24px; font-weight: 700; color: #0f172a; margin-bottom: 12px;">Remove Item</h5>
                <p style="color: #5b6f82; font-size: 16px; margin-bottom: 28px;">Are you sure you want to remove this item from your cart?</p>
                
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" data-bs-dismiss="modal" 
                            style="background: white; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px 28px; color: #5b6f82; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s;"
                            onmouseover="this.style.background='#f8fafc'"
                            onmouseout="this.style.background='white'">
                        Cancel
                    </button>
                    <button type="button" id="confirmDelete"
                            style="background: #dc2626; border: none; border-radius: 14px; padding: 14px 28px; color: white; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.2s; box-shadow: 0 4px 12px rgba(220,38,38,0.3);"
                            onmouseover="this.style.background='#b91c1c'"
                            onmouseout="this.style.background='#dc2626'">
                        Yes, Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    /* Remove spinner from number input */
    input[type=number]::-webkit-outer-spin-button,
    input[type=number]::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    
    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    #cart-item-{{ $item->id ?? '' }} {
        animation: fadeIn 0.3s ease;
    }
    
    /* Hover Effects */
    .cart-item-hover:hover {
        box-shadow: 0 12px 24px -8px rgba(30,58,95,0.12);
        border-color: transparent;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        [style*="grid-template-columns: repeat(3, 1fr)"] {
            grid-template-columns: repeat(2, 1fr) !important;
        }
    }
    
    @media (max-width: 576px) {
        [style*="grid-template-columns: repeat(3, 1fr)"] {
            grid-template-columns: 1fr !important;
        }
    }
</style>
@endsection

@push('scripts')
<script>
    let deleteItemId = null;

    // Update quantity
    function updateQuantity(itemId, newQuantity) {
        const row = $(`#cart-item-${itemId}`);
        const quantityInput = $(`#quantity-${itemId}`);
        const currentQuantity = parseInt(quantityInput.val());
        newQuantity = parseInt(newQuantity);
        
        if (newQuantity < 1) {
            return;
        }
        
        const maxQuantity = parseInt(quantityInput.attr('max'));
        if (newQuantity > maxQuantity) {
            toastr.error(`Only ${maxQuantity} items available in stock.`);
            return;
        }
        
        if (newQuantity === currentQuantity) {
            return;
        }
        
        const updateUrl = row.data('update-url');
        
        $.ajax({
            url: updateUrl,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                quantity: newQuantity
            },
            success: function(response) {
                if (response.success) {
                    quantityInput.val(newQuantity);
                    updateCartTotals(response.totals);
                    updateCartCount(response.cart_count);
                    toastr.success(response.message);
                    updateQuantityButtons(itemId, newQuantity, maxQuantity);
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.message) {
                    toastr.error(response.message);
                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    }
    
    // Update quantity buttons state
    function updateQuantityButtons(itemId, currentQuantity, maxQuantity) {
        const row = $(`#cart-item-${itemId}`);
        const minusBtn = row.find('button:first');
        const plusBtn = row.find('button:last');
        
        if (currentQuantity <= 1) {
            minusBtn.prop('disabled', true);
            minusBtn.css({'color': '#cbd5e1', 'background': 'white'});
        } else {
            minusBtn.prop('disabled', false);
            minusBtn.css({'color': '#1e3a5f', 'background': 'white'});
        }
        
        if (currentQuantity >= maxQuantity) {
            plusBtn.prop('disabled', true);
            plusBtn.css({'color': '#cbd5e1', 'background': 'white'});
        } else {
            plusBtn.prop('disabled', false);
            plusBtn.css({'color': '#1e3a5f', 'background': 'white'});
        }
    }
    
    // Remove from cart
    function removeFromCart(itemId) {
        deleteItemId = itemId;
        $('#deleteModal').modal('show');
    }
    
    // Confirm delete
    $('#confirmDelete').click(function() {
        if (deleteItemId) {
            const row = $(`#cart-item-${deleteItemId}`);
            const removeUrl = row.data('remove-url');
            
            $.ajax({
                url: removeUrl,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $(`#cart-item-${deleteItemId}`).fadeOut(300, function() {
                            $(this).remove();
                            updateCartCount(response.cart_count);
                            if (response.totals) {
                                updateCartTotals(response.totals);
                            }
                            toastr.success(response.message);
                            if ($('[id^="cart-item-"]').length === 0) {
                                setTimeout(() => location.reload(), 500);
                            }
                        });
                    } else {
                        toastr.error(response.message);
                    }
                    $('#deleteModal').modal('hide');
                    deleteItemId = null;
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    if (response && response.message) {
                        toastr.error(response.message);
                    } else {
                        toastr.error('Something went wrong!');
                    }
                    $('#deleteModal').modal('hide');
                    deleteItemId = null;
                }
            });
        }
    });
    
    // Update cart totals
    function updateCartTotals(totals) {
        $('#subtotal').text('₹' + totals.subtotal.toLocaleString('en-IN', {maximumFractionDigits: 0}));
        $('#shipping').text(totals.shipping > 0 ? '₹' + totals.shipping.toLocaleString('en-IN', {maximumFractionDigits: 0}) : 'Free');
        $('#tax').text('₹' + totals.tax.toLocaleString('en-IN', {maximumFractionDigits: 0}));
        $('#total').text('₹' + totals.total.toLocaleString('en-IN', {maximumFractionDigits: 0}));
    }
    
    // Update cart count
    function updateCartCount(count) {
        $('.cart-count').text(count);
    }
    
    // Apply coupon
    function applyCoupon() {
        const couponCode = $('#coupon').val().trim();
        const messageDiv = $('#coupon-message');
        
        if (!couponCode) {
            messageDiv.html('<span style="color: #dc2626; display: flex; align-items: center; gap: 4px;"><i class="fas fa-exclamation-circle"></i> Please enter a coupon code.</span>');
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
                    messageDiv.html('<span style="color: #10b981; display: flex; align-items: center; gap: 4px;"><i class="fas fa-check-circle"></i> ' + response.message + '</span>');
                    if (response.totals) {
                        updateCartTotals(response.totals);
                    }
                    $('#coupon').val('');
                    $('#coupon').attr('disabled', true);
                } else {
                    messageDiv.html('<span style="color: #dc2626; display: flex; align-items: center; gap: 4px;"><i class="fas fa-exclamation-circle"></i> ' + response.message + '</span>');
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.message) {
                    messageDiv.html('<span style="color: #dc2626; display: flex; align-items: center; gap: 4px;"><i class="fas fa-exclamation-circle"></i> ' + response.message + '</span>');
                } else {
                    messageDiv.html('<span style="color: #dc2626; display: flex; align-items: center; gap: 4px;"><i class="fas fa-exclamation-circle"></i> Something went wrong!</span>');
                }
            }
        });
    }
    
    // Initialize
    $(document).ready(function() {
        // Format all quantity inputs
        $('input[type=number]').on('input', function() {
            const value = parseInt($(this).val());
            const min = parseInt($(this).attr('min'));
            const max = parseInt($(this).attr('max'));
            
            if (value < min) {
                $(this).val(min);
            } else if (value > max) {
                $(this).val(max);
            }
        });
        
        // Initialize quantity buttons
        @if($cartItems && count($cartItems) > 0)
            @foreach($cartItems as $item)
                updateQuantityButtons('{{ $item->id }}', {{ $item->quantity }}, {{ $item->product->quantity }});
            @endforeach
        @endif
    });
</script>
@endpush