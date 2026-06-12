<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Controllers\User\SettingController;
use App\Http\Controllers\User\SupportController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home Route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Auth Routes
Route::middleware('guest')->group(function () {
    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Register
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    // Forgot Password with OTP
    Route::get('/forgot-password', [LoginController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

    // OTP Verification
    Route::get('/verify-otp', [LoginController::class, 'showOtpVerificationForm'])->name('password.verify.otp');
    Route::post('/verify-otp', [LoginController::class, 'verifyOtp'])->name('password.verify.otp.post');

    // Resend OTP
    Route::post('/resend-otp', [LoginController::class, 'resendOtp'])->name('password.otp.resend');

    // Reset Password
    Route::get('/reset-password', [LoginController::class, 'showResetPasswordForm'])->name('password.reset.form');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
    
    // Mail Debug Routes
    Route::get('/debug-mail', [App\Http\Controllers\Auth\LoginController::class, 'debugMailConfig'])->name('debug.mail');
    Route::get('/test-mail', [App\Http\Controllers\Auth\LoginController::class, 'testMail'])->name('test.mail');

    // Social Login
    Route::get('/auth/{provider}', [LoginController::class, 'loginWithSocial'])->name('login.social');
    Route::get('/auth/{provider}/callback', [LoginController::class, 'socialCallback'])->name('login.social.callback');
});

// Logout (POST only for security)
Route::post('/logout', [LogoutController::class, 'logout'])->name('logout');

// Email Verification Routes
Route::get('/email/verify', [RegisterController::class, 'showVerificationNotice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/resend', [RegisterController::class, 'resendVerificationEmail'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.resend');

// Phone Verification
Route::get('/phone/verify', [RegisterController::class, 'showPhoneVerificationForm'])
    ->middleware(['auth', 'verified'])
    ->name('verify.phone');

Route::post('/phone/verify', [RegisterController::class, 'verifyPhone'])
    ->middleware(['auth', 'verified'])
    ->name('verify.phone.post');

Route::post('/phone/resend', [RegisterController::class, 'resendPhoneVerification'])
    ->middleware(['auth', 'verified'])
    ->name('verify.phone.resend');

// Complete Profile
Route::get('/complete-profile', [RegisterController::class, 'showCompleteProfileForm'])
    ->middleware(['auth'])
    ->name('complete.profile');

Route::post('/complete-profile', [RegisterController::class, 'completeProfile'])
    ->middleware(['auth']);

// Email/Phone Check Routes (Public - for AJAX)
Route::get('/check-email', [RegisterController::class, 'checkEmail'])->name('register.checkEmail');
Route::get('/check-phone', [RegisterController::class, 'checkPhone'])->name('register.checkPhone');

// User Routes (Protected)
Route::middleware(['auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    // Dashboard/Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Products
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/products/{product}/review', [ProductController::class, 'addReview'])->name('products.review');
    Route::get('/products/compare', [ProductController::class, 'compare'])->name('products.compare');
    Route::get('/products/{id}/quick-view', [ProductController::class, 'quickView'])->name('products.quickView');
    
    // Search
    Route::get('/search', [HomeController::class, 'search'])->name('search');
    
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::get('/cart/mini', [CartController::class, 'getMiniCart'])->name('cart.mini');
    
    // Wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    Route::post('/wishlist/move-to-cart/{id}', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::get('/wishlist/count', [WishlistController::class, 'getWishlistCount'])->name('wishlist.count');
    Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check'])->name('wishlist.check');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');
    Route::post('/checkout/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.removeCoupon');
    Route::post('/checkout/add-address', [CheckoutController::class, 'addAddress'])->name('checkout.addAddress');
    Route::post('/checkout/validate-address', [CheckoutController::class, 'validateAddress'])->name('checkout.validateAddress');

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::match(['post', 'patch'], '/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/orders/{id}/return', [OrderController::class, 'return'])->name('orders.return');
    Route::get('/orders/{id}/invoice', [OrderController::class, 'downloadInvoice'])->name('orders.invoice');
    Route::post('/orders/{id}/reorder', [OrderController::class, 'reorder'])->name('orders.reorder');
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
    Route::post('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.address.store');
    Route::put('/profile/address/{id}', [ProfileController::class, 'updateAddress'])->name('profile.address.update');
    Route::delete('/profile/address/{id}', [ProfileController::class, 'deleteAddress'])->name('profile.address.delete');
    
    // Notifications
    Route::get('/notifications', [ProfileController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/{id}/read', [ProfileController::class, 'markNotificationAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [ProfileController::class, 'markAllNotificationsAsRead'])->name('notifications.readAll');
    Route::delete('/notifications/{id}', [ProfileController::class, 'deleteNotification'])->name('notifications.delete');
    
    // Activity
    Route::get('/activity', [ProfileController::class, 'activity'])->name('activity');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings');
    Route::put('/settings/preferences', [SettingController::class, 'updatePreferences'])->name('settings.updatePreferences');
    Route::get('/settings/privacy', [SettingController::class, 'privacy'])->name('settings.privacy');
    Route::put('/settings/privacy', [SettingController::class, 'updatePrivacy'])->name('settings.updatePrivacy');
    Route::get('/settings/security', [SettingController::class, 'security'])->name('settings.security');
    Route::post('/settings/enable-2fa', [SettingController::class, 'enableTwoFactor'])->name('settings.enable2fa');
    Route::post('/settings/verify-2fa', [SettingController::class, 'verifyTwoFactor'])->name('settings.verify2fa');
    Route::post('/settings/disable-2fa', [SettingController::class, 'disableTwoFactor'])->name('settings.disable2fa');
    Route::get('/settings/sessions', [SettingController::class, 'sessions'])->name('settings.sessions');
    Route::post('/settings/logout-other-sessions', [SettingController::class, 'logoutOtherSessions'])->name('settings.logoutOtherSessions');
    Route::delete('/settings/logout-device/{sessionId}', [SettingController::class, 'logoutFromDevice'])->name('settings.logoutFromDevice');
    Route::post('/settings/delete-account', [SettingController::class, 'deleteAccount'])->name('settings.deleteAccount');
    Route::get('/settings/export-data', [SettingController::class, 'exportData'])->name('settings.exportData');

    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::post('/support/send', [SupportController::class, 'send'])->name('support.send');
    
}); // Closing bracket for the user route group

// Additional Pages (Public)
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// Include admin routes
require __DIR__.'/admin.php';