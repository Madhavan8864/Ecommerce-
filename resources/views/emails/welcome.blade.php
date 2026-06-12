<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to eCart Electronics</title>
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
            border-radius: 0 0 10px 10px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to eCart Electronics!</h1>
    </div>
    
    <div class="content">
        <p>Hello <strong>{{ $name }}</strong>,</p>
        
        <p>Thank you for joining eCart Electronics! We're excited to have you as part of our community.</p>
        
        <p>With your new account, you can:</p>
        <ul>
            <li>Browse our extensive collection of electronics</li>
            <li>Save items to your wishlist</li>
            <li>Track your orders in real-time</li>
            <li>Get exclusive offers and discounts</li>
        </ul>
        
        <div style="text-align: center;">
            <a href="{{ route('user.home') }}" class="button">Start Shopping</a>
        </div>
        
        <p>If you have any questions, feel free to contact our support team.</p>
        
        <p>Best regards,<br>The eCart Electronics Team</p>
    </div>
    
    <div class="footer">
        <p>&copy; {{ $year }} eCart Electronics. All rights reserved.</p>
        <p>This email was sent to {{ $email }}</p>
    </div>
</body>
</html>