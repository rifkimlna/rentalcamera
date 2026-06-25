<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\MaintenanceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Customer\TransactionController as CustomerTransactionController;
use App\Http\Controllers\Customer\ReviewController;
use App\Http\Controllers\Admin\StudioController as AdminStudioController;
use App\Http\Controllers\Customer\StudioController as CustomerStudioController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Halaman Utama (Landing Page) - Tanpa Auth
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::get('/products', [HomeController::class, 'products'])->name('products');
Route::get('/products/{slug}', [HomeController::class, 'productDetail'])->name('product.detail');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/faq', [HomeController::class, 'faq'])->name('faq');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

// Social Login (Google)
Route::prefix('auth')->name('auth.')->group(function () {
    Route::get('/google', [SocialAuthController::class, 'redirectToGoogle'])->name('google');
    Route::get('/google/callback', [SocialAuthController::class, 'handleGoogleCallback'])->name('google.callback');
});

// Email Verification
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])
    ->middleware('auth')
    ->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [AuthController::class, 'resendVerification'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Profile
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [AuthController::class, 'updatePassword'])->name('profile.password');
    
    // Notifications
    Route::get('/notifications', [AuthController::class, 'notifications'])->name('notifications');
    Route::post('/notifications/mark-as-read', [AuthController::class, 'markNotificationsAsRead'])->name('notifications.mark-read');
    
    // Dashboard based on role - FIXED: Gunakan helper auth()
    Route::get('/dashboard', function () {
        if (Auth::check()) {
            $user = Auth::user();
            
            if ($user->role === 'admin' || $user->role === 'superadmin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('customer.dashboard');
            }
        }
        
        return redirect()->route('login');
    })->name('dashboard');
    
    // ============================================
    // ADMIN ROUTES
    // ============================================
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/summary', [AdminDashboardController::class, 'getSummary'])->name('dashboard.summary');
        Route::get('/dashboard/chart-data', [AdminDashboardController::class, 'getChartData'])->name('dashboard.chart-data');
        
        // Products Management
        Route::resource('products', ProductController::class);
        Route::post('products/{product}/restore', [ProductController::class, 'restore'])->name('products.restore');
        Route::post('products/{product}/update-status', [ProductController::class, 'updateStatus'])->name('products.update-status');
        Route::post('products/{product}/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
        Route::get('products/{product}/maintenance', [ProductController::class, 'maintenance'])->name('products.maintenance');
        Route::post('products/{product}/maintenance', [ProductController::class, 'storeMaintenance'])->name('products.store-maintenance');
   
Route::post('products/{product}/delete-image', [ProductController::class, 'deleteImage'])->name('admin.products.delete-image');
        
        // Brands Management
        Route::get('brands', [ProductController::class, 'brands'])->name('brands.index');
        Route::post('brands', [ProductController::class, 'storeBrand'])->name('brands.store');
        Route::put('brands/{brand}', [ProductController::class, 'updateBrand'])->name('brands.update');
        Route::delete('brands/{brand}', [ProductController::class, 'destroyBrand'])->name('brands.destroy');
        
        // Categories Management
        Route::get('categories', [ProductController::class, 'categories'])->name('categories.index');
        Route::post('categories', [ProductController::class, 'storeCategory'])->name('categories.store');
        Route::put('categories/{category}', [ProductController::class, 'updateCategory'])->name('categories.update');
        Route::delete('categories/{category}', [ProductController::class, 'destroyCategory'])->name('categories.destroy');
        
        // Transactions routes
Route::get('transactions', [TransactionController::class, 'index'])->name('transactions.index');
Route::get('transactions/create/manual', [TransactionController::class, 'createManual'])->name('transactions.create.manual');

Route::get('transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
Route::post('transactions/{transaction}/update-status', [TransactionController::class, 'updateStatus'])->name('transactions.update-status');
Route::get('transactions/{transaction}/print', [TransactionController::class, 'printInvoice'])->name('transactions.print');
Route::get('transactions/export', [TransactionController::class, 'export'])->name('transactions.export');
Route::post('transactions/{transaction}/update-shipping', [TransactionController::class, 'updateShipping'])->name('transactions.update-shipping');


        
        // Shipping Management
        Route::get('shipping/{transaction}', [TransactionController::class, 'shipping'])->name('shipping.index');
        Route::post('shipping/{transaction}', [TransactionController::class, 'updateShipping'])->name('shipping.update');
        Route::post('shipping/{transaction}/complete', [TransactionController::class, 'completeShipping'])->name('shipping.complete');
        
        // Users Management
        Route::resource('users', UserController::class);
        Route::post('users/{user}/update-status', [UserController::class, 'updateStatus'])->name('users.update-status');
        Route::post('users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::post('users/{user}/verify-ktp', [UserController::class, 'verifyKtp'])->name('users.verify-ktp');

           
    Route::post('/users/{id}/verify-ktp', [UserController::class, 'verifyKtp'])->name('users.verifyKtp');
    Route::post('/users/{id}/update-deposit', [UserController::class, 'updateDeposit'])->name('users.updateDeposit');
    Route::post('/users/{id}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::post('/users/{id}/activate', [UserController::class, 'activate'])->name('users.activate');
        
        // Reviews Management
        Route::get('reviews', [TransactionController::class, 'reviews'])->name('reviews.index');
        Route::get('reviews/{review}', [TransactionController::class, 'showReview'])->name('reviews.show');
        Route::post('reviews/{review}/approve', [TransactionController::class, 'approveReview'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [TransactionController::class, 'rejectReview'])->name('reviews.reject');
        Route::post('reviews/{review}/reply', [TransactionController::class, 'replyReview'])->name('reviews.reply');
        
        // Vouchers & Promotions
        Route::get('vouchers', [TransactionController::class, 'vouchers'])->name('vouchers.index');
        Route::post('vouchers', [TransactionController::class, 'storeVoucher'])->name('vouchers.store');
        Route::put('vouchers/{voucher}', [TransactionController::class, 'updateVoucher'])->name('vouchers.update');
        Route::delete('vouchers/{voucher}', [TransactionController::class, 'destroyVoucher'])->name('vouchers.destroy');
        
        // Reports
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/transactions', [ReportController::class, 'transactionReport'])->name('reports.transactions');
        Route::get('reports/products', [ReportController::class, 'productReport'])->name('reports.products');
        Route::get('reports/users', [ReportController::class, 'userReport'])->name('reports.users');
        Route::get('reports/export/{type}', [ReportController::class, 'exportReport'])->name('reports.export');
        
        // Settings
        Route::get('settings', [AdminDashboardController::class, 'settings'])->name('settings.index');
        Route::post('settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
        Route::get('settings/payment-methods', [AdminDashboardController::class, 'paymentMethods'])->name('settings.payment-methods');
        Route::post('settings/payment-methods', [AdminDashboardController::class, 'updatePaymentMethods'])->name('settings.update-payment-methods');
        
        // Studio Management
        Route::get('studio/bookings', [AdminStudioController::class, 'bookings'])->name('studio.bookings');
        Route::put('studio/bookings/{id}/status', [AdminStudioController::class, 'bookingUpdateStatus'])->name('studio.booking.update-status');
        Route::get('studio/bookings/{id}/print', [AdminStudioController::class, 'bookingPrint'])->name('studio.booking.print');
        Route::resource('studio', AdminStudioController::class);
        Route::get('studio/{studio}/paket', [AdminStudioController::class, 'paketIndex'])->name('studio.paket.index');
        Route::get('studio/{studio}/paket/create', [AdminStudioController::class, 'paketCreate'])->name('studio.paket.create');
        Route::post('studio/{studio}/paket', [AdminStudioController::class, 'paketStore'])->name('studio.paket.store');
        Route::get('studio/{studio}/paket/{paket}/edit', [AdminStudioController::class, 'paketEdit'])->name('studio.paket.edit');
        Route::put('studio/{studio}/paket/{paket}', [AdminStudioController::class, 'paketUpdate'])->name('studio.paket.update');
        Route::delete('studio/{studio}/paket/{paket}', [AdminStudioController::class, 'paketDestroy'])->name('studio.paket.destroy');

        // Activity Logs
        Route::get('activity-logs', [AdminDashboardController::class, 'activityLogs'])->name('activity-logs');
        
        // Maintenance
        Route::get('maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::post('maintenance/{maintenance}/complete', [MaintenanceController::class, 'complete'])->name('maintenance.complete');
    });
    
    // ============================================
    // CUSTOMER ROUTES
    // ============================================
    Route::prefix('customer')->name('customer.')->middleware('customer')->group(function () {
        // Dashboard
        Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/summary', [CustomerDashboardController::class, 'getSummary'])->name('dashboard.summary');
        Route::get('/dashboard/activity', [CustomerDashboardController::class, 'activity'])->name('dashboard.activity');
        
        // Products Browsing
        Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
        Route::get('/products/search', [CustomerProductController::class, 'search'])->name('products.search');
        Route::get('/products/category/{slug}', [CustomerProductController::class, 'byCategory'])->name('products.category');
        Route::get('/products/brand/{slug}', [CustomerProductController::class, 'byBrand'])->name('products.brand');
        Route::get('/products/{product}', [CustomerProductController::class, 'show'])->name('products.show');
        
        // Cart Management
        Route::post('/products/check-availability', [CustomerProductController::class, 'checkAvailability'])->name('products.check-availability');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');
        Route::post('/cart/update-dates/{cart}', [CartController::class, 'updateDates'])->name('cart.update-dates');
        Route::get('/cart/count', [CartController::class, 'getCartSummary'])->name('cart.summary');
        Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
        Route::post('/cart/direct-rent', [CartController::class, 'directRent'])->name('cart.direct-rent');
        // Checkout Process
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::post('/checkout/validate-voucher', [CheckoutController::class, 'checkVoucher'])->name('checkout.validate-voucher');
        Route::get('/checkout/payment/{transaction}', [CheckoutController::class, 'payment'])->name('checkout.payment');
        Route::post('/checkout/midtrans-callback', [CheckoutController::class, 'midtransCallback'])->name('checkout.midtrans-callback');
        Route::get('/checkout/success/{transaction}', [CheckoutController::class, 'success'])->name('checkout.success');
        Route::get('/checkout/failed/{transaction}', [CheckoutController::class, 'failed'])->name('checkout.failed');
        Route::get('/checkout/pending/{transaction}', [CheckoutController::class, 'pending'])->name('checkout.pending');
        Route::get('/checkout/check-status/{transaction}', [CheckoutController::class, 'checkStatus'])->name('checkout.check-status');
        
        // Transactions
        Route::get('/transactions', [CustomerTransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [CustomerTransactionController::class, 'show'])->name('transactions.show');
        Route::get('/transactions/{transaction}/invoice', [CustomerTransactionController::class, 'invoice'])->name('transactions.invoice');
        Route::post('/transactions/{transaction}/cancel', [CustomerTransactionController::class, 'cancel'])->name('transactions.cancel');
        Route::post('/transactions/{transaction}/request-cancel', [CustomerTransactionController::class, 'requestCancel'])->name('transactions.request-cancel');
        Route::post('/transactions/{transaction}/extend', [CustomerTransactionController::class, 'extend'])->name('transactions.extend');
        
        // Reviews
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::get('/reviews/available', [ReviewController::class, 'available'])->name('reviews.available');
        Route::get('/reviews/create/{transaction}', [ReviewController::class, 'create'])->name('reviews.create');
        Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
        Route::get('/reviews/{review}', [ReviewController::class, 'show'])->name('reviews.show');
        Route::get('/reviews/{review}/edit', [ReviewController::class, 'edit'])->name('reviews.edit');
        Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
        
        // Deposit
        Route::get('/deposit', [CustomerTransactionController::class, 'deposit'])->name('deposit.index');
        Route::post('/deposit/topup', [CustomerTransactionController::class, 'topup'])->name('deposit.topup');
        Route::get('/deposit/history', [CustomerTransactionController::class, 'depositHistory'])->name('deposit.history');
        
        // Shipping Tracking
        Route::get('/shipping/{transaction}', [CustomerTransactionController::class, 'shipping'])->name('shipping.track');

        // Studio Rental
        Route::get('/studio', [CustomerStudioController::class, 'index'])->name('studio.index');
        Route::get('/studio/{slug}', [CustomerStudioController::class, 'show'])->name('studio.show');
        Route::post('/studio/booking', [CustomerStudioController::class, 'booking'])->name('studio.booking');
        Route::get('/studio/payment/{id}', [CustomerStudioController::class, 'payment'])->name('studio.payment');
        Route::post('/studio/callback', [CustomerStudioController::class, 'callback'])->name('studio.callback');
        Route::get('/studio/check-status/{id}', [CustomerStudioController::class, 'checkStatus'])->name('studio.check-status');
        Route::get('/studio/booking/{id}/success', [CustomerStudioController::class, 'bookingSuccess'])->name('studio.booking.success');
        Route::get('/my-bookings/studio', [CustomerStudioController::class, 'myBookings'])->name('studio.my-bookings');
        Route::put('/studio/booking/{id}/cancel', [CustomerStudioController::class, 'bookingCancel'])->name('studio.booking.cancel');
    });
});

// Public Routes (for product details, etc.)
Route::get('/product/{slug}', [HomeController::class, 'productDetail'])->name('product.public.detail');

// Fallback Route
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

// Midtrans Notification Route (public for callback)
Route::post('/midtrans/notification', [CheckoutController::class, 'midtransNotification'])
    ->name('midtrans.notification');
Route::post('/midtrans/studio-notification', [CustomerStudioController::class, 'notification'])
    ->name('midtrans.studio-notification');
