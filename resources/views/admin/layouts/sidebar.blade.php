<aside class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <img src="{{ asset('images/logo.png') }}" alt="eCart Electronics" height="40">
            <span class="logo-text">eCart Admin</span>
        </a>
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
    </div>
    
    <div class="sidebar-content">
        <div class="sidebar-user">
            <div class="user-avatar">
                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('images/default-avatar.png') }}" 
                     alt="{{ Auth::user()->name }}">
            </div>
            <div class="user-info">
                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                <small class="text-muted">{{ Auth::user()->role }}</small>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                        <i class="fas fa-box me-2"></i>
                        <span>Products</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <i class="fas fa-list-alt me-2"></i>
                        <span>Categories</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.brands.index') }}" class="nav-link {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                        <i class="fas fa-tags me-2"></i>
                        <span>Brands</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <i class="fas fa-shopping-cart me-2"></i>
                        <span>Orders</span>
                        @php
                            $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                        @endphp
                        @if($pendingOrders > 0)
                            <span class="badge bg-danger float-end">{{ $pendingOrders }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <i class="fas fa-users me-2"></i>
                        <span>Customers</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.reports.revenue') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar me-2"></i>
                        <span>Reports</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}">
                        <i class="fas fa-star me-2"></i>
                        <span>Reviews</span>
                        @php
                            $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                        @endphp
                        @if($pendingReviews > 0)
                            <span class="badge bg-warning float-end">{{ $pendingReviews }}</span>
                        @endif
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.coupons.index') }}" class="nav-link {{ request()->routeIs('admin.coupons.*') ? 'active' : '' }}">
                        <i class="fas fa-ticket-alt me-2"></i>
                        <span>Coupons</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <i class="fas fa-cog me-2"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
            
            <hr class="sidebar-divider">
            
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('user.home') }}" class="nav-link" target="_blank">
                        <i class="fas fa-external-link-alt me-2"></i>
                        <span>View Store</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">
                        @csrf
                        <a href="#" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            <span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>