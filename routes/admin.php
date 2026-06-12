<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\StockController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\WarehouseController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\SeoController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\HelpController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\ImportController;
use App\Http\Controllers\Admin\SystemController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
| All routes in this file are protected by 'auth' and 'admin' middleware
| and prefixed with '/admin'
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // ==================== DASHBOARD ====================
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Dashboard API routes
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/stats', [DashboardController::class, 'getStats'])->name('stats');
        Route::get('/recent-orders', [DashboardController::class, 'getRecentOrders'])->name('recent-orders');
        Route::get('/top-products', [DashboardController::class, 'getTopProducts'])->name('top-products');
        Route::get('/chart-data', [DashboardController::class, 'getChartData'])->name('chart-data');
    });
    
    // ==================== CATEGORIES ====================
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('index');
        Route::get('/create', [CategoryController::class, 'create'])->name('create');
        Route::post('/', [CategoryController::class, 'store'])->name('store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('destroy');
        Route::get('/{category}', [CategoryController::class, 'show'])->name('show');
    });
    
    // ==================== BRANDS ====================
    Route::prefix('brands')->name('brands.')->group(function () {
        Route::get('/', [BrandController::class, 'index'])->name('index');
        Route::get('/create', [BrandController::class, 'create'])->name('create');
        Route::post('/', [BrandController::class, 'store'])->name('store');
        Route::get('/{brand}/edit', [BrandController::class, 'edit'])->name('edit');
        Route::put('/{brand}', [BrandController::class, 'update'])->name('update');
        Route::delete('/{brand}', [BrandController::class, 'destroy'])->name('destroy');
        Route::get('/{brand}', [BrandController::class, 'show'])->name('show');
    });
    
    // ==================== PRODUCTS ====================
    Route::prefix('products')->name('products.')->group(function () {
        // CRUD operations
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        
        // Additional product operations
        Route::post('/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('toggleFeatured');
        Route::patch('/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/{product}/duplicate', [ProductController::class, 'duplicate'])->name('duplicate');
        Route::delete('/{product}/image/{imageIndex}', [ProductController::class, 'deleteImage'])->name('deleteImage');
        Route::get('/export', [ProductController::class, 'export'])->name('export');
        Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulkAction');
    });
    
    // ==================== ORDERS ====================
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/export', [OrderController::class, 'export'])->name('export');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
        Route::patch('/{order}/tracking', [OrderController::class, 'updateTracking'])->name('updateTracking');
        Route::get('/{order}/invoice', [OrderController::class, 'printInvoice'])->name('invoice');
    });
    
    // ==================== PAYMENTS ====================
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{payment}', [PaymentController::class, 'show'])->name('show');
        Route::patch('/{payment}/status', [PaymentController::class, 'updateStatus'])->name('updateStatus');
        Route::post('/{payment}/refund', [PaymentController::class, 'refundPayment'])->name('refund');
        Route::get('/export', [PaymentController::class, 'export'])->name('export');
    });
    
    // ==================== CUSTOMERS ====================
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/export', [CustomerController::class, 'export'])->name('export');
        Route::delete('/{user}', [CustomerController::class, 'delete'])->name('delete');
        Route::get('/{user}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{user}', [CustomerController::class, 'update'])->name('update');
        Route::patch('/{user}/status', [CustomerController::class, 'toggleStatus'])->name('updateStatus');
        Route::post('/{user}/reset-password', [CustomerController::class, 'resetPassword'])->name('resetPassword');
        Route::get('/{user}/orders', [CustomerController::class, 'orders'])->name('orders');
        Route::get('/{user}', [CustomerController::class, 'show'])->name('show');
    });
    
    // ==================== REPORTS ====================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/revenue', [ReportController::class, 'revenueReport'])->name('revenue');
        Route::get('/sales', [ReportController::class, 'salesReport'])->name('sales');
        Route::get('/inventory', [ReportController::class, 'inventoryReport'])->name('inventory');
        Route::get('/customers', [ReportController::class, 'customerReport'])->name('customers');
        Route::get('/export/{type}', [ReportController::class, 'exportReport'])->name('export');
    });
    
    // ==================== ANALYTICS ====================
    Route::prefix('analytics')->name('analytics.')->group(function () {
        Route::get('/', [AnalyticsController::class, 'index'])->name('index');
        Route::get('/chart-data', [AnalyticsController::class, 'getChartData'])->name('chart');
        Route::get('/export', [AnalyticsController::class, 'export'])->name('export');
    });
    
    // ==================== REVIEWS ====================
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        Route::get('/{review}', [ReviewController::class, 'show'])->name('show');
        Route::post('/{review}/approve', [ReviewController::class, 'approve'])->name('approve');
        Route::post('/{review}/reject', [ReviewController::class, 'reject'])->name('reject');
        Route::delete('/{review}', [ReviewController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-action', [ReviewController::class, 'bulkAction'])->name('bulkAction');
    });
    
    // ==================== COUPONS ====================
    Route::prefix('coupons')->name('coupons.')->group(function () {
        Route::get('/', [CouponController::class, 'index'])->name('index');
        Route::get('/create', [CouponController::class, 'create'])->name('create');
        Route::post('/', [CouponController::class, 'store'])->name('store');
        Route::get('/{coupon}/edit', [CouponController::class, 'edit'])->name('edit');
        Route::put('/{coupon}', [CouponController::class, 'update'])->name('update');
        Route::delete('/{coupon}', [CouponController::class, 'destroy'])->name('destroy');
        Route::get('/{coupon}', [CouponController::class, 'show'])->name('show');
        Route::post('/generate-code', [CouponController::class, 'generateCode'])->name('generateCode');
        Route::patch('/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/validate', [CouponController::class, 'validateCoupon'])->name('validate');
        Route::post('/check-validity', [CouponController::class, 'checkValidity'])->name('checkValidity');
        Route::post('/{coupon}/duplicate', [CouponController::class, 'duplicate'])->name('duplicate');
        Route::post('/bulk-action', [CouponController::class, 'bulkAction'])->name('bulkAction');
        Route::get('/export', [CouponController::class, 'export'])->name('export');
        Route::get('/stats', [CouponController::class, 'getStats'])->name('stats');
    });
    
    // ==================== STOCK MANAGEMENT ====================
    Route::prefix('stock')->name('stock.')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('index');
        Route::get('/movements', [StockController::class, 'movements'])->name('movements');
        Route::get('/alerts', [StockController::class, 'alerts'])->name('alerts');
        Route::post('/{product}/adjust', [StockController::class, 'adjust'])->name('adjust');
        Route::post('/bulk-update', [StockController::class, 'bulkUpdate'])->name('bulkUpdate');
        Route::get('/export', [StockController::class, 'export'])->name('export');
        Route::get('/chart-data', [StockController::class, 'getChartData'])->name('chart');
    });
    
    // ==================== SUPPLIERS ====================
    Route::resource('suppliers', SupplierController::class);
    Route::patch('suppliers/{supplier}/toggle-status', [SupplierController::class, 'toggleStatus'])->name('suppliers.toggleStatus');
    Route::get('suppliers/export', [SupplierController::class, 'export'])->name('suppliers.export');
    
    // ==================== WAREHOUSES ====================
    Route::resource('warehouses', WarehouseController::class);
    Route::patch('warehouses/{warehouse}/toggle-status', [WarehouseController::class, 'toggleStatus'])->name('warehouses.toggleStatus');
    Route::get('warehouses/{warehouse}/stock', [WarehouseController::class, 'getStock'])->name('warehouses.stock');
    
    // ==================== CAMPAIGNS ====================
    Route::prefix('campaigns')->name('campaigns.')->group(function () {
        Route::get('/', [CampaignController::class, 'index'])->name('index');
        Route::get('/create', [CampaignController::class, 'create'])->name('create');
        Route::post('/', [CampaignController::class, 'store'])->name('store');
        Route::get('/{campaign}/edit', [CampaignController::class, 'edit'])->name('edit');
        Route::put('/{campaign}', [CampaignController::class, 'update'])->name('update');
        Route::delete('/{campaign}', [CampaignController::class, 'destroy'])->name('destroy');
        Route::get('/{campaign}', [CampaignController::class, 'show'])->name('show');
        Route::post('/{campaign}/toggle-status', [CampaignController::class, 'toggleStatus'])->name('toggleStatus');
    });
    
    // ==================== SEO ====================
    Route::prefix('seo')->name('seo.')->group(function () {
        Route::get('/', [SeoController::class, 'index'])->name('index');
        Route::put('/update', [SeoController::class, 'update'])->name('update');
        Route::get('/sitemap', [SeoController::class, 'generateSitemap'])->name('sitemap');
        Route::post('/analyze', [SeoController::class, 'analyze'])->name('analyze');
    });
    
    // ==================== NEWSLETTERS ====================
    Route::prefix('newsletters')->name('newsletters.')->group(function () {
        Route::get('/', [NewsletterController::class, 'index'])->name('index');
        Route::get('/create', [NewsletterController::class, 'create'])->name('create');
        Route::post('/', [NewsletterController::class, 'store'])->name('store');
        Route::get('/{newsletter}/edit', [NewsletterController::class, 'edit'])->name('edit');
        Route::put('/{newsletter}', [NewsletterController::class, 'update'])->name('update');
        Route::delete('/{newsletter}', [NewsletterController::class, 'destroy'])->name('destroy');
        Route::post('/{newsletter}/send', [NewsletterController::class, 'send'])->name('send');
        Route::get('/{newsletter}/preview', [NewsletterController::class, 'preview'])->name('preview');
        Route::get('/subscribers', [NewsletterController::class, 'subscribers'])->name('subscribers');
        Route::post('/subscribers/import', [NewsletterController::class, 'importSubscribers'])->name('import');
        Route::get('/subscribers/export', [NewsletterController::class, 'exportSubscribers'])->name('subscribers.export');
    });
    
    // ==================== SETTINGS ====================
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('index');
        Route::post('/general', [SettingController::class, 'updateGeneral'])->name('general');
        Route::post('/payment', [SettingController::class, 'updatePayment'])->name('payment');
        Route::post('/shipping', [SettingController::class, 'updateShipping'])->name('shipping');
        Route::post('/email', [SettingController::class, 'updateEmail'])->name('email');
        Route::post('/tax', [SettingController::class, 'updateTax'])->name('tax');
        Route::post('/theme', [SettingController::class, 'updateTheme'])->name('theme');
        Route::post('/backup', [SettingController::class, 'backup'])->name('backup');
        Route::post('/restore', [SettingController::class, 'restore'])->name('restore');
        
    });
    
    // ==================== PROFILE ====================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::put('/', [ProfileController::class, 'update'])->name('update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('updatePassword');
        Route::post('/avatar', [ProfileController::class, 'updateAvatar'])->name('updateAvatar');
        Route::delete('/avatar', [ProfileController::class, 'removeAvatar'])->name('removeAvatar');
        Route::get('/activity', [ProfileController::class, 'activity'])->name('activity');
    });
    
    // ==================== NOTIFICATIONS ====================
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
        Route::post('/read-all', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
        Route::delete('/clear-all', [NotificationController::class, 'clearAll'])->name('clearAll');
        Route::get('/settings', [NotificationController::class, 'settings'])->name('settings');
        Route::post('/settings', [NotificationController::class, 'updateSettings'])->name('updateSettings');
    });
    
    // ==================== HELP CENTER ====================
    Route::prefix('help')->name('help.')->group(function () {
        Route::get('/', [HelpController::class, 'index'])->name('index');
        Route::get('/documentation', [HelpController::class, 'documentation'])->name('documentation');
        Route::get('/faq', [HelpController::class, 'faq'])->name('faq');
        Route::get('/report', [HelpController::class, 'reportForm'])->name('report');
        Route::post('/report', [HelpController::class, 'submitReport'])->name('report.submit');
        Route::get('/tutorials', [HelpController::class, 'tutorials'])->name('tutorials');
        Route::get('/video/{id}', [HelpController::class, 'video'])->name('video');
        Route::post('/feedback', [HelpController::class, 'feedback'])->name('feedback');
    });
    
    // ==================== ADMIN USER MANAGEMENT ====================
    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{admin}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{admin}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{admin}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::patch('/{admin}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('toggleStatus');
        Route::post('/{admin}/reset-password', [AdminUserController::class, 'resetPassword'])->name('resetPassword');
        Route::get('/roles', [AdminUserController::class, 'roles'])->name('roles');
        Route::post('/roles', [AdminUserController::class, 'storeRole'])->name('roles.store');
        Route::put('/roles/{role}', [AdminUserController::class, 'updateRole'])->name('roles.update');
        Route::delete('/roles/{role}', [AdminUserController::class, 'destroyRole'])->name('roles.destroy');
    });
    
    // ==================== IMPORT/EXPORT ====================
    Route::prefix('import')->name('import.')->group(function () {
        Route::get('/', [ImportController::class, 'index'])->name('index');
        Route::post('/products', [ImportController::class, 'importProducts'])->name('products');
        Route::post('/customers', [ImportController::class, 'importCustomers'])->name('customers');
        Route::post('/orders', [ImportController::class, 'importOrders'])->name('orders');
        Route::post('/suppliers', [ImportController::class, 'importSuppliers'])->name('suppliers');
        Route::post('/coupons', [ImportController::class, 'importCoupons'])->name('coupons');
        Route::get('/template/{type}', [ImportController::class, 'downloadTemplate'])->name('template');
    });
    
    // ==================== SYSTEM ====================
    Route::prefix('system')->name('system.')->group(function () {
        Route::get('/info', [SystemController::class, 'info'])->name('info');
        Route::get('/logs', [SystemController::class, 'logs'])->name('logs');
        Route::post('/cache/clear', [SystemController::class, 'clearCache'])->name('cache.clear');
        Route::post('/optimize', [SystemController::class, 'optimize'])->name('optimize');
        Route::get('/backups', [SystemController::class, 'backups'])->name('backups');
        Route::post('/backup/create', [SystemController::class, 'createBackup'])->name('backup.create');
        Route::post('/backup/restore/{file}', [SystemController::class, 'restoreBackup'])->name('backup.restore');
        Route::delete('/backup/{file}', [SystemController::class, 'deleteBackup'])->name('backup.delete');
        Route::get('/updates', [SystemController::class, 'checkUpdates'])->name('updates');
        Route::post('/update', [SystemController::class, 'runUpdate'])->name('update');
        Route::get('/health', [SystemController::class, 'healthCheck'])->name('health');
        Route::get('/environment', [SystemController::class, 'environment'])->name('environment');
    });
    
});