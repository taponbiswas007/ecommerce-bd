<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DeliveryChargeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductPriceController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UnitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

// Frontend Routes
Route::get('/', [ProductController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'shop'])->name('shop');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/category/{slug}', [ProductController::class, 'category'])->name('category.show');

// Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Wishlist Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Checkout Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
});

// Payment Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/process/{order}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{order}', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Review Routes
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');

// Auth Routes (from Breeze)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/overview', [DashboardController::class, 'getOverviewData'])->name('dashboard.overview');
    Route::get('/dashboard/sales-chart', [DashboardController::class, 'getSalesChart'])->name('dashboard.sales-chart');
    Route::get('/dashboard/top-products', [DashboardController::class, 'getTopProducts'])->name('dashboard.top-products');

    // Customers
    Route::resource('customers', CustomerController::class);

    // Units Routes
    Route::resource('units', UnitController::class);
    Route::post('/units/{unit}/status', [UnitController::class, 'updateStatus'])->name('units.update-status');
    Route::post('/units/bulk-action', [UnitController::class, 'bulkAction'])->name('units.bulk-action');
    // Product routes
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('destroy');

        // Bulk actions
        Route::post('/bulk-action', [ProductController::class, 'bulkAction'])->name('bulk-action');
        Route::post('/{product}/status', [ProductController::class, 'updateStatus'])->name('update-status');
        Route::prefix('{product}/images')->name('images.')->group(function () {
            Route::get('/', [ProductImageController::class, 'index'])->name('index');
            Route::get('/create', [ProductImageController::class, 'create'])->name('create');
            Route::post('/', [ProductImageController::class, 'store'])->name('store');
            Route::get('/{image}', [ProductImageController::class, 'show'])->name('show');
            Route::get('/{image}/edit', [ProductImageController::class, 'edit'])->name('edit');
            Route::put('/{image}', [ProductImageController::class, 'update'])->name('update');
            Route::delete('/{image}', [ProductImageController::class, 'destroy'])->name('destroy');

            // Bulk actions
            Route::post('/bulk-action', [ProductImageController::class, 'bulkAction'])->name('bulk-action');

            // AJAX actions
            Route::post('/reorder', [ProductImageController::class, 'reorder'])->name('reorder');
            Route::post('/{image}/set-primary', [ProductImageController::class, 'setPrimary'])->name('set-primary');
            Route::post('/{image}/set-featured', [ProductImageController::class, 'setFeatured'])->name('set-featured');
        });

        Route::prefix('{product}/prices')->name('prices.')->group(function () {
            Route::get('/', [ProductPriceController::class, 'index'])->name('index');
            Route::get('/create', [ProductPriceController::class, 'create'])->name('create');
            Route::post('/', [ProductPriceController::class, 'store'])->name('store');
            Route::get('/{price}', [ProductPriceController::class, 'show'])->name('show');
            Route::get('/{price}/edit', [ProductPriceController::class, 'edit'])->name('edit');
            Route::put('/{price}', [ProductPriceController::class, 'update'])->name('update');
            Route::delete('/{price}', [ProductPriceController::class, 'destroy'])->name('destroy');

            // Bulk actions
            Route::post('/bulk-action', [ProductPriceController::class, 'bulkAction'])->name('bulk-action');
        });
    });

    // Category Routes
    // Categories Routes
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/status', [CategoryController::class, 'updateStatus'])->name('categories.update-status');
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::post('/categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
    Route::get('/categories/data', [CategoryController::class, 'getCategories'])->name('categories.data');
    Route::get('/categories/check-slug', [CategoryController::class, 'checkSlug'])->name('categories.check-slug');
    // Order Routes
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/upload-document', [OrderController::class, 'uploadDocument'])->name('orders.upload-document');

    // Coupon Routes
    Route::resource('coupons', CouponController::class);

    // Delivery Charge Routes
    Route::resource('delivery-charges', DeliveryChargeController::class);

    // Review Management
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

require __DIR__ . '/auth.php';
