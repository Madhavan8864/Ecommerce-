<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') - eCart Electronics</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('images/favicon.ico') }}">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    
    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           SLEEK ELECTRONICS / TECH GADGET THEME
           WITH SIDEBAR SCROLL BUTTONS
           ============================================ */
        
        :root {
            /* Premium Electronics Color Palette */
            --deep-navy: #0a1929;
            --navy-blue: #132f4c;
            --slate-blue: #1e3a5f;
            --steel-blue: #2e4e6e;
            
            --tech-silver: #e5e9f0;
            --cool-gray: #d0d9e2;
            --graphite: #4a5c6c;
            --charcoal: #2d3a47;
            
            --circuit-green: #00c853;
            --circuit-green-soft: rgba(0, 200, 83, 0.1);
            --circuit-blue: #2979ff;
            --circuit-blue-soft: rgba(41, 121, 255, 0.1);
            --circuit-amber: #ffc107;
            --circuit-amber-soft: rgba(255, 193, 7, 0.1);
            --circuit-red: #f44336;
            --circuit-red-soft: rgba(244, 67, 54, 0.1);
            
            --glow-blue: rgba(41, 121, 255, 0.4);
            --glow-green: rgba(0, 200, 83, 0.4);
            
            /* UI Colors */
            --bg-dark: #0b1a2a;
            --bg-darker: #07111c;
            --bg-card: #ffffff;
            --bg-sidebar: #0f1e2e;
            --bg-navbar: #ffffff;
            --bg-hover: rgba(41, 121, 255, 0.05);
            
            --border-color: #e2e8f0;
            --border-dark: #2a4052;
            
            --text-light: #ffffff;
            --text-soft: #f0f4fa;
            --text-dim: #b0c4ce;
            --text-muted: #8a9dad;
            --text-dark: #1e2e3e;
            
            /* Dimensions - Precision Engineered */
            --sidebar-width: 280px;
            --sidebar-collapsed-width: 90px;
            --header-height: 80px;
            
            /* Sharp, Precise Corners - Like Electronics */
            --radius-xs: 4px;
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
            --radius-pill: 9999px;
            
            /* Shadows - Clean & Crisp */
            --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-sm: 0 2px 5px rgba(0,0,0,0.03), 0 1px 2px rgba(0,0,0,0.05);
            --shadow-md: 0 5px 15px rgba(0,0,0,0.05), 0 2px 5px rgba(0,0,0,0.03);
            --shadow-lg: 0 10px 25px rgba(0,0,0,0.05), 0 5px 10px rgba(0,0,0,0.02);
            --shadow-xl: 0 20px 40px rgba(0,0,0,0.08);
            
            /* Electronics Gradients */
            --gradient-circuit: linear-gradient(145deg, var(--deep-navy), var(--navy-blue));
            --gradient-tech: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-connect: linear-gradient(90deg, var(--circuit-blue), var(--circuit-green));
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f5f8fc;
            background-image: 
                linear-gradient(rgba(41, 121, 255, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(41, 121, 255, 0.02) 1px, transparent 1px);
            background-size: 30px 30px;
            color: var(--text-dark);
            line-height: 1.6;
            font-weight: 400;
        }

        /* ============================================
           PRELOADER - Circuit Animation
           ============================================ */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--bg-darker);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        .preloader-inner {
            text-align: center;
        }

        .preloader-chip {
            width: 100px;
            height: 100px;
            background: linear-gradient(145deg, #1a3347, #0a1a2a);
            border-radius: var(--radius-lg);
            margin: 0 auto 30px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid var(--circuit-blue);
            box-shadow: 0 0 25px var(--glow-blue);
            animation: chipPulse 1.8s infinite;
        }

        .preloader-chip i {
            font-size: 48px;
            color: var(--circuit-blue);
            text-shadow: 0 0 15px var(--circuit-blue);
        }

        .preloader-chip::before,
        .preloader-chip::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            background: var(--circuit-green);
            border-radius: var(--radius-xs);
            animation: connect 1.5s infinite;
        }

        .preloader-chip::before {
            top: -10px;
            left: -10px;
            animation-delay: 0s;
        }

        .preloader-chip::after {
            bottom: -10px;
            right: -10px;
            animation-delay: 0.5s;
        }

        .preloader-text {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-light);
            letter-spacing: 2px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        .preloader-text span {
            color: var(--circuit-blue);
            text-shadow: 0 0 10px var(--circuit-blue);
        }

        .preloader-bar {
            width: 280px;
            height: 4px;
            background: rgba(255,255,255,0.1);
            border-radius: var(--radius-pill);
            margin: 0 auto;
            position: relative;
            overflow: hidden;
        }

        .preloader-bar::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 40%;
            height: 100%;
            background: linear-gradient(90deg, var(--circuit-blue), var(--circuit-green));
            border-radius: var(--radius-pill);
            animation: loadingScan 1.2s infinite ease-in-out;
            box-shadow: 0 0 10px var(--circuit-blue);
        }

        @keyframes chipPulse {
            0%, 100% { box-shadow: 0 0 20px var(--glow-blue); transform: scale(1); }
            50% { box-shadow: 0 0 40px var(--glow-blue); transform: scale(1.05); }
        }

        @keyframes connect {
            0%, 100% { opacity: 0.3; transform: scale(0.8); }
            50% { opacity: 1; transform: scale(1.2); background: var(--circuit-blue); }
        }

        @keyframes loadingScan {
            0% { left: -40%; }
            100% { left: 100%; }
        }

        /* ============================================
           WRAPPER
           ============================================ */
        .wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
        }

        /* ============================================
           SIDEBAR - WITH SCROLL BUTTONS
           ============================================ */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--bg-sidebar);
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border-dark);
            box-shadow: 5px 0 20px rgba(0,0,0,0.2);
        }

        .sidebar.collapsed {
            width: var(--sidebar-collapsed-width);
        }

        .sidebar-header {
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border-dark);
            height: var(--header-height);
            background: rgba(11, 26, 42, 0.95);
            flex-shrink: 0;
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            gap: 12px;
        }

        .logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(145deg, var(--circuit-blue), #1565c0);
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 22px;
            box-shadow: 0 4px 12px rgba(41, 121, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .logo-icon::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(transparent, rgba(255,255,255,0.2), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% { transform: translateX(-100%) rotate(45deg); }
            20% { transform: translateX(100%) rotate(45deg); }
            100% { transform: translateX(100%) rotate(45deg); }
        }

        .logo-text {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-light);
            letter-spacing: 1px;
        }

        .logo-text span {
            color: var(--circuit-blue);
            font-weight: 800;
        }

        .sidebar-toggle {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--border-dark);
            color: var(--text-dim);
            width: 40px;
            height: 40px;
            border-radius: var(--radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .sidebar-toggle:hover {
            background: var(--circuit-blue);
            border-color: var(--circuit-blue);
            color: white;
        }

        /* Sidebar User */
        .sidebar-user {
            padding: 24px 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 1px solid var(--border-dark);
            flex-shrink: 0;
            background: rgba(11, 26, 42, 0.8);
        }

        .user-avatar {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            overflow: hidden;
            background: linear-gradient(145deg, var(--steel-blue), var(--deep-navy));
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-light);
            font-weight: 600;
            font-size: 22px;
            border: 2px solid var(--circuit-blue);
            box-shadow: 0 0 15px rgba(41, 121, 255, 0.2);
        }

        .user-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .user-info {
            overflow: hidden;
        }

        .user-info h6 {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-light);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-info small {
            font-size: 12px;
            color: var(--text-dim);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-status {
            width: 10px;
            height: 10px;
            background: var(--circuit-green);
            border-radius: var(--radius-pill);
            display: inline-block;
            box-shadow: 0 0 10px var(--circuit-green);
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* ============================================
           SIDEBAR SCROLL CONTAINER WITH BUTTONS
           ============================================ */
        .sidebar-scroll-container {
            position: relative;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 0; /* Important for flex child scrolling */
            background: var(--bg-sidebar);
        }

        .sidebar-nav-wrapper {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 16px;
            scrollbar-width: thin;
            scrollbar-color: var(--circuit-blue) rgba(255,255,255,0.05);
        }

        /* Custom Scrollbar - Tech Style */
        .sidebar-nav-wrapper::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav-wrapper::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.05);
            border-radius: var(--radius-pill);
        }

        .sidebar-nav-wrapper::-webkit-scrollbar-thumb {
            background: var(--circuit-blue);
            border-radius: var(--radius-pill);
            box-shadow: 0 0 10px var(--glow-blue);
        }

        .sidebar-nav-wrapper::-webkit-scrollbar-thumb:hover {
            background: var(--circuit-green);
        }

        /* Scroll Buttons Container */
        .sidebar-scroll-buttons {
            display: flex;
            justify-content: center;
            gap: 8px;
            padding: 12px 16px 16px;
            background: linear-gradient(to top, var(--bg-sidebar) 80%, transparent);
            border-top: 1px solid rgba(41, 121, 255, 0.2);
            flex-shrink: 0;
        }

        .scroll-btn {
            width: 36px;
            height: 36px;
            border-radius: var(--radius-md);
            background: rgba(41, 121, 255, 0.1);
            border: 1px solid rgba(41, 121, 255, 0.3);
            color: var(--text-dim);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
        }

        .scroll-btn:hover {
            background: var(--circuit-blue);
            border-color: var(--circuit-blue);
            color: white;
            transform: scale(1.05);
        }

        .scroll-btn:active {
            transform: scale(0.95);
        }

        .scroll-btn i {
            pointer-events: none;
        }

        /* Sidebar Navigation */
        .sidebar-nav {
            padding-bottom: 20px;
        }

        .nav-section {
            margin-bottom: 28px;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
            padding: 0 12px;
            margin-bottom: 16px;
        }

        .nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            color: var(--text-dim);
            font-size: 14px;
            font-weight: 500;
            border-radius: var(--radius-md);
            transition: all 0.2s;
            text-decoration: none;
            gap: 14px;
            border: 1px solid transparent;
        }

        .nav-link:hover {
            background: rgba(41, 121, 255, 0.08);
            color: white;
            border-color: rgba(41, 121, 255, 0.3);
        }

        .nav-link.active {
            background: rgba(41, 121, 255, 0.15);
            color: white;
            border-color: var(--circuit-blue);
            box-shadow: 0 0 15px rgba(41, 121, 255, 0.2);
        }

        .nav-link i {
            font-size: 18px;
            width: 24px;
            text-align: center;
            color: var(--circuit-blue);
        }

        .nav-link.active i {
            color: var(--circuit-blue);
            text-shadow: 0 0 10px var(--circuit-blue);
        }

        .nav-link .badge {
            margin-left: auto;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: var(--radius-pill);
            font-weight: 600;
            background: var(--circuit-blue);
            color: white;
            box-shadow: 0 0 10px rgba(41, 121, 255, 0.3);
        }

        .sidebar-divider {
            margin: 24px 0;
            border: 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-dark), transparent);
        }

        /* Collapsed Sidebar - Hide Scroll Buttons */
        .sidebar.collapsed .sidebar-scroll-buttons {
            display: none;
        }

        .sidebar.collapsed .sidebar-nav-wrapper {
            padding: 0 8px;
        }

        .sidebar.collapsed .logo-text,
        .sidebar.collapsed .user-info,
        .sidebar.collapsed .nav-link span,
        .sidebar.collapsed .nav-section-title,
        .sidebar.collapsed .sidebar-divider {
            display: none;
        }

        .sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 20px 0;
        }

        .sidebar.collapsed .logo {
            justify-content: center;
        }

        .sidebar.collapsed .logo-icon {
            margin: 0;
        }

        .sidebar.collapsed .sidebar-user {
            justify-content: center;
            padding: 24px 0;
        }

        .sidebar.collapsed .user-avatar {
            margin: 0;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 14px;
        }

        .sidebar.collapsed .nav-link i {
            margin: 0;
            font-size: 20px;
        }

        .sidebar.collapsed .badge {
            display: none;
        }

        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main {
            flex: 1;
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s cubic-bezier(0.2, 0, 0, 1);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: #f5f8fc;
        }

        .main.expanded {
            margin-left: var(--sidebar-collapsed-width);
        }

        /* ============================================
           NAVBAR - Tech Style
           ============================================ */
        .navbar-top {
            background: var(--bg-navbar);
            padding: 0 28px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 99;
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
        }

        .navbar-left {
            display: flex;
            align-items: center;
            gap: 24px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
            padding: 0;
            list-style: none;
            flex-direction: row;
        }

        /* Search Box - Electronics Style */
        .search-box {
            position: relative;
            width: 320px;
        }

        .search-box i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--graphite);
            font-size: 16px;
            z-index: 10;
        }

        .search-box input {
            width: 100%;
            padding: 12px 20px 12px 46px;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-pill);
            font-size: 14px;
            background: white;
            color: var(--text-dark);
            transition: all 0.2s;
            font-weight: 500;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--circuit-blue);
            box-shadow: 0 0 0 3px rgba(41, 121, 255, 0.1);
        }

        /* Nav Icons */
        .nav-link-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--graphite);
            border-radius: var(--radius-md);
            transition: all 0.2s;
            position: relative;
            text-decoration: none;
            background: transparent;
            border: 1.5px solid transparent;
        }

        .nav-link-icon:hover {
            background: rgba(41, 121, 255, 0.05);
            color: var(--circuit-blue);
            border-color: rgba(41, 121, 255, 0.3);
        }

        .nav-link-icon i {
            font-size: 18px;
        }

        .notification-badge {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 20px;
            height: 20px;
            background: var(--circuit-red);
            border-radius: var(--radius-pill);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            font-weight: 700;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
        }

        /* User Avatar Small */
        .user-avatar-sm {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            overflow: hidden;
            background: linear-gradient(145deg, var(--steel-blue), var(--navy-blue));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            border: 2px solid var(--circuit-blue);
        }

        .user-avatar-sm img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* ============================================
           DROPDOWN - Tech Style
           ============================================ */
        .dropdown-menu {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-xl);
            padding: 16px;
            margin-top: 12px;
            min-width: 380px;
            animation: dropdownFade 0.2s ease;
        }

        @keyframes dropdownFade {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dropdown-header {
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dropdown-header h6 {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
            margin: 0;
        }

        .mark-all-read {
            font-size: 12px;
            color: var(--circuit-blue);
            text-decoration: none;
            font-weight: 600;
        }

        .dropdown-body {
            max-height: 380px;
            overflow-y: auto;
        }

        .dropdown-footer {
            padding: 16px 16px 8px;
            border-top: 1px solid var(--border-color);
        }

        .dropdown-item {
            padding: 14px 16px;
            border-radius: var(--radius-md);
            transition: all 0.2s;
            display: flex;
            align-items: flex-start;
            gap: 16px;
            color: var(--text-dark);
            font-size: 14px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .dropdown-item:hover {
            background: rgba(41, 121, 255, 0.04);
            border-color: rgba(41, 121, 255, 0.2);
        }

        /* Notification Item */
        .notification-icon {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: rgba(41, 121, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--circuit-blue);
            font-size: 18px;
            flex-shrink: 0;
        }

        .notification-content h6 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .notification-content p {
            font-size: 13px;
            color: var(--graphite);
            margin-bottom: 4px;
            line-height: 1.5;
        }

        .notification-content small {
            font-size: 11px;
            color: var(--text-muted);
        }

        /* Message Item */
        .message-avatar {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            overflow: hidden;
            flex-shrink: 0;
            border: 2px solid var(--border-color);
        }

        .message-content h6 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 4px;
        }

        .message-content p {
            font-size: 13px;
            color: var(--graphite);
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* ============================================
           CONTENT AREA
           ============================================ */
        .content {
            flex: 1;
            padding: 28px;
            background: #f5f8fc;
        }

        .container-fluid {
            padding: 0;
            max-width: 1600px;
            margin: 0 auto;
            width: 100%;
        }

        /* ============================================
           PAGE HEADER - Electronics Style
           ============================================ */
        .page-header {
            margin-bottom: 28px;
            padding: 0 0 20px 0;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 20px;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .page-header h1 i {
            color: var(--circuit-blue);
            margin-right: 10px;
            font-size: 26px;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            font-size: 14px;
            font-weight: 500;
        }

        .breadcrumb-item a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: var(--circuit-blue);
        }

        .breadcrumb-item.active {
            color: var(--text-dark);
            font-weight: 600;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: var(--text-muted);
            content: "/";
        }

        /* Page Actions */
        .page-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 600;
            border-radius: var(--radius-md);
            transition: all 0.2s;
            border: 1.5px solid transparent;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.3px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-primary {
            background: var(--circuit-blue);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #1565c0;
        }

        .btn-outline-primary {
            background: transparent;
            border: 1.5px solid var(--circuit-blue);
            color: var(--circuit-blue);
        }

        .btn-outline-primary:hover {
            background: var(--circuit-blue);
            color: white;
        }

        /* ============================================
           ALERTS - Electronics Style
           ============================================ */
        .alert {
            border: none;
            border-radius: var(--radius-lg);
            padding: 18px 22px;
            margin-bottom: 28px;
            display: flex;
            align-items: center;
            gap: 14px;
            animation: slideDown 0.3s ease;
            background: white;
            border-left: 4px solid transparent;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            border-left-color: var(--circuit-green);
        }

        .alert-danger {
            border-left-color: var(--circuit-red);
        }

        .alert-warning {
            border-left-color: var(--circuit-amber);
        }

        .alert-info {
            border-left-color: var(--circuit-blue);
        }

        .alert i {
            font-size: 20px;
        }

        .alert-success i { color: var(--circuit-green); }
        .alert-danger i { color: var(--circuit-red); }
        .alert-warning i { color: var(--circuit-amber); }
        .alert-info i { color: var(--circuit-blue); }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ============================================
           CARDS - Tech Gadget Style
           ============================================ */
        .card {
            border: none;
            border-radius: var(--radius-lg);
            background: white;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            margin-bottom: 28px;
            transition: all 0.25s;
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--circuit-blue);
        }

        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 18px 22px;
            font-weight: 600;
            color: var(--text-dark);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h5 {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .card-header h5 i {
            color: var(--circuit-blue);
            margin-right: 8px;
        }

        .card-body {
            padding: 22px;
            color: var(--text-dark);
        }

        .card-footer {
            background: white;
            border-top: 1px solid var(--border-color);
            padding: 16px 22px;
        }

        /* ============================================
           TABLES - Electronics Style
           ============================================ */
        .table {
            margin: 0;
            color: var(--text-dark);
        }

        .table thead th {
            background: #f8fafc;
            color: var(--graphite);
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 16px 16px;
            border-bottom: 1px solid var(--border-color);
            border-top: none;
        }

        .table tbody td {
            padding: 16px 16px;
            vertical-align: middle;
            border-bottom: 1px solid var(--border-color);
            color: var(--text-dark);
            font-size: 14px;
        }

        .table tbody tr:hover {
            background: rgba(41, 121, 255, 0.02);
        }

        /* ============================================
           BADGES - Electronics Status
           ============================================ */
        .badge {
            padding: 6px 14px;
            font-size: 12px;
            font-weight: 600;
            border-radius: var(--radius-pill);
            letter-spacing: 0.3px;
        }

        .badge.bg-primary {
            background: rgba(41, 121, 255, 0.1) !important;
            color: var(--circuit-blue);
            border: 1px solid var(--circuit-blue);
        }

        .badge.bg-success {
            background: rgba(0, 200, 83, 0.1) !important;
            color: var(--circuit-green);
            border: 1px solid var(--circuit-green);
        }

        .badge.bg-warning {
            background: rgba(255, 193, 7, 0.1) !important;
            color: #ed8b00;
            border: 1px solid var(--circuit-amber);
        }

        .badge.bg-danger {
            background: rgba(244, 67, 54, 0.1) !important;
            color: var(--circuit-red);
            border: 1px solid var(--circuit-red);
        }

        /* ============================================
           FORM CONTROLS - Tech Style
           ============================================ */
        .form-control, .form-select {
            background: white;
            border: 1.5px solid var(--border-color);
            border-radius: var(--radius-md);
            padding: 12px 16px;
            font-size: 14px;
            color: var(--text-dark);
            transition: all 0.2s;
            font-weight: 500;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--circuit-blue);
            box-shadow: 0 0 0 3px rgba(41, 121, 255, 0.1);
            outline: none;
        }

        /* ============================================
           STATS CARDS - Electronics Metrics
           ============================================ */
        .stat-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 24px;
            transition: all 0.25s;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .stat-card:hover {
            border-color: var(--circuit-blue);
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, rgba(41, 121, 255, 0.05) 0%, transparent 70%);
            border-radius: 50%;
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-md);
            background: rgba(41, 121, 255, 0.08);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            color: var(--circuit-blue);
            margin-bottom: 18px;
            border: 1px solid rgba(41, 121, 255, 0.2);
        }

        .stat-label {
            font-size: 13px;
            color: var(--text-muted);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 6px;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 6px;
            line-height: 1;
        }

        .stat-change {
            font-size: 12px;
            font-weight: 600;
            color: var(--circuit-green);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(0, 200, 83, 0.08);
            padding: 4px 12px;
            border-radius: var(--radius-pill);
            border: 1px solid rgba(0, 200, 83, 0.2);
        }

        .stat-change.negative {
            color: var(--circuit-red);
            background: rgba(244, 67, 54, 0.08);
            border-color: rgba(244, 67, 54, 0.2);
        }

        /* ============================================
           FOOTER - Tech Style
           ============================================ */
        .footer {
            background: white;
            border-top: 1px solid var(--border-color);
            padding: 18px 28px;
            margin-top: auto;
            margin: 30px 0 0;
        }

        .footer p {
            margin: 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
        }

        .footer i {
            color: var(--circuit-blue);
        }

        /* ============================================
           PRODUCT CARD - Electronics Showcase
           ============================================ */
        .product-card {
            background: white;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 20px;
            transition: all 0.25s;
            height: 100%;
        }

        .product-card:hover {
            border-color: var(--circuit-blue);
            box-shadow: var(--shadow-lg);
            transform: translateY(-3px);
        }

        .product-image {
            width: 100%;
            height: 180px;
            background: #f8fafc;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
            border: 1px solid var(--border-color);
        }

        .product-image i {
            font-size: 64px;
            color: var(--graphite);
        }

        .product-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .product-price {
            font-size: 18px;
            font-weight: 700;
            color: var(--circuit-blue);
            margin-bottom: 12px;
        }

        .product-sku {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
        }

        /* ============================================
           STATUS INDICATORS
           ============================================ */
        .status-badge {
            padding: 6px 14px;
            border-radius: var(--radius-pill);
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .status-active {
            background: rgba(0, 200, 83, 0.08);
            color: var(--circuit-green);
            border: 1px solid rgba(0, 200, 83, 0.3);
        }

        .status-inactive {
            background: rgba(244, 67, 54, 0.08);
            color: var(--circuit-red);
            border: 1px solid rgba(244, 67, 54, 0.3);
        }

        .status-pending {
            background: rgba(255, 193, 7, 0.08);
            color: #ed8b00;
            border: 1px solid rgba(255, 193, 7, 0.3);
        }

        /* ============================================
           LOADING OVERLAY
           ============================================ */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9998;
        }

        .loading-tech {
            text-align: center;
            background: white;
            padding: 32px 40px;
            border-radius: var(--radius-lg);
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow-xl);
        }

        .loading-tech i {
            font-size: 48px;
            color: var(--circuit-blue);
            margin-bottom: 16px;
            animation: spin 1.5s infinite linear;
        }

        .loading-tech span {
            display: block;
            color: var(--text-dark);
            font-size: 15px;
            font-weight: 600;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 992px) {
            .search-box {
                width: 240px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main {
                margin-left: 0 !important;
            }
            
            .navbar-top {
                padding: 0 20px;
            }
            
            .search-box {
                width: 200px;
            }
            
            .sidebar-scroll-buttons {
                padding: 10px 16px;
            }
        }

        @media (max-width: 576px) {
            .search-box {
                display: none;
            }
            
            .content {
                padding: 20px;
            }
            
            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
            }
            
            .page-actions {
                width: 100%;
            }
            
            .page-actions .btn-group {
                width: 100%;
            }
            
            .page-actions .btn {
                flex: 1;
            }
            
            .dropdown-menu {
                min-width: 300px;
                position: fixed !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
            }
            
            .sidebar-scroll-buttons {
                padding: 8px 12px;
            }
            
            .scroll-btn {
                width: 32px;
                height: 32px;
            }
        }

        /* ============================================
           UTILITY CLASSES
           ============================================ */
        .circuit-text {
            color: var(--circuit-blue);
            font-weight: 700;
        }
        
        .tech-divider {
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, var(--circuit-blue), var(--circuit-green));
            border-radius: var(--radius-pill);
            margin: 16px 0;
        }
        
        .bg-circuit-pattern {
            background-image: 
                linear-gradient(rgba(41, 121, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(41, 121, 255, 0.03) 1px, transparent 1px);
            background-size: 20px 20px;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Preloader - Circuit Animation -->
    <div class="preloader">
        <div class="preloader-inner">
            <div class="preloader-chip">
                <i class="fas fa-microchip"></i>
            </div>
            <div class="preloader-text">
                <span>e</span>CART
            </div>
            <div class="preloader-bar"></div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay">
        <div class="loading-tech">
            <i class="fas fa-sync-alt fa-spin"></i>
            <span>PROCESSING...</span>
        </div>
    </div>

    <div class="wrapper">
        <!-- Sidebar - Tech Dashboard with Scroll Buttons -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="logo">
                    <div class="logo-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <span class="logo-text"><span>e</span>CART</span>
                </a>
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <div class="sidebar-user">
                <div class="user-avatar">
                    @if(Auth::check() && Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                    @else
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    @endif
                </div>
                <div class="user-info">
                    <h6>{{ Auth::user()->name ?? 'Alex Chen' }}</h6>
                    <small>
                        <span class="user-status"></span>
                        {{ Auth::user()->role ?? 'System Admin' }}
                    </small>
                </div>
            </div>
            
            <!-- ============================================
                 SIDEBAR SCROLL CONTAINER WITH BUTTONS
                 ============================================ -->
            <div class="sidebar-scroll-container">
                <div class="sidebar-nav-wrapper" id="sidebarNavWrapper">
                    <div class="sidebar-nav">
                        <div class="nav-section">
                            <div class="nav-section-title">CORE</div>
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                        <i class="fas fa-tachometer-alt"></i>
                                        <span>Dashboard</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                                        <i class="fas fa-microchip"></i>
                                        <span>Products</span>
                                        @php
                                            $lowStockCount = 0;
                                            try {
                                                $lowStockCount = \App\Models\Product::where('quantity', '<=', 10)->where('status', 'in_stock')->count();
                                            } catch (Exception $e) {
                                                $lowStockCount = 0;
                                            }
                                        @endphp
                                        @if($lowStockCount > 0)
                                            <span class="badge">{{ $lowStockCount }}</span>
                                        @endif
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                                        <i class="fas fa-list"></i>
                                        <span>Categories</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                                        <i class="fas fa-tag"></i>
                                        <span>Brands</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">SALES</div>
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                                        <i class="fas fa-shopping-cart"></i>
                                        <span>Orders</span>
                                        @php
                                            $pendingOrders = 0;
                                            try {
                                                $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                                            } catch (Exception $e) {
                                                $pendingOrders = 0;
                                            }
                                        @endphp
                                        @if($pendingOrders > 0)
                                            <span class="badge">{{ $pendingOrders }}</span>
                                        @endif
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                                        <i class="fas fa-users"></i>
                                        <span>Customers</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ route('admin.analytics.index') }}" class="nav-link">
                                        <i class="fas fa-chart-line"></i>
                                        <span>Analytics</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ route('admin.reviews.index') }}" class="nav-link">
                                        <i class="fas fa-star"></i>
                                        <span>Reviews</span>
                                        @php
                                            $pendingReviews = 0;
                                            try {
                                                $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                                            } catch (Exception $e) {
                                                $pendingReviews = 0;
                                            }
                                        @endphp
                                        @if($pendingReviews > 0)
                                            <span class="badge">{{ $pendingReviews }}</span>
                                        @endif
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ route('admin.coupons.index') }}" class="nav-link">
                                        <i class="fas fa-ticket-alt"></i>
                                        <span>Coupons</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">INVENTORY</div>
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="{{ route('admin.stock.index') }}" class="nav-link">
                                        <i class="fas fa-boxes"></i>
                                        <span>Stock Management</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.suppliers.index') }}" class="nav-link">
                                        <i class="fas fa-truck"></i>
                                        <span>Suppliers</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.warehouses.index') }}" class="nav-link">
                                        <i class="fas fa-warehouse"></i>
                                        <span>Warehouses</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">MARKETING</div>
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-bullhorn"></i>
                                        <span>Campaigns</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-chart-bar"></i>
                                        <span>SEO</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-envelope"></i>
                                        <span>Newsletters</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">SYSTEM</div>
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-cog"></i>
                                        <span>Settings</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="{{ url('/') }}" class="nav-link" target="_blank">
                                        <i class="fas fa-store"></i>
                                        <span>Store</span>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Logout</span>
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="sidebar-divider"></div>
                        
                        <div class="nav-section">
                            <div class="nav-section-title">SUPPORT</div>
                            <ul class="nav">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-headset"></i>
                                        <span>Help Center</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-file-alt"></i>
                                        <span>Documentation</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-bug"></i>
                                        <span>Report Issue</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <!-- Scroll Buttons -->
                <div class="sidebar-scroll-buttons">
                    <button class="scroll-btn" id="scrollUpBtn" title="Scroll Up">
                        <i class="fas fa-chevron-up"></i>
                    </button>
                    <button class="scroll-btn" id="scrollDownBtn" title="Scroll Down">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>
        </aside>
        
        <!-- Main Content -->
        <div class="main" id="main">
            <!-- Navbar - Tech Style -->
            <nav class="navbar-top">
                <div class="navbar-left">
                    <button class="sidebar-toggle" id="sidebarToggleMobile">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search products, orders...">
                    </div>
                </div>
                
                <div class="navbar-right">
                    <ul class="navbar-nav">
                        <!-- Notifications -->
                        <li class="nav-item dropdown">
                            <a class="nav-link-icon dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                @php
                                    $notificationsCount = 3;
                                @endphp
                                @if($notificationsCount > 0)
                                    <span class="notification-badge">{{ $notificationsCount }}</span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-header">
                                    <h6>Notifications</h6>
                                    @if($notificationsCount > 0)
                                        <a href="#" class="mark-all-read">Mark all read</a>
                                    @endif
                                </div>
                                <div class="dropdown-body">
                                    @if($notificationsCount > 0)
                                        <a href="#" class="dropdown-item">
                                            <div class="notification-item">
                                                <div class="notification-icon">
                                                    <i class="fas fa-shopping-cart"></i>
                                                </div>
                                            
                                            </div>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <div class="notification-item">
                                                <div class="notification-icon">
                                                    <i class="fas fa-star"></i>
                                                </div>
                                                
                                            </div>
                                        </a>
                                        <a href="#" class="dropdown-item">
                                            <div class="notification-item">
                                                <div class="notification-icon">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                </div>
                                                
                                            </div>
                                        </a>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No notifications</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="dropdown-footer">
                                    <a href="#" class="btn btn-outline-primary w-100">View All</a>
                                </div>
                            </div>
                        </li>
                        
                        <!-- Messages -->
                        <li class="nav-item dropdown">
                            <a class="nav-link-icon dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-comment"></i>
                                <span class="notification-badge" style="background: var(--circuit-green);">2</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <div class="dropdown-header">
                                    <h6>Messages</h6>
                                    <a href="#" class="mark-all-read">Mark all read</a>
                                </div>
                                <div class="dropdown-body">
                                    <a href="#" class="dropdown-item">
                                        <div class="message-item">
                                            <div class="message-avatar">
                                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="avatar">
                                            </div>
                                            <div class="message-content">
                                                <h6>Sarah Johnson</h6>
                                                <p>Thanks for the quick delivery! The MacBook is perfect...</p>
                                                <small>5 min ago</small>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="dropdown-item">
                                        <div class="message-item">
                                            <div class="message-avatar">
                                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="avatar">
                                            </div>
                                            <div class="message-content">
                                                <h6>Michael Chen</h6>
                                                <p>Do you have the RTX 4090 in stock? Need it for my build...</p>
                                                <small>25 min ago</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-footer">
                                    <a href="#" class="btn btn-outline-primary w-100">View All</a>
                                </div>
                            </div>
                        </li>
                        
                        <!-- User Profile -->
                        <li class="nav-item dropdown">
                            <a class="nav-link-icon dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="user-avatar-sm">
                                    @if(Auth::check() && Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                    @else
                                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                    @endif
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end profile-dropdown">
                                <div class="dropdown-header">
                                    <div class="d-flex align-items-center">
                                        <div class="user-avatar">
                                            @if(Auth::check() && Auth::user()->avatar)
                                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}">
                                            @else
                                                {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                            @endif
                                        </div>
                                        <div class="user-info ms-3">
                                            <h6>{{ Auth::user()->name ?? 'Alex Chen' }}</h6>
                                            <small>{{ Auth::user()->email ?? 'alex@ecart.com' }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-user"></i> My Profile
                                </a>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-cog"></i> Settings
                                </a>
                                <a href="#" class="dropdown-item">
                                    <i class="fas fa-shield-alt"></i> Security
                                </a>
                                <div class="dropdown-divider"></div>
                                <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            
            <!-- Main Content Area -->
            <main class="content">
                <div class="container-fluid">
                    <!-- Page Header -->
                    <div class="page-header">
                        <div>
                            <h1>
                                <i class="fas fa-microchip"></i>
                                @yield('page-title', 'Dashboard')
                            </h1>
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a></li>
                                    @hasSection('breadcrumbs')
                                        @yield('breadcrumbs')
                                    @else
                                        <li class="breadcrumb-item active">Dashboard</li>
                                    @endif
                                </ol>
                            </nav>
                            <div class="tech-divider"></div>
                        </div>
                        <div class="page-actions">
                            @hasSection('page-actions')
                                @yield('page-actions')
                            @else
                                <div class="btn-group gap-2">
                                    <button class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Product
                                    </button>
                                    <button class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i> Export
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Alerts -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('info'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle"></i>
                            {{ session('info') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Validation Errors -->
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i>
                            <strong>Please check the following:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <!-- Page Content -->
                    @yield('content')
                </div>
            </main>
            
            <!-- Footer -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <p>
                                <i class="fas fa-bolt"></i> eCart Electronics · Premium Gadgets Admin
                            </p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p>
                                <span class="badge bg-primary">v2.5.0</span>
                                <span class="ms-2 text-muted">© {{ date('Y') }} eCart</span>
                            </p>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        $(window).on('load', function() {
            setTimeout(function() {
                $('.preloader').fadeOut('slow');
            }, 1200);
        });

        $(document).ready(function() {
            const sidebar = $('.sidebar');
            const main = $('#main');
            const sidebarToggle = $('#sidebarToggle');
            const sidebarToggleMobile = $('#sidebarToggleMobile');
            
            // Sidebar Toggle
            sidebarToggle.on('click', function() {
                sidebar.toggleClass('collapsed');
                main.toggleClass('expanded');
                localStorage.setItem('sidebarCollapsed', sidebar.hasClass('collapsed'));
            });
            
            sidebarToggleMobile.on('click', function() {
                if ($(window).width() < 768) {
                    sidebar.toggleClass('show');
                } else {
                    sidebar.toggleClass('collapsed');
                    main.toggleClass('expanded');
                    localStorage.setItem('sidebarCollapsed', sidebar.hasClass('collapsed'));
                }
            });
            
            // Load saved state
            if (localStorage.getItem('sidebarCollapsed') === 'true' && $(window).width() >= 768) {
                sidebar.addClass('collapsed');
                main.addClass('expanded');
            }
            
            // Close sidebar on mobile when clicking outside
            $(document).on('click', function(e) {
                if ($(window).width() < 768 && !$(e.target).closest('.sidebar').length && !$(e.target).closest('.sidebar-toggle').length) {
                    sidebar.removeClass('show');
                }
            });
            
            // ============================================
            // SIDEBAR SCROLL BUTTONS FUNCTIONALITY
            // ============================================
            const sidebarNavWrapper = $('#sidebarNavWrapper');
            const scrollUpBtn = $('#scrollUpBtn');
            const scrollDownBtn = $('#scrollDownBtn');
            
            // Scroll amount in pixels
            const scrollAmount = 100;
            
            // Scroll Up
            scrollUpBtn.on('click', function() {
                sidebarNavWrapper.animate({
                    scrollTop: sidebarNavWrapper.scrollTop() - scrollAmount
                }, 300);
            });
            
            // Scroll Down
            scrollDownBtn.on('click', function() {
                sidebarNavWrapper.animate({
                    scrollTop: sidebarNavWrapper.scrollTop() + scrollAmount
                }, 300);
            });
            
            // Show/hide scroll buttons based on scroll position
            function updateScrollButtons() {
                const scrollTop = sidebarNavWrapper.scrollTop();
                const scrollHeight = sidebarNavWrapper[0].scrollHeight;
                const clientHeight = sidebarNavWrapper[0].clientHeight;
                
                // Show/hide up button
                if (scrollTop > 20) {
                    scrollUpBtn.css('opacity', '1').css('pointer-events', 'auto');
                } else {
                    scrollUpBtn.css('opacity', '0.5').css('pointer-events', 'auto');
                }
                
                // Show/hide down button
                if (scrollTop + clientHeight < scrollHeight - 20) {
                    scrollDownBtn.css('opacity', '1').css('pointer-events', 'auto');
                } else {
                    scrollDownBtn.css('opacity', '0.5').css('pointer-events', 'auto');
                }
            }
            
            // Initial check
            setTimeout(function() {
                updateScrollButtons();
            }, 100);
            
            // Update on scroll
            sidebarNavWrapper.on('scroll', function() {
                updateScrollButtons();
            });
            
            // Update on window resize
            $(window).on('resize', function() {
                updateScrollButtons();
            });
            
            // Update when sidebar toggle
            sidebarToggle.on('click', function() {
                setTimeout(function() {
                    updateScrollButtons();
                }, 400);
            });
            
            // Mark all as read
            $('.mark-all-read').on('click', function(e) {
                e.preventDefault();
                const $this = $(this);
                $this.html('<i class="fas fa-spinner fa-spin"></i>');
                
                setTimeout(function() {
                    $('.notification-badge').first().remove();
                    $this.closest('.dropdown').find('.dropdown-body').html(`
                        <div class="text-center py-4">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No notifications</p>
                        </div>
                    `);
                    toastr.success('All notifications cleared');
                    $this.text('Mark all read');
                }, 800);
            });
            
            $('.search-box input').on('keyup', function(e) {
                if (e.key === 'Enter') {
                    const searchTerm = $(this).val();
                    if (searchTerm.length > 2) {
                        toastr.info('Searching: ' + searchTerm);
                    }
                }
            });
            
            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
            
            $('form[data-confirm]').on('submit', function(e) {
                const message = $(this).data('confirm') || 'Are you sure?';
                if (!confirm(message)) {
                    e.preventDefault();
                }
            });
            
            // DataTable initialization
            if ($('.datatable').length) {
                $('.datatable').DataTable({
                    "pageLength": 25,
                    "language": {
                        "search": "Search:",
                        "lengthMenu": "Show _MENU_ entries",
                        "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                        "paginate": {
                            "first": "«",
                            "last": "»",
                            "next": "→",
                            "previous": "←"
                        }
                    },
                    "responsive": true,
                    "autoWidth": false
                });
            }
            
            // Select2 initialization
            if ($('.select2').length) {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    width: '100%'
                });
            }
            
            // Summernote initialization
            if ($('.summernote').length) {
                $('.summernote').summernote({
                    height: 250,
                    toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture']],
                        ['view', ['fullscreen', 'codeview']]
                    ],
                    placeholder: 'Product description...'
                });
            }
            
            // File input preview
            $('input[type="file"]').on('change', function(e) {
                const input = this;
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    const previewId = $(this).data('preview');
                    
                    reader.onload = function(e) {
                        if (previewId) {
                            $('#' + previewId).attr('src', e.target.result).show();
                        }
                    };
                    
                    reader.readAsDataURL(input.files[0]);
                }
            });
            
            // Toastr configuration
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            
            // CSRF token for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        // Global functions
        function showLoading() {
            $('.loading-overlay').fadeIn();
        }
        
        function hideLoading() {
            $('.loading-overlay').fadeOut();
        }
        
        function formatCurrency(amount) {
            return '₹' + parseFloat(amount).toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
        
        function showToast(type, message) {
            toastr[type](message);
        }
        
        window.admin = {
            showLoading,
            hideLoading,
            formatCurrency,
            showToast
        };
        
        // Window resize handler
        $(window).on('resize', function() {
            if ($(window).width() >= 768) {
                $('.sidebar').removeClass('show');
            }
        });
        
        // Active menu highlighting
        const currentUrl = window.location.href;
        $('.sidebar-nav .nav-link').each(function() {
            if (currentUrl.includes($(this).attr('href')) && $(this).attr('href') !== '#') {
                $(this).addClass('active');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>