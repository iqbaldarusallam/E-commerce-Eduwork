<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    if (auth('web')->check()) {
        // Redirect berdasarkan role: admin ke admin.dashboard, user ke user.dashboard
        if (auth('web')->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Alias routes untuk backward compatibility
Route::middleware(['auth'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');
});

// User authenticated routes
Route::middleware(['auth'])->group(function () {
    // Global dashboard route: redirect based on user role (admin -> admin.dashboard, user -> user.dashboard)
    Route::get('/dashboard', function () {
        if (auth('web')->check() && auth('web')->user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
    })->name('dashboard');
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

        // Products routes
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product:slug}', [ProductController::class, 'show'])->name('products.show');

        // Cart routes
        Route::resource('cart', \App\Http\Controllers\User\CartController::class)
            ->only(['index', 'store', 'update', 'destroy']);
        // Cart count (AJAX) - returns JSON {count: int}
        Route::get('cart/count', [\App\Http\Controllers\User\CartController::class, 'count'])->name('cart.count');

        // Address routes
        Route::resource('addresses', \App\Http\Controllers\User\AddressController::class);

        // Checkout routes
        Route::get('/checkout', [\App\Http\Controllers\User\CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/pay', [\App\Http\Controllers\User\CheckoutController::class, 'pay'])->name('checkout.pay');

        // Transactions routes (view user's own orders)
        Route::get('/transactions/{transaction}', [\App\Http\Controllers\User\TransactionController::class, 'show'])->name('transactions.show');

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
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    // Product image management: delete image and set primary
    Route::delete('products/images/{image}', [\App\Http\Controllers\Admin\ProductController::class, 'destroyImage'])->name('products.images.destroy');
    Route::post('products/{product}/images/{image}/primary', [\App\Http\Controllers\Admin\ProductController::class, 'setPrimaryImage'])->name('products.images.setPrimary');
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('transactions', \App\Http\Controllers\Admin\TransactionController::class)
        ->only(['index', 'show', 'update']);
    // Admin profile routes (handled by main ProfileController)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    // Allow admin to delete their account via admin route as well
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Webhook route
Route::post('/webhook/midtrans', [\App\Http\Controllers\WebhookController::class, 'midtrans'])
    ->name('webhook.midtrans')
    ->withoutMiddleware('csrf');

// Auth routes
require __DIR__ . '/auth.php';
