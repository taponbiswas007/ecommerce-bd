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
use App\Http\Controllers\Auth\SocialLoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;

/*
|--------------------------------------------------------------------------
| Public Routes (Accessible to everyone: guests, customers, admins)
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/products', [FrontendController::class, 'shop'])->name('shop');
Route::get('/products/{slug}', [FrontendController::class, 'show'])->name('product.show');
Route::get('/category/{category:slug}', [FrontendController::class, 'categoryShow'])->name('category.show');
Route::get('/product/quick-view/{id}', [FrontendController::class, 'quickView'])->name('product.quick-view');
Route::get('/flash-sale', [FrontendController::class, 'flashSale'])->name('flash-sale');
Route::get('/new-arrivals', [FrontendController::class, 'newArrivals'])->name('new-arrivals');
// Static Page Routes
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/offers', [PageController::class, 'offers'])->name('offers');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Social Login Routes
Route::get('/auth/google', [SocialLoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [SocialLoginController::class, 'handleGoogleCallback']);
Route::get('/auth/facebook', [SocialLoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('/auth/facebook/callback', [SocialLoginController::class, 'handleFacebookCallback']);


// Cart Routes (Public - with authentication check inside controller)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Product Reviews (Public viewing, authenticated posting)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', action: [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth:customer');

/*
|--------------------------------------------------------------------------
| Customer Only Routes (Authenticated users with 'customer' role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {

    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{product}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove/{product}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Checkout & Payment Routes (Buying flow)
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');

    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/process/{order}', [PaymentController::class, 'process'])->name('payment.process');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel/{order}', [PaymentController::class, 'cancel'])->name('payment.cancel');

    // Customer Dashboard
    Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');


    // Customer Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customer Orders
    Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('orders.show');

    // Customer Addresses
    Route::resource('addresses', App\Http\Controllers\Customer\AddressController::class);
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes (Authenticated users with 'admin' role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/overview', [DashboardController::class, 'getOverviewData'])->name('dashboard.overview');
    Route::get('/dashboard/sales-chart', [DashboardController::class, 'getSalesChart'])->name('dashboard.sales-chart');
    Route::get('/dashboard/top-products', [DashboardController::class, 'getTopProducts'])->name('dashboard.top-products');

    // Customers Management
    Route::resource('customers', CustomerController::class);

    // Units Management
    Route::resource('units', UnitController::class);
    Route::post('/units/{unit}/status', [UnitController::class, 'updateStatus'])->name('units.update-status');
    Route::post('/units/bulk-action', [UnitController::class, 'bulkAction'])->name('units.bulk-action');

    // Product Management
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

        // Product Images
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

        // Product Prices
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

    // Category Management
    Route::resource('categories', CategoryController::class);
    Route::post('/categories/{category}/status', [CategoryController::class, 'updateStatus'])->name('categories.update-status');
    Route::post('/categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::post('/categories/bulk-action', [CategoryController::class, 'bulkAction'])->name('categories.bulk-action');
    Route::get('/categories/data', [CategoryController::class, 'getCategories'])->name('categories.data');
    Route::get('/categories/check-slug', [CategoryController::class, 'checkSlug'])->name('categories.check-slug');

    // Order Management
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/upload-document', [OrderController::class, 'uploadDocument'])->name('orders.upload-document');

    // Coupon Management
    Route::resource('coupons', CouponController::class);

    // Delivery Charge Management
    Route::resource('delivery-charges', DeliveryChargeController::class);

    // Review Management
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');

    // Settings Management
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (From Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
