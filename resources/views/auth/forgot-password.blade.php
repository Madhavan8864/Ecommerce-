<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password - eCart Electronics</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        :root {
            --primary-dark: #0a1c2f;
            --primary: #1e3a5f;
            --primary-light: #2c4c6e;
            --accent: #00a8ff;
            --accent-glow: #00a8ff33;
            --text-dark: #1e293b;
            --text-gray: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: linear-gradient(135deg, #0a1c2f 0%, #0a1a2c 50%, #0a1c2f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            font-family: 'Segoe UI', 'Poppins', system-ui, -apple-system, sans-serif;
        }
        
        /* Tech Circuit Background Animation */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="none" stroke="rgba(0,168,255,0.1)" stroke-width="1" d="M10,10 L90,10 M10,20 L90,20 M10,30 L90,30 M10,40 L90,40 M10,50 L90,50 M10,60 L90,60 M10,70 L90,70 M10,80 L90,80 M10,90 L90,90 M20,10 L20,90 M30,10 L30,90 M40,10 L40,90 M50,10 L50,90 M60,10 L60,90 M70,10 L70,90 M80,10 L80,90 M90,10 L90,90"/></svg>');
            background-size: 30px 30px;
            opacity: 0.3;
            pointer-events: none;
            animation: moveGrid 20s linear infinite;
        }
        
        @keyframes moveGrid {
            0% {
                background-position: 0 0;
            }
            100% {
                background-position: 30px 30px;
            }
        }
        
        /* Floating Particles */
        body::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(0,168,255,0.05) 0%, transparent 50%);
            pointer-events: none;
        }
        
        .forgot-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 15px 35px -12px rgba(0,0,0,0.3), 0 0 0 1px rgba(0,168,255,0.1);
            overflow: hidden;
            width: 480px;
            max-width: 90%;
            position: relative;
            z-index: 1;
            animation: slideUp 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Header with Tech Theme */
        .header {
            background: linear-gradient(145deg, #0a1c2f 0%, #0f2b44 40%, #0a1c2f 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        /* Circuit Pattern Overlay */
        .header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="none" stroke="rgba(0,168,255,0.15)" stroke-width="1.5" d="M30,30 L170,30 M30,50 L170,50 M30,70 L170,70 M30,90 L170,90 M30,110 L170,110 M30,130 L170,130 M30,150 L170,150 M30,170 L170,170 M50,30 L50,170 M70,30 L70,170 M90,30 L90,170 M110,30 L110,170 M130,30 L130,170 M150,30 L150,170"/><circle cx="40" cy="40" r="3" fill="rgba(0,168,255,0.3)"/><circle cx="160" cy="40" r="3" fill="rgba(0,168,255,0.3)"/><circle cx="40" cy="160" r="3" fill="rgba(0,168,255,0.3)"/><circle cx="160" cy="160" r="3" fill="rgba(0,168,255,0.3)"/></svg>');
            background-size: 40px 40px;
            opacity: 0.4;
            pointer-events: none;
        }
        
        /* Glow Effect */
        .header::after {
            content: '';
            position: absolute;
            top: -80px;
            right: -80px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(0,168,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .header > * {
            position: relative;
            z-index: 1;
        }
        
        .header .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent), #0077cc);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            box-shadow: 0 8px 20px rgba(0,168,255,0.3);
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 8px 20px rgba(0,168,255,0.3);
            }
            50% {
                box-shadow: 0 8px 25px rgba(0,168,255,0.5);
            }
        }
        
        .header .logo-icon i {
            font-size: 32px;
            color: white;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, #fff, #00a8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .header p {
            margin: 10px 0 0;
            opacity: 0.85;
            font-size: 14px;
        }
        
        .content {
            padding: 40px 35px;
            background: white;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 13px;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
            box-sizing: border-box;
            background: #fafbff;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0,168,255,0.1);
            background: white;
            transform: translateY(-1px);
        }
        
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }
        
        .btn-submit::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-submit:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -8px rgba(0,168,255,0.4);
        }
        
        .btn-submit:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }
        
        .back-link {
            text-align: center;
            margin-top: 25px;
        }
        
        .back-link a {
            color: var(--accent);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .back-link a:hover {
            color: var(--primary);
            text-decoration: underline;
            transform: translateX(-3px);
        }
        
        .alert {
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            border-left: 3px solid;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border-left-color: #16a34a;
        }
        
        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border-left-color: #dc2626;
        }
        
        .info-text {
            font-size: 12px;
            color: var(--text-gray);
            margin-top: 20px;
            text-align: center;
            padding-top: 15px;
            border-top: 1px solid #f1f5f9;
        }
        
        .info-text i {
            color: var(--accent);
            margin-right: 6px;
        }
        
        /* Spinner Animation */
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Error styling */
        .error-message {
            color: #dc2626;
            font-size: 11px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .error-message i {
            font-size: 10px;
        }
        
        .form-group input.is-invalid {
            border-color: #dc2626;
        }
        
        /* Responsive */
        @media (max-width: 576px) {
            .content {
                padding: 30px 25px;
            }
            
            .header {
                padding: 30px 20px;
            }
            
            .header .logo-icon {
                width: 55px;
                height: 55px;
            }
            
            .header .logo-icon i {
                font-size: 26px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="header">
            <div class="logo-icon">
                <i class="fas fa-microchip"></i>
            </div>
            <h1>Forgot Password?</h1>
            <p>Enter your email to receive OTP</p>
        </div>
        
        <div class="content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    {{ session('error') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}" id="forgotForm">
                @csrf
                
                <div class="form-group">
                    <label for="email">
                        <i class="fas fa-envelope" style="color: var(--accent); margin-right: 6px;"></i>
                        Email Address
                    </label>
                    <input type="email" id="email" name="email" 
                           value="{{ old('email') }}" required 
                           placeholder="Enter your registered email"
                           class="@error('email') is-invalid @enderror">
                    @error('email')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <button type="submit" class="btn-submit" id="submitBtn">
                    <span id="btnText"><i class="fas fa-paper-plane me-2"></i> Send OTP</span>
                    <span id="btnSpinner" style="display: none;">
                        <span class="spinner"></span>
                    </span>
                </button>
                
                <div class="back-link">
                    <a href="{{ route('login') }}">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a>
                </div>
                
                <div class="info-text">
                    <i class="fas fa-shield-alt"></i>
                    We'll send a 6-digit OTP to your email. Valid for 10 minutes.
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Form submission handler
            $('#forgotForm').on('submit', function(e) {
                const email = $('#email').val();
                
                // Email validation
                if (!email) {
                    e.preventDefault();
                    toastr.error('Please enter your email address');
                    $('#email').focus();
                    return false;
                }
                
                // Email format validation
                const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    toastr.error('Please enter a valid email address');
                    $('#email').focus();
                    return false;
                }
                
                // Show loading state
                const btn = document.getElementById('submitBtn');
                const btnText = document.getElementById('btnText');
                const btnSpinner = document.getElementById('btnSpinner');
                
                btn.disabled = true;
                btnText.style.display = 'none';
                btnSpinner.style.display = 'inline-block';
                
                return true;
            });
            
            // Initialize Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "preventDuplicates": true
            };
            
            // Auto focus on email field
            if (!$('#email').val()) {
                $('#email').focus();
            }
            
            // Real-time email validation
            $('#email').on('input', function() {
                const email = $(this).val();
                const emailRegex = /^[^\s@]+@([^\s@]+\.)+[^\s@]+$/;
                
                if (email && !emailRegex.test(email)) {
                    $(this).addClass('is-invalid');
                    if ($(this).next('.error-message').length === 0) {
                        $(this).after('<div class="error-message"><i class="fas fa-exclamation-circle"></i> Please enter a valid email address</div>');
                    }
                } else {
                    $(this).removeClass('is-invalid');
                    $(this).next('.error-message').remove();
                }
            });
            
            // Show flash messages via Toastr
            @if(session('success'))
                toastr.success("{{ session('success') }}");
            @endif
            
            @if(session('error'))
                toastr.error("{{ session('error') }}");
            @endif
            
            @if($errors->has('email'))
                toastr.error("{{ $errors->first('email') }}");
            @endif
        });
    </script>
</body>
</html>