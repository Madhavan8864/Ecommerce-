@extends('admin.layouts.app')

@section('title', 'Revenue Report')
@section('page-title', 'Revenue Report')

@section('breadcrumbs')
<li class="breadcrumb-item active">Revenue Report</li>
@endsection

@section('page-actions')
<button type="button" class="btn btn-success" onclick="exportReport()">
    <i class="fas fa-download me-2"></i> Export Report
</button>
@endsection

@section('content')
<div class="row">
    <!-- Filters -->
    <div class="col-12 mb-4">
        <div class="card">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="start_date" 
                               name="start_date"
                               value="{{ $startDate }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="date" 
                               class="form-control" 
                               id="end_date" 
                               name="end_date"
                               value="{{ $endDate }}">
                    </div>
                    
                    <div class="col-md-3">
                        <label for="status" class="form-label">Order Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i> Apply Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Summary Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Revenue</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">₹{{ number_format($totalRevenue, 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            New Customers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $newCustomers }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Total Orders</div>
                        @php
                            $totalOrdersCount = $revenueByStatus->sum('revenue_count');
                        @endphp
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrdersCount }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Average Order Value</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            ₹{{ $totalOrdersCount > 0 ? number_format($totalRevenue / $totalOrdersCount, 2) : '0.00' }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue Chart -->
    <div class="col-xl-8 col-lg-7 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue Trend</h6>
            </div>
            <div class="card-body">
                <div class="chart-area">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue by Status -->
    <div class="col-xl-4 col-lg-5 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Revenue by Order Status</h6>
            </div>
            <div class="card-body">
                <div class="chart-pie pt-4 pb-2">
                    <canvas id="statusPieChart"></canvas>
                </div>
                <div class="mt-4 text-center small">
                    @foreach($revenueByStatus as $item)
                    <span class="mr-2">
                        <i class="fas fa-circle" style="color: {{ getStatusColor($item->status) }}"></i>
                        {{ ucfirst($item->status) }}
                    </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top Products -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Top Selling Products</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Sold</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.products.show', $product->id) }}">
                                        {{ Str::limit($product->name, 20) }}
                                    </a>
                                </td>
                                <td>{{ $product->sku }}</td>
                                <td>{{ $product->total_quantity }}</td>
                                <td>₹{{ number_format($product->total_revenue, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Revenue by Category -->
    <div class="col-xl-6 col-lg-6 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Revenue by Category</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Quantity</th>
                                <th>Revenue</th>
                                <th>% of Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($revenueByCategory as $category)
                            <tr>
                                <td>{{ $category->category_name }}</td>
                                <td>{{ $category->total_quantity }}</td>
                                <td>₹{{ number_format($category->total_revenue, 2) }}</td>
                                <td>
                                    @if($totalRevenue > 0)
                                        {{ number_format(($category->total_revenue / $totalRevenue) * 100, 1) }}%
                                    @else
                                        0%
                                    @endif
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
@endsection

@push('styles')
<style>
    .chart-area, .chart-pie {
        position: relative;
        height: 300px;
        width: 100%;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    var ctx = document.getElementById('revenueChart').getContext('2d');
    var revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($dailyRevenue->pluck('date')),
            datasets: [{
                label: 'Revenue (₹)',
                data: @json($dailyRevenue->pluck('revenue')),
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: 'rgba(78, 115, 223, 1)',
                pointRadius: 3,
                pointHoverRadius: 5,
                fill: true
            }]
        },
        options: {
            maintainAspectRatio: false,
            layout: {
                padding: {
                    left: 10,
                    right: 25,
                    top: 25,
                    bottom: 0
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Revenue: ₹' + context.parsed.y.toLocaleString();
                        }
                    }
                }
            }
        }
    });
    
    // Status Pie Chart
    var ctx2 = document.getElementById('statusPieChart').getContext('2d');
    var statusPieChart = new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: @json($revenueByStatus->pluck('status')),
            datasets: [{
                data: @json($revenueByStatus->pluck('revenue')),
                backgroundColor: @json($revenueByStatus->map(function($item) {
                    return getStatusColor($item->status);
                })),
                hoverBackgroundColor: @json($revenueByStatus->map(function($item) {
                    return getStatusColor($item->status);
                })),
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function(tooltipItem, data) {
                        var dataset = data.datasets[tooltipItem.datasetIndex];
                        var total = dataset.data.reduce(function(previousValue, currentValue) {
                            return previousValue + currentValue;
                        });
                        var currentValue = dataset.data[tooltipItem.index];
                        var percentage = Math.floor(((currentValue/total) * 100)+0.5);
                        return data.labels[tooltipItem.index] + ': ₹' + currentValue.toLocaleString() + ' (' + percentage + '%)';
                    }
                }
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80,
        },
    });
    
    // Status color mapping
    function getStatusColor(status) {
        const colors = {
            'pending': '#f6c23e',
            'processing': '#36b9cc',
            'shipped': '#4e73df',
            'delivered': '#1cc88a',
            'cancelled': '#e74a3b',
            'completed': '#1cc88a'
        };
        return colors[status] || '#858796';
    }
    
    // Export report
    function exportReport() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const status = document.getElementById('status').value;
        
        let url = '{{ route("admin.reports.export", "revenue") }}';
        url += '?start_date=' + startDate + '&end_date=' + endDate;
        
        if (status !== 'all') {
            url += '&status=' + status;
        }
        
        window.location.href = url;
    }
</script>

@php
    // Helper function for colors
    function getStatusColor($status) {
        $colors = [
            'pending' => '#f6c23e',
            'processing' => '#36b9cc',
            'shipped' => '#4e73df',
            'delivered' => '#1cc88a',
            'cancelled' => '#e74a3b',
            'completed' => '#1cc88a'
        ];
        return $colors[$status] ?? '#858796';
    }
@endphp
@endpush