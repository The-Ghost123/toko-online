<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\OwnerCustomerController;
use App\Http\Controllers\OwnerOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SocialMediaController as AdminSocialMediaController;
use App\Http\Controllers\Admin\OrderAdminController;
use App\Http\Controllers\Admin\CartAnalyticsController;
use App\Http\Controllers\Admin\PaymentSettingsController;
use App\Http\Controllers\ProductFeedbackController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// ─── PUBLIC ROUTES ───────────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('home');
    Route::get('/products', [ProductController::class, 'products'])->name('products');
    Route::get('/products/{product}/whatsapp', [ProductController::class, 'redirectToWhatsapp'])->name('products.whatsapp');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/about', [ProductController::class, 'about'])->name('about');
    Route::get('/cart', function () {
        return view('public.cart');
    })->name('cart');

    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->whereNumber('order')->name('orders.show');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->whereNumber('order')->name('orders.complete');
    Route::post('/orders/{order}/refund', [OrderController::class, 'refund'])->whereNumber('order')->name('orders.refund');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->whereNumber('order')->name('orders.cancel');
    Route::post('/orders/{order}/items/{orderItem}/review', [ReviewController::class, 'store'])->whereNumber('order')->whereNumber('orderItem')->name('orders.items.review.store');
    Route::get('/notifications', [OrderController::class, 'notifications'])->name('notifications.index');
});

// ─── OWNER ROUTES ───────────────────────────────────────────────
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');
    Route::get('/product-feedback', [ProductFeedbackController::class, 'index'])->name('product-feedback.index');
    Route::get('/customers', [OwnerCustomerController::class, 'index'])->name('customers.index');
    Route::post('/customers/{customer}/ban', [OwnerCustomerController::class, 'toggleBan'])->name('customers.ban');
    Route::delete('/customers/{customer}', [OwnerCustomerController::class, 'destroy'])->name('customers.destroy');
    Route::get('/orders', [\App\Http\Controllers\Owner\OrderOwnerController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Owner\OrderOwnerController::class, 'show'])->whereNumber('order')->name('orders.show');
    Route::delete('/orders/{order}', [\App\Http\Controllers\Owner\OrderOwnerController::class, 'destroy'])->whereNumber('order')->name('orders.destroy');
    Route::resource('products', App\Http\Controllers\Owner\ProductController::class);
    Route::resource('categories', App\Http\Controllers\Owner\CategoryController::class);
    Route::resource('reports', App\Http\Controllers\Owner\ReportController::class)->only(['index']);
    Route::resource('settings', App\Http\Controllers\Owner\SettingController::class)->only(['index']);
    Route::get('social-media', [AdminSocialMediaController::class, 'edit'])->name('social-media.edit');
    Route::put('social-media', [AdminSocialMediaController::class, 'update'])->name('social-media.update');
    Route::get('pages/about', [AdminPageController::class, 'edit'])->name('pages.about.edit');
    Route::put('pages/about', [AdminPageController::class, 'update'])->name('pages.about.update');
});

// ─── ADMIN ROUTES ────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Redirect '/admin' root to the admin dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/carts/insights', [CartAnalyticsController::class, 'cartInsights'])->name('carts.insights');
    Route::get('/carts/abandoned', [CartAnalyticsController::class, 'abandonedCarts'])->name('carts.abandoned');
    Route::get('/carts/{cart}', [CartAnalyticsController::class, 'showAbandonedCart'])->name('carts.show');
    Route::post('/carts/{cart}/remind', [CartAnalyticsController::class, 'sendReminder'])->name('carts.remind');
    Route::get('/carts/active-data', [CartAnalyticsController::class, 'activeCartsData'])->name('carts.active.data');
    Route::get('/orders', [OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/map', [OrderAdminController::class, 'map'])->name('orders.map');
    Route::get('/orders/notifications', [OrderAdminController::class, 'notifications'])->name('orders.notifications');
    // show single order (admin)
    Route::get('/orders/{order}', [OrderAdminController::class, 'show'])->whereNumber('order')->name('orders.show');
    Route::post('/orders/{order}/verify-payment', [OrderAdminController::class, 'verifyPayment'])->name('orders.verify');
    Route::post('/orders/{order}/ship', [OrderAdminController::class, 'ship'])->name('orders.ship');
    Route::get('/payment-settings', [PaymentSettingsController::class, 'edit'])->name('payment-settings.edit');
    Route::put('/payment-settings', [PaymentSettingsController::class, 'update'])->name('payment-settings.update');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::get('product-feedback', [ProductFeedbackController::class, 'index'])->name('product-feedback.index');
    Route::get('pages/about', [AdminPageController::class, 'edit'])->name('pages.about.edit');
    Route::put('pages/about', [AdminPageController::class, 'update'])->name('pages.about.update');
});

require __DIR__.'/auth.php';
