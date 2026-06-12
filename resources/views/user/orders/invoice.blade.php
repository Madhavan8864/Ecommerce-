<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            background: #fff;
        }
        
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #0d6efd;
        }
        
        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 10px;
        }
        
        .invoice-details {
            margin-bottom: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .company-info, .customer-info {
            margin-bottom: 20px;
        }
        
        .company-info h4, .customer-info h4 {
            color: #0d6efd;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .invoice-table th {
            background: #0d6efd;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        
        .invoice-table td {
            padding: 10px;
            border-bottom: 1px solid #dee2e6;
        }
        
        .invoice-table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .summary-table {
            width: 100%;
            max-width: 400px;
            margin-left: auto;
            margin-bottom: 30px;
        }
        
        .summary-table td {
            padding: 8px 10px;
        }
        
        .summary-table .total {
            font-size: 16px;
            font-weight: bold;
            color: #0d6efd;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-pending { background: #ffc107; color: #000; }
        .status-processing { background: #0dcaf0; color: #000; }
        .status-shipped { background: #0d6efd; color: #fff; }
        .status-delivered { background: #198754; color: #fff; }
        .status-cancelled { background: #dc3545; color: #fff; }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 11px;
            color: #6c757d;
        }
        
        .terms {
            margin-top: 30px;
            font-size: 11px;
            color: #6c757d;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .invoice-container {
                max-width: 100%;
                padding: 0;
            }
            
            .status-badge {
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <h1 class="invoice-title">INVOICE</h1>
            <p>Order #{{ $order->order_number }}</p>
            <p>Date: {{ $order->created_at->format('d M Y, h:i A') }}</p>
        </div>
        
        <!-- Company & Customer Info -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 30px;">
            <div class="company-info">
                <h4>eCart Electronics</h4>
                <p>123 Electronics Street<br>
                Tech Park, Silicon Valley<br>
                CA 94043, United States<br>
                Email: support@ecart.com<br>
                Phone: +1 (800) 123-4567</p>
            </div>
            
            <div class="customer-info">
                <h4>Bill To:</h4>
                <p>{{ $order->shippingAddress->full_name ?? $order->user->name }}<br>
                {{ $order->shippingAddress->address_line_1 ?? $order->shippingAddress->address ?? '' }}<br>
                @if(!empty($order->shippingAddress->address_line_2))
                    {{ $order->shippingAddress->address_line_2 }}<br>
                @endif
                {{ $order->shippingAddress->city ?? '' }}, {{ $order->shippingAddress->state ?? '' }} {{ $order->shippingAddress->zip_code ?? '' }}<br>
                {{ $order->shippingAddress->country ?? '' }}<br>
                Email: {{ $order->shippingAddress->email ?? $order->user->email }}<br>
                Phone: {{ $order->shippingAddress->phone ?? $order->user->phone }}</p>
            </div>
        </div>
        
        <!-- Order Status -->
        <div style="margin-bottom: 20px; text-align: right;">
            <span class="status-badge status-{{ $order->status }}">
                {{ strtoupper($order->status) }}
            </span>
            <span class="status-badge status-{{ $order->payment_status }}" style="margin-left: 10px;">
                {{ strtoupper($order->payment_status) }}
            </span>
        </div>
        
        <!-- Order Items Table -->
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->sku }}</td>
                    <td>₹{{ number_format($item->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₹{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <!-- Order Summary -->
        <table class="summary-table">
            <tr>
                <td>Subtotal:</td>
                <td style="text-align: right;">₹{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Shipping:</td>
                <td style="text-align: right;">₹{{ number_format($order->shipping_cost, 2) }}</td>
            </tr>
            <tr>
                <td>Tax:</td>
                <td style="text-align: right;">₹{{ number_format($order->tax, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td style="text-align: right;">-₹{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="2"><hr style="margin: 5px 0;"></td>
            </tr>
            <tr class="total">
                <td>Total Amount:</td>
                <td style="text-align: right;">₹{{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
        
        <!-- Payment Information -->
        <div style="margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h4 style="color: #0d6efd; margin-bottom: 10px; font-size: 14px;">Payment Information</h4>
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                    <p><strong>Transaction ID:</strong> {{ $order->payment->transaction_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <p><strong>Payment Date:</strong> {{ $order->payment->paid_at ? $order->payment->paid_at->format('d M Y, h:i A') : 'Pending' }}</p>
                    <p><strong>Payment Status:</strong> <span class="status-badge status-{{ $order->payment_status }}">{{ strtoupper($order->payment_status) }}</span></p>
                </div>
            </div>
        </div>
        
        <!-- Shipping Information -->
        @if($order->tracking_number)
        <div style="margin-top: 20px; padding: 20px; background: #f8f9fa; border-radius: 5px;">
            <h4 style="color: #0d6efd; margin-bottom: 10px; font-size: 14px;">Shipping Information</h4>
            <div style="display: flex; justify-content: space-between;">
                <div>
                    <p><strong>Carrier:</strong> {{ $order->shipping_carrier ?? 'Standard Shipping' }}</p>
                    <p><strong>Tracking Number:</strong> {{ $order->tracking_number }}</p>
                </div>
                @if($order->shipped_at)
                <div>
                    <p><strong>Shipped Date:</strong> {{ $order->shipped_at->format('d M Y') }}</p>
                    @if($order->estimated_delivery_date)
                    <p><strong>Est. Delivery:</strong> {{ $order->estimated_delivery_date->format('d M Y') }}</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endif
        
        <!-- Terms & Conditions -->
        <div class="terms">
            <h4 style="color: #0d6efd; margin-bottom: 10px; font-size: 14px;">Terms & Conditions</h4>
            <p>1. This is a computer generated invoice, no signature is required.</p>
            <p>2. Goods once sold will not be taken back or exchanged.</p>
            <p>3. All disputes are subject to jurisdiction of local courts.</p>
            <p>4. This invoice is valid only for the products listed above.</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping with eCart Electronics!</p>
            <p>For any queries, please contact our support team at support@ecart.com</p>
            <p style="margin-top: 10px;">This document is digitally signed and approved.</p>
        </div>
    </div>
</body>
</html>