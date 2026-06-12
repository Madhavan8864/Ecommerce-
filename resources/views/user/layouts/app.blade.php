<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'eCart Electronics - Your One-Stop Electronics Store')</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <style>
        /* ============================================
           ELECTRONICS STORE - PREMIUM MODERN DESIGN
           Single Row Header - Perfect Alignment
           REDUCED FOOTER SIZE - NORMAL REALISTIC
           ============================================ */
        
        :root {
            /* Premium Electronics Color Palette */
            --primary: #2563eb;
            --primary-dark: #1e40af;
            --primary-light: #60a5fa;
            --primary-soft: #dbeafe;
            
            --secondary: #0ea5e9;
            --secondary-dark: #0284c7;
            --secondary-light: #7dd3fc;
            
            --accent: #f97316;
            --accent-dark: #ea580c;
            --accent-light: #fb923c;
            
            --success: #10b981;
            --success-dark: #059669;
            --success-light: #6ee7b7;
            
            --warning: #f59e0b;
            --warning-dark: #d97706;
            --warning-light: #fcd34d;
            
            --danger: #ef4444;
            --danger-dark: #dc2626;
            --danger-light: #f87171;
            
            --dark: #0f172a;
            --dark-soft: #1e293b;
            --gray-dark: #334155;
            --gray: #64748b;
            --gray-light: #94a3b8;
            --gray-soft: #e2e8f0;
            --light: #f8fafc;
            --white: #ffffff;
            
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
            
            --radius-xs: 6px;
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            --radius-full: 9999px;
            
            --font-display: 'Space Grotesk', sans-serif;
            --font-body: 'Inter', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: var(--font-body);
            color: var(--dark);
            background-color: var(--light);
            line-height: 1.6;
            overflow-x: hidden;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: var(--font-display);
            font-weight: 700;
            color: var(--dark);
            letter-spacing: -0.02em;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.5); }
            50% { transform: scale(1.05); box-shadow: 0 0 0 20px rgba(37, 99, 235, 0); }
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ============================================
           HEADER - PERFECT SINGLE ROW ALIGNMENT
           ============================================ */
        .main-header {
            background: var(--white);
            border-bottom: 1px solid var(--gray-soft);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
            box-shadow: var(--shadow-sm);
        }

        .header-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
            max-width: 1440px;
            margin: 0 auto;
            padding: 0 30px;
        }

        /* Logo - Premium */
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            min-width: 140px;
        }

        .logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            box-shadow: var(--shadow-md);
            transform: rotate(-5deg);
            transition: transform 0.3s;
        }

        .logo:hover .logo-icon {
            transform: rotate(0deg);
        }

        .logo-text {
            font-size: 24px;
            font-weight: 700;
            font-family: var(--font-display);
            color: var(--dark);
            letter-spacing: -0.5px;
        }

        .logo-text span {
            color: var(--primary);
        }

        /* Search Bar - Perfect Height */
        .search-box {
            flex: 1;
            max-width: 550px;
            min-width: 300px;
            position: relative;
        }

        .search-box input {
            width: 100%;
            padding: 12px 50px 12px 20px;
            border: 1.5px solid var(--gray-soft);
            border-radius: var(--radius-full);
            font-size: 15px;
            transition: all 0.25s;
            height: 48px;
            background: var(--light);
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary);
            background: var(--white);
            box-shadow: 0 0 0 4px var(--primary-soft);
        }

        .search-box button {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            background: var(--primary);
            color: white;
            border: none;
            width: 36px;
            height: 36px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.25s;
            font-size: 15px;
        }

        .search-box button:hover {
            background: var(--primary-dark);
            transform: translateY(-50%) scale(1.05);
        }

        /* Navigation - Perfect Scrollable Row */
        .main-nav {
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: nowrap;
            overflow-x: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            padding: 0 4px;
        }

        .main-nav::-webkit-scrollbar {
            display: none;
        }

        .nav-item {
            list-style: none;
            flex-shrink: 0;
        }

        .nav-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--gray-dark);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: var(--radius-full);
            transition: all 0.25s;
            min-width: 80px;
            gap: 4px;
            position: relative;
        }

        .nav-link i {
            font-size: 20px;
            margin-bottom: 2px;
            color: var(--gray);
            transition: all 0.25s;
        }

        .nav-link span {
            font-size: 11px;
            text-align: center;
            line-height: 1.2;
            font-weight: 600;
            white-space: nowrap;
        }

        .nav-link .badge {
            position: absolute;
            top: 4px;
            right: 10px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            font-weight: 700;
            width: 18px;
            height: 18px;
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
        }

        .nav-link:hover {
            background: var(--primary-soft);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .nav-link:hover i {
            color: var(--primary);
        }

        .nav-link.active {
            background: var(--primary);
            color: white;
        }

        .nav-link.active i {
            color: white;
        }

        .nav-link.active .badge {
            background: white;
            color: var(--danger);
            border-color: var(--primary);
        }

        /* Header Actions */
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 180px;
            justify-content: flex-end;
        }

        .action-item {
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: var(--gray-dark);
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            padding: 8px 12px;
            border-radius: var(--radius-full);
            transition: all 0.25s;
            min-width: 70px;
            gap: 4px;
        }

        .action-item i {
            font-size: 20px;
            margin-bottom: 2px;
            color: var(--gray);
            transition: all 0.25s;
        }

        .action-item span {
            font-size: 11px;
            text-align: center;
            line-height: 1.2;
            font-weight: 600;
            white-space: nowrap;
        }

        .action-item:hover {
            background: var(--primary-soft);
            color: var(--primary);
            transform: translateY(-2px);
        }

        .action-item:hover i {
            color: var(--primary);
        }

        /* User Dropdown - Compact */
        .user-dropdown {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: var(--radius-full);
            transition: all 0.25s;
            background: var(--light);
            border: 1px solid var(--gray-soft);
        }

        .user-dropdown:hover {
            background: var(--primary-soft);
            border-color: var(--primary-light);
        }

        .user-avatar-sm {
            width: 32px;
            height: 32px;
            border-radius: var(--radius-full);
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--dark);
            max-width: 100px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Mobile Toggle */
        .mobile-toggle {
            display: none;
            background: var(--light);
            border: 1px solid var(--gray-soft);
            color: var(--gray-dark);
            font-size: 18px;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: var(--radius-full);
            transition: all 0.25s;
            align-items: center;
            justify-content: center;
            min-width: 44px;
        }

        .mobile-toggle:hover {
            background: var(--primary-soft);
            color: var(--primary);
            border-color: var(--primary-light);
        }

        /* ============================================
           DROPDOWN MENU
           ============================================ */
        .dropdown-menu {
            border: none;
            box-shadow: var(--shadow-xl);
            border-radius: var(--radius-md);
            padding: 10px 0;
            min-width: 240px;
            border: 1px solid var(--gray-soft);
            margin-top: 10px !important;
        }

        .dropdown-header {
            padding: 12px 20px;
            background: var(--light);
            border-bottom: 1px solid var(--gray-soft);
        }

        .dropdown-item {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            color: var(--dark);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .dropdown-item i {
            width: 20px;
            font-size: 16px;
            color: var(--gray);
        }

        .dropdown-item:hover {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .dropdown-item:hover i {
            color: var(--primary);
        }

        .dropdown-item.text-danger:hover {
            background: #fee2e2;
            color: var(--danger) !important;
        }

        .dropdown-item.text-danger:hover i {
            color: var(--danger);
        }

        .dropdown-divider {
            border-top: 1px solid var(--gray-soft);
        }

        /* ============================================
           HERO BANNER - ELECTRONICS STYLE
           ============================================ */
        .hero-section {
            background: linear-gradient(135deg, var(--dark), var(--dark-soft));
            position: relative;
            overflow: hidden;
            padding: 60px 0;
            margin-bottom: 50px;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="50" cy="50" r="40" fill="none" stroke="rgba(37,99,235,0.1)" stroke-width="2"/><circle cx="50" cy="50" r="30" fill="none" stroke="rgba(14,165,233,0.1)" stroke-width="2"/></svg>');
            background-size: 60px 60px;
            opacity: 0.5;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-badge {
            display: inline-block;
            padding: 6px 14px;
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: var(--radius-full);
            color: white;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 16px;
            backdrop-filter: blur(10px);
        }

        .hero-title {
            font-size: 44px;
            font-weight: 800;
            color: white;
            margin-bottom: 16px;
            line-height: 1.1;
            letter-spacing: -0.02em;
        }

        .hero-title span {
            color: var(--primary-light);
            position: relative;
        }

        .hero-description {
            font-size: 16px;
            color: rgba(255,255,255,0.8);
            margin-bottom: 25px;
            max-width: 600px;
        }

        .hero-stats {
            display: flex;
            gap: 30px;
            margin-top: 30px;
        }

        .hero-stat-number {
            font-size: 24px;
            font-weight: 800;
            color: white;
            font-family: var(--font-display);
        }

        .hero-stat-label {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
        }

        /* ============================================
           SECTION TITLES - REDUCED SIZE
           ============================================ */
        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 40px;
        }

        .section-subtitle {
            display: inline-block;
            padding: 5px 14px;
            background: var(--primary-soft);
            color: var(--primary);
            border-radius: var(--radius-full);
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .section-title {
            font-size: 30px;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 12px;
        }

        .section-title span {
            color: var(--primary);
        }

        .section-description {
            font-size: 15px;
            color: var(--gray);
        }

        /* ============================================
           PRODUCT CARDS - NORMAL SIZE
           ============================================ */
        .product-card {
            background: var(--white);
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            border: 1px solid var(--gray-soft);
            display: flex;
            flex-direction: column;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .product-badge-container {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .product-badge {
            padding: 4px 12px;
            border-radius: var(--radius-full);
            font-size: 11px;
            font-weight: 700;
            color: white;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: var(--shadow);
        }

        .badge-new { background: var(--success); }
        .badge-hot { background: var(--danger); }
        .badge-sale { background: var(--accent); }

        .wishlist-btn {
            position: absolute;
            top: 12px;
            right: 12px;
            width: 36px;
            height: 36px;
            background: var(--white);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray);
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: var(--shadow);
            border: 1px solid var(--gray-soft);
            z-index: 2;
        }

        .wishlist-btn:hover {
            background: var(--danger);
            color: white;
            transform: scale(1.1);
            border-color: var(--danger);
        }

        .product-img {
            position: relative;
            padding-top: 75%;
            background: linear-gradient(135deg, var(--gray-soft), var(--light));
            overflow: hidden;
        }

        .product-img img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            padding: 15px;
            transition: transform 0.5s ease;
        }

        .product-card:hover .product-img img {
            transform: scale(1.05);
        }

        .product-info {
            padding: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-category {
            font-size: 11px;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .product-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.4;
        }

        .product-title a {
            color: var(--dark);
            text-decoration: none;
            transition: color 0.3s;
        }

        .product-title a:hover {
            color: var(--primary);
        }

        .product-rating {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
        }

        .stars {
            display: flex;
            gap: 2px;
        }

        .stars i {
            color: var(--warning);
            font-size: 12px;
        }

        .rating-count {
            font-size: 11px;
            color: var(--gray);
        }

        .product-price {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin-bottom: 14px;
        }

        .current-price {
            font-size: 20px;
            font-weight: 800;
            color: var(--dark);
            font-family: var(--font-display);
        }

        .original-price {
            font-size: 14px;
            color: var(--gray);
            text-decoration: line-through;
            font-weight: 500;
        }

        .product-actions {
            display: flex;
            gap: 8px;
            margin-top: auto;
        }

        .btn-add-cart {
            flex: 1;
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 14px;
            border-radius: var(--radius-full);
            font-weight: 600;
            transition: all 0.3s;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            font-size: 13px;
        }

        .btn-add-cart:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-quick-view {
            width: 42px;
            height: 42px;
            background: var(--light);
            color: var(--gray);
            border: 1px solid var(--gray-soft);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            font-size: 16px;
        }

        .btn-quick-view:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: scale(1.05);
        }

        /* ============================================
           CATEGORY CARDS - NORMAL SIZE
           ============================================ */
        .category-card {
            background: var(--white);
            border: 1px solid var(--gray-soft);
            border-radius: var(--radius-lg);
            padding: 25px 15px;
            text-align: center;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
        }

        .category-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
        }

        .category-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary-soft), var(--secondary-light));
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 28px;
            color: var(--primary);
            transition: all 0.3s;
        }

        .category-card:hover .category-icon {
            background: var(--primary);
            color: white;
        }

        .category-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .category-count {
            font-size: 13px;
            color: var(--gray);
        }

        /* ============================================
           FEATURES SECTION - NORMAL SIZE
           ============================================ */
        .feature-card {
            background: var(--white);
            border: 1px solid var(--gray-soft);
            border-radius: var(--radius-lg);
            padding: 25px 20px;
            text-align: center;
            transition: all 0.3s;
            height: 100%;
        }

        .feature-card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-lg);
            transform: translateY(-5px);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--primary-soft);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 24px;
            color: var(--primary);
        }

        .feature-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .feature-description {
            font-size: 13px;
            color: var(--gray);
            line-height: 1.6;
        }

        /* ============================================
           FOOTER - REDUCED SIZE (NORMAL, REALISTIC)
           ============================================ */
        .footer {
            background: var(--dark);
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
            position: relative;
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
        }

        .footer-widget {
            margin-bottom: 30px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            margin-bottom: 15px;
        }

        .footer-logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .footer-logo-text {
            font-size: 20px;
            font-weight: 700;
            font-family: var(--font-display);
            color: white;
        }

        .footer-logo-text span {
            color: var(--primary-light);
        }

        .footer-text {
            color: var(--gray-light);
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .social-links {
            display: flex;
            gap: 10px;
        }

        .social-link {
            width: 34px;
            height: 34px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: var(--radius-full);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 14px;
        }

        .social-link:hover {
            background: var(--primary);
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .footer-title {
            font-size: 16px;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 8px;
        }

        .footer-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 35px;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: var(--gray-light);
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .footer-links a i {
            font-size: 11px;
            color: var(--gray);
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(4px);
        }

        .footer-links a:hover i {
            color: var(--primary);
        }

        .contact-info {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .contact-info li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
            color: var(--gray-light);
            font-size: 14px;
        }

        .contact-info li i {
            color: var(--primary);
            font-size: 15px;
            margin-top: 3px;
        }

        .newsletter-form {
            margin-top: 15px;
        }

        .newsletter-form .input-group {
            border-radius: var(--radius-full);
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.1);
            background: rgba(255,255,255,0.05);
        }

        .newsletter-form input {
            background: transparent;
            border: none;
            padding: 10px 16px;
            color: white;
            font-size: 13px;
            height: 42px;
        }

        .newsletter-form input::placeholder {
            color: var(--gray-light);
            font-size: 13px;
        }

        .newsletter-form input:focus {
            background: rgba(255,255,255,0.1);
            box-shadow: none;
            outline: none;
        }

        .newsletter-form button {
            background: var(--primary);
            border: none;
            padding: 0 20px;
            color: white;
            transition: all 0.3s;
            font-size: 14px;
            font-weight: 500;
        }

        .newsletter-form button:hover {
            background: var(--primary-dark);
        }

        .footer-bottom {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .copyright {
            color: var(--gray-light);
            font-size: 13px;
            margin: 0;
        }

        .footer-bottom-links {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
        }

        .footer-bottom-links a {
            color: var(--gray-light);
            text-decoration: none;
            font-size: 13px;
            transition: color 0.3s;
        }

        .footer-bottom-links a:hover {
            color: white;
        }

        /* ============================================
           QUICK VIEW MODAL
           ============================================ */
        .modal-content {
            border: none;
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--gray-soft);
            padding: 16px 20px;
        }

        .modal-title {
            font-weight: 700;
            color: var(--dark);
            font-size: 18px;
        }

        .modal-body {
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid var(--gray-soft);
            padding: 16px 20px;
        }

        .btn-close {
            transition: all 0.3s;
        }

        .btn-close:hover {
            transform: rotate(90deg);
        }

        /* ============================================
           RESPONSIVE DESIGN - FOOTER ADJUSTED
           ============================================ */
        @media (max-width: 1280px) {
            .header-container {
                gap: 20px;
                padding: 0 20px;
            }
            
            .search-box {
                max-width: 400px;
                min-width: 250px;
            }
            
            .main-nav {
                gap: 2px;
            }
            
            .nav-link {
                min-width: 70px;
                padding: 8px 12px;
            }
        }

        @media (max-width: 1024px) {
            .header-container {
                flex-wrap: wrap;
                gap: 15px;
                padding: 10px 20px;
            }
            
            .logo {
                order: 1;
            }
            
            .mobile-toggle {
                display: flex;
                order: 2;
            }
            
            .search-box {
                order: 4;
                min-width: 100%;
                max-width: 100%;
            }
            
            .main-nav {
                display: none;
                order: 5;
                width: 100%;
                padding: 15px 0;
                flex-wrap: nowrap;
                overflow-x: auto;
            }
            
            .main-nav.active {
                display: flex;
            }
            
            .header-actions {
                order: 3;
                min-width: auto;
                margin-left: auto;
            }
            
            .hero-title {
                font-size: 36px;
            }
            
            .section-title {
                font-size: 26px;
            }
        }

        @media (max-width: 768px) {
            .header-container {
                padding: 10px 15px;
            }
            
            .user-name {
                display: none;
            }
            
            .user-dropdown {
                padding: 6px;
            }
            
            .hero-title {
                font-size: 30px;
            }
            
            .hero-stats {
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .section-title {
                font-size: 24px;
            }
            
            .footer {
                padding: 30px 0 15px;
                margin-top: 40px;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
            
            .footer-bottom-links {
                justify-content: center;
            }
        }

        @media (max-width: 576px) {
            .logo-text {
                font-size: 20px;
            }
            
            .logo-icon {
                width: 38px;
                height: 38px;
                font-size: 20px;
            }
            
            .action-item span {
                display: none;
            }
            
            .action-item {
                min-width: auto;
                padding: 10px;
            }
            
            .action-item i {
                margin-bottom: 0;
                font-size: 20px;
            }
            
            .header-actions {
                gap: 5px;
            }
            
            .hero-title {
                font-size: 26px;
            }
            
            .hero-description {
                font-size: 14px;
            }
            
            .hero-badge {
                font-size: 11px;
            }
            
            .section-title {
                font-size: 22px;
            }
            
            .product-card {
                margin-bottom: 15px;
            }
            
            .footer {
                padding: 25px 0 15px;
            }
            
            .footer-widget {
                margin-bottom: 25px;
            }
        }

        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .bg-gradient-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
        }

        .text-gradient {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid var(--gray-soft);
            border-top-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: var(--light);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--gray);
            border-radius: var(--radius-full);
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }
    </style>
    
    @stack('styles')
</head>
<body>

    <!-- Header - PERFECT SINGLE ROW ALIGNMENT -->
    <header class="main-header">
        <div class="header-container">
            <!-- Logo -->
            <a href="{{ route('user.home') }}" class="logo">
                <div class="logo-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <span class="logo-text"><span>e</span>Cart</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="mobile-toggle" id="mobileToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Navigation - Scrollable -->
            <nav class="main-nav" id="mainNav">
                <li class="nav-item">
                    <a class="nav-link active" href="{{ route('user.home') }}">
                        <i class="fas fa-home"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.products.index') }}">
                        <i class="fas fa-microchip"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.cart.index') }}">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Cart</span>
                        
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.support.index')}}">
                        <i class="fas fa-headset"></i>
                        <span>Support</span>
                    </a>
                </li>
            </nav>

            <!-- Search Bar -->
            <div class="search-box">
                <input type="text" placeholder="Search electronics, gadgets...">
                <button type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>

            <!-- User Actions -->
            <div class="header-actions">
                @if(Auth::check())
                    <div class="dropdown">
                        <div class="user-dropdown" data-bs-toggle="dropdown">
                            <div class="user-avatar-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <span class="user-name">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--gray);"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('user.profile') }}">
                                    <i class="fas fa-user"></i> Profile
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.orders.index') }}">
                                    <i class="fas fa-box"></i> My Orders
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.wishlist.index') }}">
                                    <i class="far fa-heart"></i> Wishlist
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('user.settings') }}">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item text-danger" href="#" onclick="this.closest('form').submit(); return false;">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="action-item">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="action-item">
                        <i class="fas fa-user-plus"></i>
                        <span>Register</span>
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer - REDUCED SIZE (NORMAL, REALISTIC) -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <!-- Company Info - Reduced -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <a href="{{ route('user.home') }}" class="footer-logo">
                            <div class="footer-logo-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <span class="footer-logo-text"><span>e</span>Cart</span>
                        </a>
                        <p class="footer-text">
                            Your trusted source for latest electronics. Quality products, best prices.
                        </p>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            <a href="#" class="social-link"><i class="fab fa-youtube"></i></a>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Links - Reduced -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">Shop</h4>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-chevron-right"></i> New Arrivals</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Best Sellers</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Deals</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Gift Cards</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Support Links - Reduced -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">Support</h4>
                        <ul class="footer-links">
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Contact</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> FAQs</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Shipping</a></li>
                            <li><a href="#"><i class="fas fa-chevron-right"></i> Returns</a></li>
                        </ul>
                    </div>
                </div>
                
                <!-- Contact Info - Reduced -->
                <div class="col-lg-2 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">Contact</h4>
                        <ul class="contact-info">
                            <li>
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Bangalore, India</span>
                            </li>
                            <li>
                                <i class="fas fa-phone-alt"></i>
                                <span>+91 98765 43210</span>
                            </li>
                            <li>
                                <i class="fas fa-envelope"></i>
                                <span>support@ecart.com</span>
                            </li>
                        </ul>
                    </div>
                </div>
                
                <!-- Newsletter - Reduced -->
                <div class="col-lg-3 col-md-6">
                    <div class="footer-widget">
                        <h4 class="footer-title">Newsletter</h4>
                        <p class="footer-text" style="margin-bottom: 12px;">Get updates on new products</p>
                        <div class="newsletter-form">
                            <div class="input-group">
                                <input type="email" class="form-control" placeholder="Your email">
                                <button class="btn" type="submit">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Footer Bottom - Reduced -->
            <div class="footer-bottom">
                <p class="copyright">
                    &copy; {{ date('Y') }} eCart Electronics. All rights reserved.
                </p>
                <div class="footer-bottom-links">
                    <a href="#">Privacy</a>
                    <a href="#">Terms</a>
                    <a href="#">Cookie</a>
                    <a href="#">Sitemap</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Global CSRF Token
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize Toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "preventDuplicates": true
        };

        // Initialize AOS Animation
        AOS.init({
            duration: 800,
            once: true,
            offset: 100
        });

        // Cart count update
        function updateCartCount() {
            $.ajax({
                url: '{{ route("user.cart.count") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#cart-count').text(response.count);
                    }
                }
            });
        }

        // Wishlist count update
        function updateWishlistCount() {
            $.ajax({
                url: '{{ route("user.wishlist.count") }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#wishlist-count').text(response.count);
                    }
                }
            });
        }

        // Mobile menu toggle
        $(document).ready(function() {
            const $mobileToggle = $('#mobileToggle');
            const $mainNav = $('#mainNav');

            $mobileToggle.on('click', function(e) {
                e.stopPropagation();
                $mainNav.toggleClass('active');
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('.main-nav').length && 
                    !$(e.target).closest('.mobile-toggle').length) {
                    $mainNav.removeClass('active');
                }
            });

            // Initialize counts
            updateCartCount();
            updateWishlistCount();

            // Search functionality
            $('.search-box input').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = $(this).val();
                    if (searchTerm.length > 2) {
                        window.location.href = '{{ route("user.products.index") }}?search=' + encodeURIComponent(searchTerm);
                    }
                }
            });

            $('.search-box button').on('click', function() {
                const searchTerm = $(this).siblings('input').val();
                if (searchTerm.length > 2) {
                    window.location.href = '{{ route("user.products.index") }}?search=' + encodeURIComponent(searchTerm);
                }
            });

            // Newsletter form
            $('.newsletter-form .input-group').on('submit', function(e) {
                e.preventDefault();
                const email = $(this).find('input').val();
                if (email) {
                    toastr.success('Thanks for subscribing!');
                    $(this).find('input').val('');
                }
            });

            // Active nav link highlighting
            const currentUrl = window.location.pathname;
            $('.nav-link').each(function() {
                const linkUrl = $(this).attr('href');
                if (currentUrl === linkUrl || 
                    (linkUrl !== '/' && currentUrl.startsWith(linkUrl) && linkUrl !== '#')) {
                    $(this).addClass('active');
                }
            });

            // Initialize Owl Carousel
            if ($('.owl-carousel').length) {
                $('.owl-carousel').owlCarousel({
                    loop: true,
                    margin: 15,
                    nav: true,
                    dots: false,
                    autoplay: true,
                    autoplayTimeout: 5000,
                    autoplayHoverPause: true,
                    responsive: {
                        0: { items: 1 },
                        576: { items: 2 },
                        768: { items: 3 },
                        992: { items: 4 },
                        1200: { items: 5 }
                    }
                });
            }
        });

        // Add to cart function
        function addToCart(productId, quantity = 1) {
            $.ajax({
                url: '{{ route("user.cart.add") }}',
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity
                },
                beforeSend: function() {
                    $(`[data-product="${productId}"] .btn-add-cart`).html('<div class="loading-spinner"></div>');
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        updateCartCount();
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function(xhr) {
                    if (xhr.status === 401) {
                        toastr.warning('Please login to add items');
                        setTimeout(function() {
                            window.location.href = '{{ route("login") }}';
                        }, 1500);
                    } else {
                        toastr.error('Something went wrong!');
                    }
                },
                complete: function() {
                    $(`[data-product="${productId}"] .btn-add-cart`).html('<i class="fas fa-shopping-cart"></i> Add');
                }
            });
        }

        // Add to wishlist function
        function addToWishlist(productId) {
            $.ajax({
                url: '{{ route("user.wishlist.add") }}',
                type: 'POST',
                data: {
                    product_id: productId
                },
                success: function(response) {
                    if (response.success) {
                        if (response.requires_login) {
                            toastr.warning('Please login to add to wishlist');
                            setTimeout(function() {
                                window.location.href = '{{ route("login") }}';
                            }, 1500);
                        } else {
                            toastr.success(response.message);
                            updateWishlistCount();
                            $(`[data-product="${productId}"] .wishlist-btn i`).removeClass('far').addClass('fas');
                        }
                    } else {
                        toastr.error(response.message);
                    }
                }
            });
        }

        // Quick view function
        function quickView(productId) {
            $.ajax({
                url: '{{ route("user.products.quickView", ":id") }}'.replace(':id', productId),
                type: 'GET',
                beforeSend: function() {
                    $('#quickViewModal .modal-body').html(`
                        <div class="d-flex justify-content-center align-items-center" style="min-height: 250px;">
                            <div class="loading-spinner" style="width: 40px; height: 40px;"></div>
                        </div>
                    `);
                    $('#quickViewModal').modal('show');
                },
                success: function(response) {
                    if (response.success) {
                        $('#quickViewModal .modal-body').html(response.html);
                    }
                },
                error: function() {
                    toastr.error('Failed to load product');
                    $('#quickViewModal').modal('hide');
                }
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>