<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracking Information Updated</title>
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
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .tracking-box {
            background: white;
            padding: 25px;
            border-radius: 10px;
            text-align: center;
            border: 2px solid #4e73df;
        }
        .tracking-number {
            font-size: 24px;
            font-weight: bold;
            color: #4e73df;
            margin: 15px 0;
            padding: 10px;
            background: #f0f5ff;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #4e73df;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #718096;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚚 Tracking Information Updated</h1>
        <p>Your order #{{ $order->order_number }} is on the way!</p>
    </div>
    
    <div class="content">
        <div class="tracking-box">
            <h3>Tracking Details</h3>
            
            <div class="tracking-number">
                {{ $order->tracking_number ?? 'N/A' }}
            </div>
            
            @if($order->shipping_carrier)
            <div class="info-row">
                <span>Carrier:</span>
                <span><strong>{{ $order->shipping_carrier }}</strong></span>
            </div>
            @endif
            
            @if($order->tracking_url)
            <div style="margin: 20px 0;">
                <a href="{{ $order->tracking_url }}" class="button" target="_blank">
                    <i class="fas fa-truck"></i> Track Your Order
                </a>
            </div>
            @endif
        </div>

        <div style="margin: 20px 0; padding: 15px; background: #e8f4fd; border-radius: 8px;">
            <h4 style="margin: 0 0 10px;">📦 Order Summary</h4>
            <div class="info-row">
                <span>Order Date:</span>
                <span>{{ $order->created_at->format('d M Y') }}</span>
            </div>
            <div class="info-row">
                <span>Total Amount:</span>
                <span>₹{{ number_format($order->total_amount, 2) }}</span>
            </div>
            <div class="info-row">
                <span>Shipping Address:</span>
                <span>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }}</span>
            </div>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('user.orders.show', $order->id) }}" class="button" style="background: #1cc88a;">
                View Order Details
            </a>
        </div>

        <div class="footer">
            <p>Thank you for shopping with eCart Electronics!</p>
        </div>
    </div>
</body>
</html>