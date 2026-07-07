<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ─── CART ROUTES ───────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'getCart'])->name('cart.get');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/item/{cartItem}', [CartController::class, 'updateItem'])->name('cart.update');
    Route::delete('/cart/item/{cartItem}', [CartController::class, 'removeItem'])->name('cart.remove');
    Route::delete('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    
    // Admin routes for abandoned cart analysis
    Route::get('/cart/abandoned', [CartController::class, 'getAbandonedCarts'])
        ->middleware('role:admin')
        ->name('cart.abandoned');
});

