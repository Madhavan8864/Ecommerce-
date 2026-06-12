<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            padding: 40px 20px;
            color: #333;
            line-height: 1.6;
        }
        
        .invoice-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            text-align: center;
        }
        
        .invoice-header h1 {
            font-size: 32px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .invoice-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .invoice-content {
            padding: 40px;
        }
        
        .company-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #e9ecef;
        }
        
        .company-info h2 {
            color: #4e73df;
            margin-bottom: 5px;
            font-size: 24px;
        }
        
        .company-info p {
            color: #6c757d;
            font-size: 14px;
            margin: 3px 0;
        }
        
        .invoice-info {
            text-align: right;
        }
        
        .invoice-info h3 {
            color: #4e73df;
            margin-bottom: 5px;
            font-size: 20px;
        }
        
        .invoice-info p {
            color: #6c757d;
            font-size: 14px;
            margin: 3px 0;
        }
        
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .badge-success {
            background: #d4edda;
            color: #155724;
        }
        
        .badge-warning {
            background: #fff3cd;
            color: #856404;
        }
        
        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }
        
        .badge-info {
            background: #d1ecf1;
            color: #0c5460;
        }
        
        .address-section {
            display: flex;
            gap: 30px;
            margin-bottom: 30px;
        }
        
        .address-box {
            flex: 1;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            border-left: 4px solid #4e73df;
        }
        
        .address-box h4 {
            color: #4e73df;
            margin-bottom: 15px;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .address-box p {
            color: #6c757d;
            font-size: 14px;
            margin: 5px 0;
            line-height: 1.7;
        }
        
        .order-summary {
            margin-bottom: 30px;
        }
        
        .order-summary h3 {
            color: #4e73df;
            margin-bottom: 15px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        table thead {
            background: #f8f9fa;
        }
        
        table th {
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #4a5568;
            border-bottom: 2px solid #e9ecef;
        }
        
        table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            color: #6c757d;
        }
        
        table tbody tr:hover {
            background: #f8f9fa;
        }
        
        table tfoot td {
            padding: 15px;
            font-weight: 600;
            border-top: 2px solid #e9ecef;
        }
        
        .product-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .product-image {
            width: 50px;
            height: 50px;
            border-radius: 5px;
            object-fit: cover;
            border: 1px solid #e9ecef;
        }
        
        .product-details h5 {
            margin: 0;
            color: #2d3748;
            font-size: 14px;
            font-weight: 600;
        }
        
        .product-details small {
            color: #a0aec0;
            font-size: 11px;
        }
        
        .price-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dashed #e9ecef;
        }
        
        .price-row:last-child {
            border-bottom: none;
        }
        
        .price-row.total {
            font-size: 18px;
            font-weight: 700;
            color: #4e73df;
            padding-top: 15px;
            margin-top: 5px;
            border-top: 2px solid #e9ecef;
        }
        
        .amount-paid {
            color: #28a745;
            font-weight: 600;
        }
        
        .amount-due {
            color: #dc3545;
            font-weight: 600;
        }
        
        .footer {
            text-align: center;
            padding: 30px;
            border-top: 1px solid #e9ecef;
            color: #6c757d;
            font-size: 13px;
        }
        
        .footer p {
            margin: 5px 0;
        }
        
        .footer strong {
            color: #4e73df;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #4e73df;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 10px rgba(78,115,223,0.3);
            transition: all 0.3s;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #2e59d9;
            transform: translateY(-2px);
        }
        
        .status-badge {
            margin-top: 10px;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            body {
                background: white;
                padding: 0;
            }
            
            .invoice-container {
                box-shadow: none;
            }
        }
        
        @media (max-width: 768px) {
            .invoice-content {
                padding: 20px;
            }
            
            .company-details {
                flex-direction: column;
                text-align: center;
            }
            
            .invoice-info {
                text-align: center;
                margin-top: 15px;
            }
            
            .address-section {
                flex-direction: column;
                gap: 15px;
            }
            
            table {
                font-size: 12px;
            }
            
            table th, table td {
                padding: 8px;
            }
            
            .product-image {
                width: 35px;
                height: 35px;
            }
            
            .product-details h5 {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Print / Save PDF
    </button>
    
    <div class="invoice-container">
        <div class="invoice-header">
            <h1>TAX INVOICE</h1>
            <p>{{ $order->order_number }}</p>
            <div class="status-badge">
                <span class="badge badge-{{ $order->status_color }}">
                    {{ ucfirst($order->status) }}
                </span>
                <span class="badge badge-{{ $order->payment_status_color }}">
                    {{ ucfirst($order->payment_status) }}
                </span>
            </div>
        </div>
        
        <div class="invoice-content">
            <div class="company-details">
                <div class="company-info">
                    <h2>eCart Electronics</h2>
                    <p><i class="fas fa-map-marker-alt"></i> 123 Tech Park, Electronic City</p>
                    <p><i class="fas fa-city"></i> Bangalore, Karnataka - 560100</p>
                    <p><i class="fas fa-phone"></i> +91 98765 43210</p>
                    <p><i class="fas fa-envelope"></i> support@ecart.com</p>
                    <p><strong>GSTIN:</strong> 29ABCDE1234F1Z5</p>
                </div>
                
                <div class="invoice-info">
                    <h3>Invoice Details</h3>
                    <p><strong>Invoice No:</strong> INV-{{ $order->order_number }}</p>
                    <p><strong>Order No:</strong> {{ $order->order_number }}</p>
                    <p><strong>Invoice Date:</strong> {{ now()->format('d M Y') }}</p>
                    <p><strong>Order Date:</strong> {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
            
            <div class="address-section">
                <div class="address-box">
                    <h4>
                        <i class="fas fa-truck"></i> Shipping Address
                    </h4>
                    <p><strong>{{ $order->user->name }}</strong></p>
                    <p>{{ $order->shippingAddress->address_line_1 }}</p>
                    @if($order->shippingAddress->address_line_2)
                        <p>{{ $order->shippingAddress->address_line_2 }}</p>
                    @endif
                    <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} - {{ $order->shippingAddress->zip_code }}</p>
                    <p>{{ $order->shippingAddress->country }}</p>
                    <p>Phone: {{ $order->user->phone ?? 'N/A' }}</p>
                </div>
                
                <div class="address-box">
                    <h4>
                        <i class="fas fa-file-invoice"></i> Billing Address
                    </h4>
                    <p><strong>{{ $order->user->name }}</strong></p>
                    <p>{{ $order->billingAddress->address_line_1 }}</p>
                    @if($order->billingAddress->address_line_2)
                        <p>{{ $order->billingAddress->address_line_2 }}</p>
                    @endif
                    <p>{{ $order->billingAddress->city }}, {{ $order->billingAddress->state }} - {{ $order->billingAddress->zip_code }}</p>
                    <p>{{ $order->billingAddress->country }}</p>
                    <p>Email: {{ $order->user->email }}</p>
                </div>
            </div>
            
            <div class="order-summary">
                <h3>
                    <i class="fas fa-shopping-cart"></i> Order Items
                </h3>
                
                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                        <tr>
                            <td>
                                <div class="product-info">
                                    @if($item->product && $item->product->main_image)
                                        <img src="{{ asset('storage/' . $item->product->main_image) }}" 
                                             alt="{{ $item->product->name }}"
                                             class="product-image">
                                    @endif
                                    <div class="product-details">
                                        <h5>{{ $item->product->name ?? 'Product' }}</h5>
                                        <small>{{ Str::limit($item->product->description ?? '', 30) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->product->sku ?? 'N/A' }}</td>
                            <td>₹{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td><strong>₹{{ number_format($item->total, 2) }}</strong></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="price-details">
                <div class="price-row">
                    <span>Subtotal:</span>
                    <span>₹{{ number_format($order->subtotal, 2) }}</span>
                </div>
                
                <div class="price-row">
                    <span>Shipping:</span>
                    <span>
                        @if($order->shipping_cost > 0)
                            ₹{{ number_format($order->shipping_cost, 2) }}
                        @else
                            <span class="badge badge-success">Free</span>
                        @endif
                    </span>
                </div>
                
                <div class="price-row">
                    <span>Tax (GST @ 8%):</span>
                    <span>₹{{ number_format($order->tax, 2) }}</span>
                </div>
                
                @if($order->discount_amount > 0)
                <div class="price-row">
                    <span>Discount:</span>
                    <span class="text-success">- ₹{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                
                <div class="price-row total">
                    <span>Total Amount:</span>
                    <span>₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                
                <div class="price-row">
                    <span>Amount Paid:</span>
                    <span class="amount-paid">₹{{ $order->payment_status == 'completed' ? number_format($order->total_amount, 2) : '0.00' }}</span>
                </div>
                
                <div class="price-row">
                    <span>Balance Due:</span>
                    <span class="amount-due">₹{{ $order->payment_status == 'completed' ? '0.00' : number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
            
            <div style="margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                <h4 style="color: #4e73df; margin-bottom: 10px;">Payment Information</h4>
                <div class="price-row">
                    <span>Payment Method:</span>
                    <span>{{ ucfirst($order->payment_method) }}</span>
                </div>
                <div class="price-row">
                    <span>Payment Status:</span>
                    <span>
                        <span class="badge badge-{{ $order->payment_status_color }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                    </span>
                </div>
                @if($order->payment)
                <div class="price-row">
                    <span>Transaction ID:</span>
                    <span>{{ $order->payment->transaction_id ?? 'N/A' }}</span>
                </div>
                @endif
            </div>
            
            @if($order->notes)
            <div style="margin-top: 20px; padding: 15px; background: #fff3cd; border-left: 4px solid #ffc107; border-radius: 5px;">
                <h5 style="color: #856404; margin-bottom: 5px;">Order Notes</h5>
                <p style="color: #856404; margin: 0;">{{ $order->notes }}</p>
            </div>
            @endif
            
            <div class="footer">
                <p><strong>Terms & Conditions:</strong></p>
                <p>1. This is a computer generated invoice, no signature required.</p>
                <p>2. Goods once sold will not be taken back or exchanged.</p>
                <p>3. All disputes are subject to Bangalore jurisdiction.</p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #e9ecef;">
                <p>Thank you for shopping with <strong>eCart Electronics</strong>!</p>
                <p style="font-size: 11px;">For any queries, contact our support team at support@ecart.com or call +91 98765 43210</p>
            </div>
        </div>
    </div>
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>