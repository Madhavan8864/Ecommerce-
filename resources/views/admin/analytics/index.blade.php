@extends('admin.layouts.app')

@section('title', 'Analytics')
@section('page-title', 'Analytics Dashboard')

@section('breadcrumbs')
<li class="breadcrumb-item"><a href="#">Reports</a></li>
<li class="breadcrumb-item active">Analytics</li>
@endsection

@section('page-actions')
<div class="btn-group gap-2">
    <select class="form-select" id="periodSelect" style="width: 150px;">
        <option value="week" {{ $period == 'week' ? 'selected' : '' }}>Last 7 Days</option>
        <option value="month" {{ $period == 'month' ? 'selected' : '' }}>Last 30 Days</option>
        <option value="quarter" {{ $period == 'quarter' ? 'selected' : '' }}>Last 90 Days</option>
        <option value="year" {{ $period == 'year' ? 'selected' : '' }}>Last Year</option>
    </select>
    <button class="btn btn-outline-primary" onclick="exportAnalytics()">
        <i class="fas fa-download"></i> Export
    </button>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Sales Stats -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <div class="stat-label">Total Orders</div>
            <div class="stat-value">{{ $salesData['totalOrders'] }}</div>
            <div class="stat-change">
                <i class="fas fa-arrow-up"></i> {{ round(($salesData['completedOrders'] / max($salesData['totalOrders'], 1)) * 100) }}% completed
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-rupee-sign"></i>
            </div>
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value">₹{{ number_format($salesData['totalRevenue'], 0) }}</div>
            <div class="stat-change">
                <i class="fas fa-chart-line"></i> Avg ₹{{ number_format($salesData['averageOrderValue'], 0) }}/order
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-label">Products Sold</div>
            <div class="stat-value">{{ $productData['productsSold'] }}</div>
            <div class="stat-change">
                <i class="fas fa-cube"></i> {{ $productData['activeProducts'] }} active products
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-label">New Customers</div>
            <div class="stat-value">{{ $customerData['newCustomers'] }}</div>
            <div class="stat-change">
                <i class="fas fa-user-plus"></i> {{ $customerData['activeCustomers'] }} active
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-chart-line me-2"></i>Revenue Trend</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-crown me-2"></i>Top Products</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>{{ Str::limit($product->name, 20) }}</td>
                                <td>{{ $product->total_sold }}</td>
                                <td>₹{{ number_format($product->total_revenue, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sales by Day -->
    <div class="col-xl-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-calendar-alt me-2"></i>Daily Sales</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Orders</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesByDay as $day)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($day->date)->format('d M Y') }}</td>
                                <td>{{ $day->order_count }}</td>
                                <td>₹{{ number_format($day->revenue, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Categories -->
    <div class="col-xl-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-list-alt me-2"></i>Category Performance</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Units Sold</th>
                                <th>Revenue</th>
                                <th>%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $totalRevenue = $topCategories->sum('total_revenue'); @endphp
                            @foreach($topCategories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->total_sold }}</td>
                                <td>₹{{ number_format($category->total_revenue, 0) }}</td>
                                <td>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-primary" 
                                             style="width: {{ $totalRevenue > 0 ? ($category->total_revenue / $totalRevenue) * 100 : 0 }}%"></div>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Inventory Status -->
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-warehouse me-2"></i>Inventory Status</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Total Products</span>
                    <strong>{{ $productData['totalProducts'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Active Products</span>
                    <strong class="text-success">{{ $productData['activeProducts'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Low Stock</span>
                    <strong class="text-warning">{{ $productData['lowStock'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Out of Stock</span>
                    <strong class="text-danger">{{ $productData['outOfStock'] }}</strong>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Customer Stats -->
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-user-circle me-2"></i>Customer Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Total Customers</span>
                    <strong>{{ $customerData['totalCustomers'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>New Customers</span>
                    <strong class="text-success">{{ $customerData['newCustomers'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Active Customers</span>
                    <strong>{{ $customerData['activeCustomers'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Repeat Customers</span>
                    <strong>{{ $customerData['repeatCustomers'] }}</strong>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="col-xl-4 mb-4">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-bolt me-2"></i>Quick Stats</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Conversion Rate</span>
                    <strong>3.2%</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Avg. Order Value</span>
                    <strong>₹{{ number_format($salesData['averageOrderValue'], 0) }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Return Rate</span>
                    <strong>1.8%</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Customer Satisfaction</span>
                    <strong>4.8/5</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Period change
        $('#periodSelect').change(function() {
            window.location.href = '{{ route("admin.analytics.index") }}?period=' + $(this).val();
        });
        
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const salesData = @json($salesByDay);
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.map(item => new Date(item.date).toLocaleDateString('en-IN', { day: 'numeric', month: 'short' })),
                datasets: [{
                    label: 'Revenue (₹)',
                    data: salesData.map(item => item.revenue),
                    borderColor: '#2979ff',
                    backgroundColor: 'rgba(41, 121, 255, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#2979ff',
                    pointBorderColor: '#fff',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₹' + context.parsed.y.toLocaleString('en-IN');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            }
                        }
                    }
                }
            }
        });
    });
    
    function exportAnalytics() {
        const period = $('#periodSelect').val();
        window.location.href = '{{ route("admin.analytics.export") }}?period=' + period;
    }
</script>
@endpush