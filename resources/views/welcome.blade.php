<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - eCart Electronics</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .verification-card {
            max-width: 500px;
            width: 100%;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            background: white;
            text-align: center;
        }
        .verification-icon {
            font-size: 60px;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="verification-card">
        <div class="verification-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="mb-3">Email Already Verified</h2>
        <p class="text-muted mb-4">
            Your email address has been automatically verified. You can now access all features.
        </p>
        <a href="{{ route('user.home') }}" class="btn btn-primary">
            Continue to Dashboard
        </a>
    </div>
    
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>