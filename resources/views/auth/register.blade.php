<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Create Account - eCart Electronics</title>
    
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
        
        .auth-container {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 15px 35px -12px rgba(0,0,0,0.3), 0 0 0 1px rgba(0,168,255,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 1000px;
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
        
        /* Left Side - Full Gradient Background */
        .auth-left {
            position: relative;
            background: linear-gradient(145deg, #0a1c2f 0%, #0f2b44 40%, #0a1c2f 100%);
            color: white;
            padding: 50px 35px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 650px;
            overflow: hidden;
            height: 100%;
        }
        
        /* Circuit Pattern Overlay */
        .auth-left::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><path fill="none" stroke="rgba(0,168,255,0.2)" stroke-width="1.5" d="M30,30 L170,30 M30,50 L170,50 M30,70 L170,70 M30,90 L170,90 M30,110 L170,110 M30,130 L170,130 M30,150 L170,150 M30,170 L170,170 M50,30 L50,170 M70,30 L70,170 M90,30 L90,170 M110,30 L110,170 M130,30 L130,170 M150,30 L150,170"/><circle cx="40" cy="40" r="3" fill="rgba(0,168,255,0.4)"/><circle cx="160" cy="40" r="3" fill="rgba(0,168,255,0.4)"/><circle cx="40" cy="160" r="3" fill="rgba(0,168,255,0.4)"/><circle cx="160" cy="160" r="3" fill="rgba(0,168,255,0.4)"/></svg>');
            background-size: 40px 40px;
            opacity: 0.5;
            pointer-events: none;
        }
        
        /* Tech Glow Effect - Top Left */
        .auth-left::after {
            content: '';
            position: absolute;
            top: -100px;
            left: -100px;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(0,168,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        
        /* Additional Glow Effect - Bottom Right */
        .auth-left .glow-bottom {
            position: absolute;
            bottom: -80px;
            right: -80px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(0,168,255,0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        
        .auth-left > * {
            position: relative;
            z-index: 1;
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .auth-logo .logo-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent), #0077cc);
            border-radius: 20px;
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
            font-size: 28px;
            font-weight: 800;
            margin-top: 5px;
            margin-bottom: 5px;
            background: linear-gradient(135deg, #fff, #00a8ff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .auth-logo p {
            opacity: 0.85;
            font-size: 13px;
            letter-spacing: 0.5px;
        }
        
        .auth-features {
            margin-top: 30px;
        }
        
        .auth-feature {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding: 12px 15px;
            background: rgba(255,255,255,0.08);
            border-radius: 14px;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(0,168,255,0.25);
            transition: all 0.3s;
        }
        
        .auth-feature:hover {
            background: rgba(0,168,255,0.15);
            border-color: rgba(0,168,255,0.5);
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
            opacity: 0.85;
            margin-bottom: 0;
        }
        
        /* Right Side Styling */
        .auth-right {
            padding: 45px 40px;
            background: white;
            min-height: 650px;
            overflow-y: auto;
            max-height: 650px;
        }
        
        /* Custom Scrollbar for Right Side */
        .auth-right::-webkit-scrollbar {
            width: 5px;
        }
        
        .auth-right::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        
        .auth-right::-webkit-scrollbar-thumb {
            background: var(--accent);
            border-radius: 10px;
        }
        
        .form-title {
            font-size: 32px;
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
        
        .auth-footer {
            text-align: center;
            margin-top: 30px;
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
        
        .form-text {
            font-size: 11px;
            margin-top: 5px;
        }
        
        /* Accordion styles - Fixed White Background */
        .accordion-item {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            margin-bottom: 0;
            background: white;
        }
        
        .accordion-button {
            background: #f8fafc;
            padding: 14px 20px;
            font-weight: 600;
            color: var(--text-dark);
            font-size: 14px;
            border: none;
        }
        
        .accordion-button:not(.collapsed) {
            background: linear-gradient(135deg, rgba(0,168,255,0.05), rgba(30,58,95,0.05));
            color: var(--accent);
            box-shadow: none;
        }
        
        .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(0,168,255,0.2);
        }
        
        .accordion-button i {
            color: var(--accent);
        }
        
        .accordion-body {
            padding: 20px;
            background: white;
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
        
        @media (max-width: 991.98px) {
            .auth-left {
                padding: 40px 30px;
                min-height: auto;
                height: auto;
            }
            
            .auth-right {
                padding: 40px 30px;
                min-height: auto;
                max-height: none;
                overflow-y: visible;
            }
            
            .form-title {
                font-size: 28px;
            }
        }
        
        @media (max-width: 767.98px) {
            .auth-container {
                max-width: 500px;
            }
            
            .auth-left {
                display: none;
            }
            
            .auth-right {
                padding: 35px 25px;
                min-height: auto;
            }
            
            .form-title {
                font-size: 26px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="row g-0">
            <!-- Left Side - Full Gradient with No White Bottom -->
            <div class="col-lg-5 d-none d-lg-block">
                <div class="auth-left">
                    <div class="glow-bottom"></div>
                    <div class="auth-logo">
                        <div class="logo-icon">
                            <i class="fas fa-microchip"></i>
                        </div>
                        <h1>eCart Electronics</h1>
                        <p>Premium Tech & Gadgets Store</p>
                    </div>
                    
                    <div class="auth-features">
                        <div class="auth-feature">
                            <i class="fas fa-gem"></i>
                            <div>
                                <h5>Exclusive Deals</h5>
                                <p>Get member-only discounts and offers</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <i class="fas fa-truck-fast"></i>
                            <div>
                                <h5>Free Shipping</h5>
                                <p>On orders above ₹999</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <i class="fas fa-wallet"></i>
                            <div>
                                <h5>Easy Returns</h5>
                                <p>30-day return policy</p>
                            </div>
                        </div>
                        
                        <div class="auth-feature">
                            <i class="fas fa-chart-line"></i>
                            <div>
                                <h5>Track Orders</h5>
                                <p>Real-time order tracking</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="text-center mb-0" style="font-size: 12px; opacity: 0.9;">
                            🚀 Join 50,000+ happy customers
                            <br>
                            <a href="{{ route('login') }}" class="text-white fw-bold text-decoration-none" style="text-decoration: underline !important; font-size: 12px;">Sign in →</a>
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Register Form -->
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
                    
                    <h2 class="form-title">Create Account</h2>
                    <p class="form-subtitle">Join our electronics store today</p>
                    
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
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" id="registerForm">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}"
                                       placeholder="Enter your full name"
                                       required
                                       autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}"
                                       placeholder="Enter your email"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}"
                                       placeholder="Enter your phone number">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="date" 
                                       class="form-control @error('date_of_birth') is-invalid @enderror" 
                                       id="date_of_birth" 
                                       name="date_of_birth" 
                                       value="{{ old('date_of_birth') }}">
                                @error('date_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Password Section -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <div class="password-input">
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Create a password"
                                           required>
                                    <button type="button" class="password-toggle" id="togglePassword">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text">Minimum 6 characters</div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password *</label>
                                <div class="password-input">
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Confirm your password"
                                           required>
                                    <button type="button" class="password-toggle" id="toggleConfirmPassword">
                                        <i class="far fa-eye"></i>
                                    </button>
                                </div>
                                <div id="passwordMatch" class="form-text"></div>
                            </div>
                        </div>
                        
                        <!-- Additional Information (Accordion) -->
                        <div class="accordion mb-4" id="additionalInfoAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingAdditional">
                                    <button class="accordion-button collapsed" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapseAdditional" 
                                            aria-expanded="false" aria-controls="collapseAdditional">
                                        <i class="fas fa-user-circle me-2"></i> Additional Information (Optional)
                                    </button>
                                </h2>
                                <div id="collapseAdditional" class="accordion-collapse collapse" 
                                     aria-labelledby="headingAdditional" data-bs-parent="#additionalInfoAccordion">
                                    <div class="accordion-body">
                                        <!-- Gender -->
                                        <div class="mb-3">
                                            <label class="form-label">Gender</label>
                                            <div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" 
                                                           name="gender" id="gender_male" 
                                                           value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gender_male">Male</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" 
                                                           name="gender" id="gender_female" 
                                                           value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gender_female">Female</label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" 
                                                           name="gender" id="gender_other" 
                                                           value="other" {{ old('gender') == 'other' ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="gender_other">Other</label>
                                                </div>
                                            </div>
                                            @error('gender')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <!-- Address -->
                                        <div class="mb-3">
                                            <label for="address" class="form-label">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="2"
                                                      placeholder="Enter your address">{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <!-- City, State, Zip Code -->
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" 
                                                       class="form-control @error('city') is-invalid @enderror" 
                                                       id="city" 
                                                       name="city" 
                                                       value="{{ old('city') }}"
                                                       placeholder="City">
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="state" class="form-label">State</label>
                                                <input type="text" 
                                                       class="form-control @error('state') is-invalid @enderror" 
                                                       id="state" 
                                                       name="state" 
                                                       value="{{ old('state') }}"
                                                       placeholder="State">
                                                @error('state')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="zip_code" class="form-label">Zip Code</label>
                                                <input type="text" 
                                                       class="form-control @error('zip_code') is-invalid @enderror" 
                                                       id="zip_code" 
                                                       name="zip_code" 
                                                       value="{{ old('zip_code') }}"
                                                       placeholder="Zip Code">
                                                @error('zip_code')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="country" class="form-label">Country</label>
                                                <input type="text" 
                                                       class="form-control @error('country') is-invalid @enderror" 
                                                       id="country" 
                                                       name="country" 
                                                       value="{{ old('country') }}"
                                                       placeholder="Country">
                                                @error('country')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        
                                        <!-- Two-Factor Authentication Preference -->
                                        <div class="mb-3">
                                            <label class="form-label">Two-Factor Authentication Preference</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="two_factor_enabled" name="two_factor_enabled" value="1"
                                                       {{ old('two_factor_enabled') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="two_factor_enabled">
                                                    Enable Two-Factor Authentication
                                                </label>
                                            </div>
                                            <div class="form-text small text-muted">
                                                Adds an extra layer of security to your account
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input @error('terms') is-invalid @enderror" 
                                       type="checkbox" 
                                       id="terms" 
                                       name="terms"
                                       required>
                                <label class="form-check-label" for="terms">
                                    I agree to the 
                                    <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#termsModal" style="color: var(--accent) !important;">
                                        terms and conditions
                                    </a>
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-user-plus me-2"></i> Create Account
                            </button>
                        </div>
                    </form>
                    
                    <div class="auth-footer">
                        <p class="mb-0">
                            Already have an account? 
                            <a href="{{ route('login') }}">Sign in here</a>
                        </p>
                        <p class="mt-2 mb-0">
                            <small>By creating an account, you agree to our Privacy Policy and Terms of Service.</small>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terms and Conditions Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--primary-dark), var(--primary)); color: white;">
                    <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>eCart Electronics Account Agreement</h6>
                    <p>Welcome to eCart Electronics. By creating an account, you agree to the following terms:</p>
                    
                    <h6>1. Account Security</h6>
                    <p>You are responsible for maintaining the confidentiality of your password and account information.</p>
                    
                    <h6>2. Privacy</h6>
                    <p>Your personal information will be handled in accordance with our Privacy Policy.</p>
                    
                    <h6>3. Communications</h6>
                    <p>You agree to receive transactional and promotional emails from eCart Electronics.</p>
                    
                    <h6>4. Account Management</h6>
                    <p>You can update your account information at any time through your profile settings.</p>
                    
                    <h6>5. Two-Factor Authentication</h6>
                    <p>Enabling two-factor authentication provides additional security for your account.</p>
                    
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="agreeInModal">
                        <label class="form-check-label" for="agreeInModal">
                            I have read and agree to the terms and conditions
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="acceptTerms" data-bs-dismiss="modal">Accept</button>
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
            $('#togglePassword, #toggleConfirmPassword').click(function() {
                const inputId = $(this).attr('id') === 'togglePassword' ? '#password' : '#password_confirmation';
                const passwordInput = $(inputId);
                const icon = $(this).find('i');
                
                if (passwordInput.attr('type') === 'password') {
                    passwordInput.attr('type', 'text');
                    icon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordInput.attr('type', 'password');
                    icon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Password confirmation check
            $('#password_confirmation').on('input', function() {
                const password = $('#password').val();
                const confirmPassword = $(this).val();
                
                if (confirmPassword) {
                    if (password === confirmPassword) {
                        $('#passwordMatch').html('<span class="text-success"><i class="fas fa-check-circle me-1"></i>Passwords match!</span>');
                        $(this).removeClass('is-invalid').addClass('is-valid');
                    } else {
                        $('#passwordMatch').html('<span class="text-danger"><i class="fas fa-times-circle me-1"></i>Passwords do not match</span>');
                        $(this).removeClass('is-valid').addClass('is-invalid');
                    }
                } else {
                    $('#passwordMatch').html('');
                    $(this).removeClass('is-valid is-invalid');
                }
            });
            
            // Terms modal functionality
            $('#acceptTerms').click(function() {
                $('#terms').prop('checked', true);
                $('#agreeInModal').prop('checked', true);
            });
            
            $('#agreeInModal').change(function() {
                if ($(this).is(':checked')) {
                    $('#terms').prop('checked', true);
                }
            });
            
            // Calculate age from date of birth
            $('#date_of_birth').change(function() {
                const dob = new Date($(this).val());
                const today = new Date();
                let age = today.getFullYear() - dob.getFullYear();
                const monthDiff = today.getMonth() - dob.getMonth();
                
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < dob.getDate())) {
                    age--;
                }
                
                if (age < 13 && $(this).val()) {
                    toastr.warning('You must be at least 13 years old to register');
                }
            });
            
            // Form submission validation
            $('#registerForm').submit(function(e) {
                const password = $('#password').val();
                const confirmPassword = $('#password_confirmation').val();
                const terms = $('#terms').is(':checked');
                
                // Check password match
                if (password !== confirmPassword) {
                    e.preventDefault();
                    toastr.error('Passwords do not match!');
                    $('#password_confirmation').focus();
                    return false;
                }
                
                // Check terms acceptance
                if (!terms) {
                    e.preventDefault();
                    toastr.error('You must accept the terms and conditions');
                    $('#terms').focus();
                    return false;
                }
                
                // Check password length
                if (password.length < 6) {
                    e.preventDefault();
                    toastr.error('Password must be at least 6 characters long');
                    $('#password').focus();
                    return false;
                }
                
                // Age validation
                const dob = $('#date_of_birth').val();
                if (dob) {
                    const birthDate = new Date(dob);
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();
                    
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    
                    if (age < 13) {
                        e.preventDefault();
                        toastr.error('You must be at least 13 years old to register');
                        return false;
                    }
                }
                
                // Disable submit button to prevent double submission
                $('#submitBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i> Creating Account...');
                
                return true;
            });
            
            // Initialize Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };
            
            // Auto focus on name field
            if (!$('#name').val()) {
                $('#name').focus();
            }
            
            // Phone number formatting
            $('#phone').on('input', function() {
                let phone = $(this).val().replace(/\D/g, '');
                if (phone.length > 10) {
                    phone = phone.substring(0, 10);
                }
                $(this).val(phone);
            });
            
            // Zip code validation
            $('#zip_code').on('input', function() {
                let zip = $(this).val().replace(/\D/g, '');
                if (zip.length > 5) {
                    zip = zip.substring(0, 5);
                }
                $(this).val(zip);
            });
        });
    </script>
</body>
</html>