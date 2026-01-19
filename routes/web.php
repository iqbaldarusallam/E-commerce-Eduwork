<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\TransactionController as AdminTransactionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\CategoryController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\TransactionController;
use App\Http\Controllers\User\WishlistController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (auth('web')->check()) {
        // Redirect berdasarkan role: admin ke admin.dashboard, user ke user.home
        if (auth('web')->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.home');

    }

    return redirect()->route('login');
});

// Alias routes untuk backward compatibility
Route::middleware(['auth'])->group(function () {

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
});

// User authenticated routes
Route::middleware(['auth'])->group(function () {
    // Contact page route
    Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

    // Maintain legacy '/profile' routes for backward compatibility
    Route::get('/profile', [ProfileController::class, 'edit']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);
    // Global dashboard route: redirect based on user role (admin -> admin.dashboard, user -> user.home)
    Route::get('/dashboard', function () {
        if (auth('web')->check() && auth('web')->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('user.home');

        return redirect()->route('user.dashboard');
    })->name('dashboard');
    Route::prefix('user')->name('user.')->group(function () {
        // Home route
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Home route
        Route::get('/home', [HomeController::class, 'index'])->name('home');

        // address get cities,district route
        Route::get('/address/cities/{provinceId}', [AddressController::class, 'getCities']);
        Route::get('/address/districts/{cityId}', [AddressController::class, 'getDistricts']);

        // Products routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

        // Category routes
        Route::get('/category/{category:slug}', [CategoryController::class, 'show'])->name('products.category');

        // Cart routes
        Route::resource('cart', CartController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        // Cart count (AJAX) - returns JSON {count: int}
        Route::get('cart/count', [CartController::class, 'count'])->name('cart.count');

        // Address routes
        Route::resource('addresses', AddressController::class);


        // Checkout routes
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->name('checkout.pay');
        // Simulation route removed for production
        Route::post('/checkout/notify', [CheckoutController::class, 'notifyPayment'])->name('checkout.notify');
        // Shipping cost route
        Route::post('/checkout/ongkir', [CheckoutController::class, 'calculateOngkir'])->name('checkout.ongkir');
        // Transactions routes (view user's own orders)
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
        // Cancel a user's pending transaction
        Route::post('/transactions/{transaction}/cancel', [TransactionController::class, 'cancel'])->name('transactions.cancel');

        // Show payment page for an existing transaction (continue payment)
        Route::get('/checkout/payment/{transaction}', [CheckoutController::class, 'showPayment'])->name('checkout.payment.show');
        // Switch existing transaction to Midtrans payment (create midtrans payment attempt)
        Route::post('/checkout/payment/switch/{transaction}', [CheckoutController::class, 'switchToMidtrans'])->name('checkout.payment.switch');

        // Profile routes for user (moved here so path becomes /user/profile)
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        // User wishlist routes
        Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');

    });
});

// Admin routes (requires auth and is_admin middleware)
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    // AJAX stats endpoint for live dashboard
    Route::get('/dashboard/stats', [AdminDashboardController::class, 'stats'])->name('dashboard.stats');
    Route::resource('products', AdminProductController::class);
    // Product image management: delete image and set primary
    Route::delete('products/images/{image}', [AdminProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::post('products/{product}/images/{image}/primary', [AdminProductController::class, 'setPrimaryImage'])->name('products.images.setPrimary');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('users', AdminUserController::class)->only(['index', 'edit', 'update', 'destroy']);
    Route::resource('transactions', AdminTransactionController::class)
        ->only(['index', 'show', 'update']);
    // Admin profile routes (handled by main ProfileController)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Allow admin to delete their account via admin route as well
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Webhook route
Route::post('/webhook/midtrans', [WebhookController::class, 'midtrans'])
    ->name('webhook.midtrans')
    ->withoutMiddleware('csrf');

// Midtrans return URL used by client-side redirect after payment completion
Route::get('/checkout/return', [WebhookController::class, 'midtransReturn'])
    ->name('webhook.midtrans.return');

// Auth routes
require __DIR__.'/auth.php';
