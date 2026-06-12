<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 10px 0 0;
            opacity: 0.9;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .order-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .order-info h2 {
            margin-top: 0;
            color: #4a5568;
            font-size: 20px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #718096;
        }
        .info-value {
            color: #2d3748;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .table th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e9ecef;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e9ecef;
        }
        .table tr:last-child td {
            border-bottom: none;
        }
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
        }
        .total-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .grand-total {
            font-size: 18px;
            font-weight: 700;
            color: #4e73df;
            border-top: 2px solid #e9ecef;
            padding-top: 15px;
            margin-top: 5px;
        }
        .address-section {
            display: flex;
            gap: 20px;
            margin: 20px 0;
        }
        .address-box {
            flex: 1;
            background: white;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .address-box h3 {
            margin-top: 0;
            color: #4a5568;
            font-size: 16px;
            border-bottom: 1px solid #e9ecef;
            padding-bottom: 8px;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #718096;
            font-size: 14px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #4e73df;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .button:hover {
            background: #2e59d9;
        }
        @media (max-width: 600px) {
            .address-section {
                flex-direction: column;
            }
            .table {
                font-size: 14px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎉 Order Confirmed!</h1>
        <p>Thank you for shopping with eCart Electronics</p>
    </div>
    
    <div class="content">
        <div class="order-info">
            <h2>Order Details</h2>
            <div class="info-row">
                <span class="info-label">Order Number:</span>
                <span class="info-value"><strong>{{ $order->order_number }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">Order Date:</span>
                <span class="info-value">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Method:</span>
                <span class="info-value">
                    @switch($order->payment_method)
                        @case('cod')
                            Cash on Delivery
                            @break
                        @case('card')
                            Credit/Debit Card
                            @break
                        @case('upi')
                            UPI
                            @break
                        @case('netbanking')
                            Net Banking
                            @break
                        @case('wallet')
                            Wallet
                            @break
                        @case('emi')
                            EMI
                            @break
                        @default
                            {{ $order->payment_method }}
                    @endswitch
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Payment Status:</span>
                <span class="info-value">
                    <span class="badge {{ $order->payment_status == 'completed' ? 'badge-success' : 'badge-warning' }}">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Order Status:</span>
                <span class="info-value">
                    <span class="badge badge-success">{{ ucfirst($order->status) }}</span>
                </span>
            </div>
        </div>

        <h2 style="color: #4a5568; margin-bottom: 15px;">Order Items</h2>
        
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orderItems as $item)
                <tr>
                    <td>
                        <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                             alt="{{ $item->product->name }}"
                             class="product-image">
                    </td>
                    <td>
                        <strong>{{ $item->product->name }}</strong>
                        <br>
                        <small style="color: #718096;">SKU: {{ $item->product->sku }}</small>
                    </td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td><strong>₹{{ number_format($item->total, 2) }}</strong></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="address-section">
            <div class="address-box">
                <h3>📦 Shipping Address</h3>
                <p style="margin: 0; color: #4a5568;">
                    <strong>{{ $order->shippingAddress->address_line_1 }}</strong><br>
                    @if($order->shippingAddress->address_line_2)
                        {{ $order->shippingAddress->address_line_2 }}<br>
                    @endif
                    {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} - {{ $order->shippingAddress->zip_code }}<br>
                    {{ $order->shippingAddress->country }}
                </p>
            </div>
            
            <div class="address-box">
                <h3>💰 Billing Address</h3>
                <p style="margin: 0; color: #4a5568;">
                    <strong>{{ $order->billingAddress->address_line_1 }}</strong><br>
                    @if($order->billingAddress->address_line_2)
                        {{ $order->billingAddress->address_line_2 }}<br>
                    @endif
                    {{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} - {{ $order->billingAddress->zip_code }}<br>
                    {{ $order->billingAddress->country }}
                </p>
            </div>
        </div>

        <div class="total-section">
            <div class="total-row">
                <span>Subtotal:</span>
                <span>₹{{ number_format($order->subtotal, 2) }}</span>
            </div>
            <div class="total-row">
                <span>Shipping:</span>
                <span>
                    @if($order->shipping_cost > 0)
                        ₹{{ number_format($order->shipping_cost, 2) }}
                    @else
                        <span style="color: #28a745;">Free</span>
                    @endif
                </span>
            </div>
            <div class="total-row">
                <span>Tax (8% GST):</span>
                <span>₹{{ number_format($order->tax, 2) }}</span>
            </div>
            @if($order->discount_amount > 0)
            <div class="total-row" style="color: #28a745;">
                <span>Discount:</span>
                <span>- ₹{{ number_format($order->discount_amount, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand-total">
                <span>Total Amount:</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
        </div>

        @if($order->notes)
        <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-radius: 8px;">
            <h4 style="margin: 0 0 10px; color: #856404;">📝 Order Notes</h4>
            <p style="margin: 0; color: #856404;">{{ $order->notes }}</p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('user.orders.show', $order->id) }}" class="button">
                <i class="fas fa-eye" style="margin-right: 8px;"></i> View Order Details
            </a>
        </div>

        <div class="footer">
            <p>📱 Need help? Contact our support team at support@ecart.com or call us at +91 98765 43210</p>
            <p>Thank you for choosing eCart Electronics! We hope you enjoy your purchase. 😊</p>
            <hr style="border: none; border-top: 1px solid #e9ecef; margin: 20px 0;">
            <p style="margin: 0;">
                <small>&copy; {{ date('Y') }} eCart Electronics. All rights reserved.</small><br>
                <small>This is a system generated email. Please do not reply.</small>
            </p>
        </div>
    </div>
</body>
</html>