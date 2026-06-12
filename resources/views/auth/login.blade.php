<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login - eCart Electronics</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Custom Auth CSS -->
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
            --gradient-start: #1e3a5f;
            --gradient-end: #0f2b44;
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
        
        .auth-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 15px 35px -12px rgba(0,0,0,0.3), 0 0 0 1px rgba(0,168,255,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 950px;
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
        
        /* Left Side - Tech Themed */
        .auth-left {
            position: relative;
            background: linear-gradient(135deg, #0a1c2f 0%, #0f2b44 50%, #0a1c2f 100%);
            color: white;
            padding: 45px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 550px;
            overflow: hidden;
        }
        
        /* Circuit Pattern Overlay */
        .auth-left::before {
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
        
        /* Tech Glow Effect */
        .auth-left::after {
            content: '';
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(0,168,255,0.2) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        .auth-left > * {
            position: relative;
            z-index: 1;
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .auth-logo .logo-icon {
            width: 65px;
            height: 65px;
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
        
        .auth-logo .logo-icon i {
            font-size: 32px;
            color: white;
        }
        
        .auth-logo h1 {
            font-size: 26px;
            font-weight: 800;
            margin-top: 5px;
            margin-bottom: 5px;
            background: linear-gradient(135deg, #fff, #00a8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .auth-logo p {
            opacity: 0.8;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
        
        .auth-features {
            margin-top: 35px;
        }
        
        .auth-feature {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 12px 15px;
            background: rgba(255,255,255,0.03);
            border-radius: 14px;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0,168,255,0.2);
            transition: all 0.3s;
        }
        
        .auth-feature:hover {
            background: rgba(0,168,255,0.1);
            border-color: rgba(0,168,255,0.4);
            transform: translateX(5px);
        }
        
        .auth-feature i {
            font-size: 24px;
            margin-right: 15px;
            color: var(--accent);
        }
        
        .auth-feature h5 {
            font-size: 14px;
            margin-bottom: 3px;
            font-weight: 700;
        }
        
        .auth-feature p {
            font-size: 11px;
            opacity: 0.8;
            margin-bottom: 0;
        }
        
        /* Right Side Styling */
        .auth-right {
            padding: 45px 40px;
            background: white;
        }
        
        .form-title {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 10px;
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .form-subtitle {
            color: var(--text-gray);
            margin-bottom: 30px;
            font-size: 14px;
        }
        
        .form-control {
            padding: 12px 16px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            font-size: 14px;
            transition: all 0.3s;
            background: #fafbff;
        }
        
        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(0,168,255,0.1);
            background: white;
            transform: translateY(-1px);
        }
        
        .form-label {
            font-weight: 600;
            margin-bottom: 8px;
            color: var(--text-dark);
            font-size: 13px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border: none;
            padding: 14px 28px;
            font-weight: 700;
            font-size: 15px;
            border-radius: 12px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            letter-spacing: 0.5px;
        }
        
        .btn-primary::before {
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
        
        .btn-primary:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -8px rgba(0,168,255,0.4);
        }
        
        .btn-primary:active {
            transform: translateY(0);
        }
        
        .auth-divider {
            text-align: center;
            margin: 28px 0;
            position: relative;
        }
        
        .auth-divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }
        
        .auth-divider span {
            background: white;
            padding: 0 18px;
            color: var(--text-gray);
            font-size: 12px;
            position: relative;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .social-login {
            display: flex;
            gap: 12px;
            margin-bottom: 28px;
        }
        
        .social-btn {
            flex: 1;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            text-decoration: none;
            cursor: pointer;
        }
        
        .social-btn.google {
            background: white;
            border: 1px solid #e2e8f0;
            color: var(--text-dark);
        }
        
        .social-btn.google:hover {
            background: #f8fafc;
            border-color: var(--accent);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        
        .social-btn.facebook {
            background: linear-gradient(135deg, #1877f2, #0c63e4);
            border: none;
            color: white;
            box-shadow: 0 2px 8px rgba(24,119,242,0.3);
        }
        
        .social-btn.facebook:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(24,119,242,0.4);
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
        }
        
        .auth-footer p {
            color: var(--text-gray);
            margin-bottom: 0;
            font-size: 13px;
        }
        
        .auth-footer a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s;
        }
        
        .auth-footer a:hover {
            color: var(--primary);
            text-decoration: underline;
        }
        
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-gray);
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }
        
        .password-toggle:hover {
            color: var(--accent);
        }
        
        .password-input {
            position: relative;
        }
        
        .alert {
            border-radius: 12px;
            border: none;
            padding: 12px 18px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        
        .alert-danger {
            background: #fee2e2;
            color: #dc2626;
            border-left: 3px solid #dc2626;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border-left: 3px solid #16a34a;
        }
        
        .form-check-input:checked {
            background-color: var(--accent);
            border-color: var(--accent);
        }
        
        .form-check-input:focus {
            box-shadow: 0 0 0 2px rgba(0,168,255,0.25);
        }
        
        .form-check-label {
            font-size: 13px;
            color: var(--text-gray);
        }
        
        .forgot-link {
            color: var(--accent);
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
        }
        
        .forgot-link:hover {
            text-decoration: underline;
        }
        
        /* Mobile Logo Styling */
        .mobile-logo {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .mobile-logo .logo-icon {
            width: 55px;
            height: 55px;
            background: linear-gradient(135deg, var(--accent), #0077cc);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
        }
        
        .mobile-logo .logo-icon i {
            font-size: 26px;
            color: white;
        }
        
        .mobile-logo h1 {
            font-size: 22px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary-dark), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 3px;
        }
        
        .mobile-logo p {
            font-size: 11px;
            color: var(--text-gray);
        }
        
        @media (max-width: 991.98px) {
            .auth-left {
                padding: 35px 25px;
                min-height: auto;
            }
            
            .auth-right {
                padding: 35px 30px;
            }
            
            .form-title {
                font-size: 28px;
            }
            
            .auth-feature {
                padding: 10px 12px;
            }
            
            .auth-feature i {
                font-size: 20px;
                margin-right: 12px;
            }
            
            .auth-feature h5 {
                font-size: 13px;
            }
            
            .auth-feature p {
                font-size: 10px;
            }
        }
        
        @media (max-width: 767.98px) {
            .auth-container {
                max-width: 450px;
            }
            
            .auth-left {
                display: none;
            }
            
            .auth-right {
                padding: 35px 25px;
            }
            
            .form-title {
                font-size: 26px;
            }
            
            .social-btn {
                padding: 10px;
                font-size: 12px;
            }
        }
        
        /* Floating Animation for Features */
        @keyframes floatFeature {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-3px);
            }
        }
        
        .auth-feature {
            animation: floatFeature 3s ease-in-out infinite;
        }
        
        .auth-feature:nth-child(1) { animation-delay: 0s; }
        .auth-feature:nth-child(2) { animation-delay: 0.3s; }
        .auth-feature:nth-child(3) { animation-delay: 0.6s; }
        .auth-feature:nth-child(4) { animation-delay: 0.9s; }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="row g-0">
            <!-- Left Side - Tech Themed -->
            <div class="col-lg-5 d-none d-lg-block">
                <div class="auth-left">
                    <div class="auth-logo">
                        <div class="logo-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h1>eCart Electronics</h1>
                        <p>Premium Tech & Gadgets Store</p>
                    </div>
                    
                    <div class="auth-features">
                        <div class="auth-feature">
                            <i class="fas fa-microchip"></i>
                            <div>
                                <h5>Latest Tech Products</h5>
                                <p>Shop the newest electronics and gadgets</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <i class="fas fa-bolt"></i>
                            <div>
                                <h5>Express Delivery</h5>
                                <p>Same day shipping on all orders</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <i class="fas fa-shield-alt"></i>
                            <div>
                                <h5>2 Year Warranty</h5>
                                <p>Extended warranty on all products</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <i class="fas fa-headset"></i>
                            <div>
                                <h5>24/7 Tech Support</h5>
                                <p>Expert assistance anytime you need</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-center mb-0" style="font-size: 12px;">
                            🚀 Join 50,000+ happy customers
                            <br>
                            <a href="{{ route('register') }}" class="text-white fw-bold text-decoration-none" style="text-decoration: underline !important; font-size: 12px;">Create an account →</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="col-lg-7">
                <div class="auth-right">
                    <!-- Mobile Logo -->
                    <div class="mobile-logo d-block d-lg-none mb-3">
                        <div class="logo-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h1>eCart Electronics</h1>
                        <p>Premium Tech Store</p>
                    </div>
                    
                    <h2 class="form-title">Welcome Back</h2>
                    <p class="form-subtitle">Sign in to access your tech hub</p>
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Social Login -->
                    <div class="social-login">
                        <a href="{{ route('login.social', 'google') }}" class="social-btn google">
                            <i class="fab fa-google"></i>
                            Google
                        </a>
                        <a href="{{ route('login.social', 'facebook') }}" class="social-btn facebook">
                            <i class="fab fa-facebook-f"></i>
                            Facebook
                        </a>
                    </div>
                    
                    <div class="auth-divider">
                        <span>Or continue with email</span>
                    </div>
                    
                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="Enter your email"
                                   required
                                   autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="password-input">
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Enter your password"
                                       required>
                                <button type="button" class="password-toggle" id="togglePassword">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="text-end mt-1">
                                <a href="{{ route('password.request') }}" class="forgot-link">
                                    Forgot password?
                                </a>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       id="remember" 
                                       name="remember"
                                       {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Keep me signed in
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right-to-bracket me-2"></i> Sign In
                            </button>
                        </div>
                    </form>
                    
                    <div class="auth-footer">
                        <p class="mb-0">
                            New to eCart Electronics? 
                            <a href="{{ route('register') }}">Create an account</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordInput = $('#password');
                const icon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Initialize Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000",
                "preventDuplicates": true
            };
            
            // Show any flash messages
            @if(session('status'))
                toastr.success("{{ session('status') }}");
            @endif
            
            @if(session('verified'))
                toastr.success("{{ session('verified') }}");
            @endif
            
            // Auto focus on email field if empty
            if (!$('#email').val()) {
                $('#email').focus();
            }
            
            // Add input animation
            $('.form-control').on('focus', function() {
                $(this).parent().find('.form-label').css('color', '#00a8ff');
            }).on('blur', function() {
                $(this).parent().find('.form-label').css('color', '#1e293b');
            });
        });
    </script>
</body>
</html>