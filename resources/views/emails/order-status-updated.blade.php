<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated</title>
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
        .content {
            background: #f9f9f9;
            padding: 30px;
            border: 1px solid #e9ecef;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .status-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            border-left: 4px solid;
        }
        .status-pending { border-color: #f6c23e; }
        .status-processing { border-color: #36b9cc; }
        .status-shipped { border-color: #4e73df; }
        .status-delivered { border-color: #1cc88a; }
        .status-cancelled { border-color: #e74a3b; }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-processing { background: #d1ecf1; color: #0c5460; }
        .badge-shipped { background: #cce5ff; color: #004085; }
        .badge-delivered { background: #d4edda; color: #155724; }
        .badge-cancelled { background: #f8d7da; color: #721c24; }
        
        .order-details {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
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
        <h1>📦 Order Status Updated</h1>
        <p>Your order #{{ $order->order_number }} status has been updated</p>
    </div>
    
    <div class="content">
        <div class="status-box status-{{ $newStatus }}">
            <h3>Status Changed</h3>
            <div style="display: flex; justify-content: center; gap: 20px; margin: 15px 0;">
                <div>
                    <small>Old Status</small>
                    <div class="status-badge badge-{{ $oldStatus }}">
                        {{ ucfirst($oldStatus) }}
                    </div>
                </div>
                <div style="font-size: 24px;">→</div>
                <div>
                    <small>New Status</small>
                    <div class="status-badge badge-{{ $newStatus }}">
                        {{ ucfirst($newStatus) }}
                    </div>
                </div>
            </div>
        </div>

        <div class="order-details">
            <h3>Order Summary</h3>
            <div class="info-row">
                <span>Order Number:</span>
                <span><strong>{{ $order->order_number }}</strong></span>
            </div>
            <div class="info-row">
                <span>Order Date:</span>
                <span>{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="info-row">
                <span>Total Amount:</span>
                <span><strong>₹{{ number_format($order->total_amount, 2) }}</strong></span>
            </div>
            <div class="info-row">
                <span>Payment Method:</span>
                <span>{{ ucfirst($order->payment_method) }}</span>
            </div>
        </div>

        @if($newStatus == 'shipped')
        <div style="background: #e8f4fd; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px; color: #004085;">🚚 Your Order is On The Way!</h4>
            <p style="margin: 0;">We'll notify you when your order is out for delivery.</p>
        </div>
        @endif

        @if($newStatus == 'delivered')
        <div style="background: #d4edda; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px; color: #155724;">✅ Order Delivered!</h4>
            <p style="margin: 0;">We hope you enjoy your purchase. Please consider leaving a review.</p>
        </div>
        @endif

        @if($newStatus == 'cancelled')
        <div style="background: #f8d7da; padding: 15px; border-radius: 8px; margin: 20px 0;">
            <h4 style="margin: 0 0 10px; color: #721c24;">❌ Order Cancelled</h4>
            <p style="margin: 0;">If you have any questions, please contact our support team.</p>
        </div>
        @endif

        <div style="text-align: center;">
            <a href="{{ route('user.orders.show', $order->id) }}" class="button">
                View Order Details
            </a>
        </div>

        <div class="footer">
            <p>Thank you for shopping with eCart Electronics!</p>
            <hr style="border: none; border-top: 1px solid #e9ecef; margin: 20px 0;">
            <p style="margin: 0;">
                <small>Need help? Contact us at support@ecart.com</small>
            </p>
        </div>
    </div>
</body>
</html>