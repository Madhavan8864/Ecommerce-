<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-top: none;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
        .user-info {
            background: #e9ecef;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .message-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #667eea;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>New Support Request</h2>
    </div>
    
    <div class="content">
        <div class="user-info">
            <h4>User Information:</h4>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>User ID:</strong> {{ $user->id }}</p>
            <p><strong>Date:</strong> {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>
        
        <h4>Subject: {{ $subject }}</h4>
        
        <div class="message-box">
            <h5>Message:</h5>
            <p>{{ nl2br($message) }}</p>
        </div>
    </div>
    
    <div class="footer">
        <p>&copy; {{ date('Y') }} eCart Electronics. All rights reserved.</p>
        <p>This is an automated message from your support system.</p>
    </div>
</body>
</html>