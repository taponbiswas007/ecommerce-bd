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
use App\Http\Controllers\Admin\VatAitController;
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
use App\Http\Controllers\ChatController;

/*
|--------------------------------------------------------------------------
| Public Routes (Accessible to everyone: guests, customers, admins)
|--------------------------------------------------------------------------
*/

Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/products', [FrontendController::class, 'shop'])->name('shop');
Route::get('/products/{slug}', [FrontendController::class, 'show'])->name('product.show');

// Public endpoint for upazilas (used by admin UI AJAX to avoid auth issues)
Route::get('/delivery-charges/upazilas', [\App\Http\Controllers\Admin\DeliveryChargeController::class, 'upazilas'])->name('delivery-charges.upazilas.public');

// Shipping estimate (used by checkout to re-calc when transport/company changes)
Route::get('/shipping/estimate', [\App\Http\Controllers\CheckoutController::class, 'estimate'])->name('shipping.estimate');
Route::get('/category/{category:slug}', [FrontendController::class, 'categoryShow'])->name('category.show');
Route::get('/product/quick-view/{hashid}', [FrontendController::class, 'quickView'])->name('product.quick-view');
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
Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{hashid}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{hashid}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Product Reviews (Public viewing, authenticated posting)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');

/*
|--------------------------------------------------------------------------
| Customer Only Routes (Authenticated users with 'customer' role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::post('/check-product-attribute', [App\Http\Controllers\FrontendController::class, 'checkProductAttribute']);
    // Wishlist Routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add/{hashid}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::post('/wishlist/remove/{hashid}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/toggle/{hashid}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

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

    // Customer Orders (with 'customer.' prefix for blade usage)
    Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('customer.orders.index');
    // Also register as 'orders.index' for compatibility
    Route::get('/orders', [App\Http\Controllers\Customer\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [App\Http\Controllers\Customer\OrderController::class, 'show'])->name('customer.orders.show');
    Route::get('/orders/{order}/tracking', [App\Http\Controllers\Customer\OrderController::class, 'tracking'])->name('customer.orders.tracking');
    Route::get('/orders/{order}/document/{historyId}', [App\Http\Controllers\Customer\OrderController::class, 'downloadDocument'])->name('customer.orders.download-document');

    // Customer Addresses
    Route::resource('addresses', App\Http\Controllers\Customer\AddressController::class);
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon']);
    Route::post('/cart/remove-coupon', [CartController::class, 'removeCoupon']);
});

/*
|--------------------------------------------------------------------------
| Admin Only Routes (Authenticated users with 'admin' role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Ads Management
    Route::resource('ads', \App\Http\Controllers\Admin\AdController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/overview', [DashboardController::class, 'getOverviewData'])->name('dashboard.overview');
    Route::get('/dashboard/sales-chart', [DashboardController::class, 'getSalesChart'])->name('dashboard.sales-chart');
    Route::get('/dashboard/top-products', [DashboardController::class, 'getTopProducts'])->name('dashboard.top-products');

    // Admin Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Customers Management
    Route::resource('customers', CustomerController::class);

    // Units Management
    Route::resource('units', UnitController::class);
    Route::post('/units/{unit}/status', [UnitController::class, 'updateStatus'])->name('units.update-status');
    Route::post('/units/bulk-action', [UnitController::class, 'bulkAction'])->name('units.bulk-action');

    // Product Management
    Route::prefix('products')->name('products.')->group(function () {
        // Trashed products (soft deleted)
        Route::get('/trash', [ProductController::class, 'trash'])->name('trash');
        Route::delete('/{id}/force-delete', [ProductController::class, 'forceDelete'])->name('forceDelete');
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

    // Trashed categories
    Route::get('/categories-trashed', [CategoryController::class, 'trashed'])->name('categories.trashed');
    Route::post('/categories/{id}/restore', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/{id}/force-delete', [CategoryController::class, 'forceDelete'])->name('categories.force-delete');
    Route::post('/categories/bulk-restore', [CategoryController::class, 'bulkRestore'])->name('categories.bulk-restore');
    Route::post('/categories/bulk-force-delete', [CategoryController::class, 'bulkForceDelete'])->name('categories.bulk-force-delete');

    // Brand Management
    Route::resource('brands', \App\Http\Controllers\Admin\BrandController::class);
    Route::post('/brands/{id}/status', [\App\Http\Controllers\Admin\BrandController::class, 'updateStatus'])->name('brands.update-status')->where('id', '[0-9]+');
    Route::post('/brands/reorder', [\App\Http\Controllers\Admin\BrandController::class, 'reorder'])->name('brands.reorder');
    Route::post('/brands/bulk-action', [\App\Http\Controllers\Admin\BrandController::class, 'bulkAction'])->name('brands.bulk-action');

    // Trashed brands
    Route::get('/brands-trashed', [\App\Http\Controllers\Admin\BrandController::class, 'trashed'])->name('brands.trashed');
    Route::post('/brands/{id}/restore', [\App\Http\Controllers\Admin\BrandController::class, 'restore'])->name('brands.restore');
    Route::delete('/brands/{id}/force-delete', [\App\Http\Controllers\Admin\BrandController::class, 'forceDelete'])->name('brands.force-delete');

    // Admin Order Management
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::post('orders/{order}/update-status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('orders/{order}/upload-document', [\App\Http\Controllers\Admin\OrderController::class, 'uploadDocument'])->name('orders.upload-document');
    Route::get('orders/{order}/tracking-history', [\App\Http\Controllers\Admin\OrderController::class, 'trackingHistory'])->name('orders.tracking-history');

    // Coupon Management
    Route::get('coupons/generate-code', [CouponController::class, 'generateCode'])->name('coupons.generate-code');
    Route::resource('coupons', CouponController::class);

    // Delivery Charge Management
    Route::resource('delivery-charges', DeliveryChargeController::class);
    // AJAX route to get upazilas for a district
    Route::get('delivery-charges/upazilas', [DeliveryChargeController::class, 'upazilas'])->name('delivery-charges.upazilas');

    // Transport companies and package rates management (admin)
    Route::resource('transport-companies', \App\Http\Controllers\Admin\TransportCompanyController::class);
    Route::resource('package-rates', \App\Http\Controllers\Admin\PackageRateController::class);
    Route::resource('packaging-rules', \App\Http\Controllers\Admin\PackagingRuleController::class);
    Route::resource('shop-to-transport-rates', \App\Http\Controllers\Admin\ShopToTransportRateController::class);

    // VAT & AIT Management
    Route::prefix('vat-ait')->name('vat-ait.')->group(function () {
        Route::get('/', [VatAitController::class, 'index'])->name('index');
        Route::post('/update-settings', [VatAitController::class, 'updateSettings'])->name('update-settings');

        Route::get('/products', [VatAitController::class, 'productTaxes'])->name('products');
        Route::get('/products/{product}/edit', [VatAitController::class, 'editProductTax'])->name('edit-product');
        Route::post('/products/{product}/update', [VatAitController::class, 'updateProductTax'])->name('update-product');
        Route::post('/products/{product}/remove', [VatAitController::class, 'removeProductTax'])->name('remove-product');
        Route::post('/products/bulk-update', [VatAitController::class, 'bulkUpdateProductTax'])->name('bulk-update');
        Route::get('/products/search', [VatAitController::class, 'searchProductTax'])->name('search');
        Route::get('/products/export', [VatAitController::class, 'exportProductTax'])->name('export');

        Route::get('/history', [VatAitController::class, 'history'])->name('history');
        Route::get('/report', [VatAitController::class, 'report'])->name('report');
    });

    // Review Management
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('/reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\Admin\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Settings Management
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
});

/*
|--------------------------------------------------------------------------
| Chat Routes (Authenticated Users - Both Customer & Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->prefix('chat')->name('chat.')->group(function () {
    Route::get('/get-or-create', [App\Http\Controllers\ChatController::class, 'getOrCreateChat'])->name('get-or-create');
    Route::get('/all', [App\Http\Controllers\ChatController::class, 'getAllChats'])->name('all');
    Route::get('/{chat}/messages', [App\Http\Controllers\ChatController::class, 'getMessages'])->name('messages');
    Route::post('/{chat}/send', [App\Http\Controllers\ChatController::class, 'sendMessage'])->name('send');
    Route::get('/unread-count', [App\Http\Controllers\ChatController::class, 'getUnreadCount'])->name('unread-count');
    Route::post('/{chat}/mark-read', [App\Http\Controllers\ChatController::class, 'markAsRead'])->name('mark-read');
});

/*
|--------------------------------------------------------------------------
| Auth Routes (From Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
