<header class="header sticky-top">
    <!-- Top Bar -->
    <div class="top-bar bg-primary text-white py-2 d-none d-md-block">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="top-bar-left">
                        <span><i class="fas fa-phone me-2"></i> Call Us: +91 9876543210</span>
                        <span class="ms-4"><i class="fas fa-envelope me-2"></i> support@ecart-electronics.com</span>
                    </div>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="top-bar-right">
                        @auth
                            <span class="me-3">Welcome, {{ Auth::user()->name }}!</span>
                            <a href="{{ route('user.orders.index') }}" class="text-white me-3">
                                <i class="fas fa-box me-1"></i> My Orders
                            </a>
                            <a href="{{ route('user.profile') }}" class="text-white me-3">
                                <i class="fas fa-user me-1"></i> My Account
                            </a>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-white p-0 border-0">
                                    <i class="fas fa-sign-out-alt me-1"></i> Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-white me-3">
                                <i class="fas fa-sign-in-alt me-1"></i> Login
                            </a>
                            <a href="{{ route('register') }}" class="text-white">
                                <i class="fas fa-user-plus me-1"></i> Register
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="main-header py-3">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-lg-2 col-md-3 col-6">
                    <div class="logo">
                        <a href="{{ route('user.home') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="eCart Electronics" class="img-fluid" width="150">
                        </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="col-lg-6 col-md-5 d-none d-md-block">
                    <form action="{{ route('user.products.index') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text" 
                                   class="form-control" 
                                   name="search" 
                                   placeholder="Search for products..."
                                   value="{{ request('search') }}">
                            <select class="form-select w-auto" name="category">
                                <option value="">All Categories</option>
                                @foreach(\App\Models\Category::where('status', 'active')->get() as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Action Icons -->
                <div class="col-lg-4 col-md-4 col-6">
                    <div class="header-actions d-flex justify-content-end align-items-center">
                        <!-- Wishlist -->
                        <div class="action-item me-4">
                            <a href="{{ route('user.wishlist.index') }}" class="text-dark position-relative">
                                <i class="fas fa-heart fa-lg"></i>
                                <span class="badge bg-danger position-absolute top-0 start-100 translate-middle" id="wishlist-count">0</span>
                            </a>
                        </div>

                        <!-- Cart -->
                        <div class="action-item me-4">
                            <a href="{{ route('user.cart.index') }}" class="text-dark position-relative">
                                <i class="fas fa-shopping-cart fa-lg"></i>
                                <span class="badge bg-primary position-absolute top-0 start-100 translate-middle" id="cart-count">0</span>
                            </a>
                        </div>

                        <!-- Mobile Menu Toggle -->
                        <div class="action-item d-md-none">
                            <button class="btn btn-link text-dark p-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                                <i class="fas fa-bars fa-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-md navbar-light bg-light border-top">
        <div class="container">
            <!-- Mobile Search -->
            <div class="d-md-none w-100 mb-3">
                <form action="{{ route('user.products.index') }}" method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" 
                               class="form-control" 
                               name="search" 
                               placeholder="Search products..."
                               value="{{ request('search') }}">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Desktop Navigation -->
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}" href="{{ route('user.products.index') }}">
                            All Products
                        </a>
                    </li>
                    @php
                        $mainCategories = \App\Models\Category::where('status', 'active')
                            ->whereNull('parent_id')
                            ->withCount(['products' => function($query) {
                                $query->where('is_active', true)->where('status', 'in_stock');
                            }])
                            ->having('products_count', '>', 0)
                            ->limit(6)
                            ->get();
                    @endphp
                    @foreach($mainCategories as $category)
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ $category->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @foreach($category->children()->where('status', 'active')->get() as $subCategory)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.products.index', ['category' => $subCategory->slug]) }}">
                                            {{ $subCategory->name }}
                                        </a>
                                    </li>
                                @endforeach
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('user.products.index', ['category' => $category->slug]) }}">
                                        View All {{ $category->name }}
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endforeach
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            Contact Us
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Mobile Menu Offcanvas -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="mobileMenu">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title">Menu</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.home') ? 'active' : '' }}" href="{{ route('user.home') }}">
                    <i class="fas fa-home me-2"></i> Home
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('user.products.*') ? 'active' : '' }}" href="{{ route('user.products.index') }}">
                    <i class="fas fa-box me-2"></i> All Products
                </a>
            </li>
            @foreach($mainCategories as $category)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.products.index', ['category' => $category->slug]) }}">
                        <i class="fas fa-tag me-2"></i> {{ $category->name }}
                    </a>
                </li>
            @endforeach
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.wishlist.index') }}">
                    <i class="fas fa-heart me-2"></i> Wishlist
                    <span class="badge bg-danger float-end" id="mobile-wishlist-count">0</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('user.cart.index') }}">
                    <i class="fas fa-shopping-cart me-2"></i> Cart
                    <span class="badge bg-primary float-end" id="mobile-cart-count">0</span>
                </a>
            </li>
            @auth

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('user.profile') }}">
                        <i class="fas fa-user me-2"></i> My Account
                    </a>
                </li>
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-link btn btn-link text-start w-100">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">
                        <i class="fas fa-sign-in-alt me-2"></i> Login
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">
                        <i class="fas fa-user-plus me-2"></i> Register
                    </a>
                </li>
            @endauth
        </ul>
    </div>
</div>