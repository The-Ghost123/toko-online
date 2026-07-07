<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get the current user's cart
     */
    public function getCart(): JsonResponse
    {
        $user = Auth::user();
        $cart = Cart::with(['items.product'])->firstOrCreate(['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'items' => $cart->items()->with('product')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                    'subtotal' => $item->subtotal,
                    'product' => [
                        'id' => $item->product->id,
                        'nama_produk' => $item->product->nama_produk,
                        'slug' => $item->product->slug,
                        'harga' => $item->product->harga,
                        'foto_produk' => $item->product->foto_produk,
                        'ketersediaan_stok' => $item->product->ketersediaan_stok,
                    ],
                ];
            }),
            'total_items' => $cart->total_items,
            'total_price' => $cart->total_price,
        ]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan',
            ], 404);
        }

        if ($product->ketersediaan_stok === 'habis') {
            return response()->json([
                'success' => false,
                'message' => 'Produk habis terjual',
            ], 422);
        }

        // Get or create cart for user
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);

        // Check if product already exists in cart
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            // Update quantity if product already in cart
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
            ]);
        } else {
            // Create new cart item
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->harga,
            ]);
        }

        // Update cart totals
        $cart->updateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
            'cart' => [
                'total_items' => $cart->total_items,
                'total_price' => $cart->total_price,
            ],
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateItem(Request $request, CartItem $cartItem): JsonResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Verify the item belongs to the authenticated user
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $cartItem->update([
            'quantity' => $request->quantity,
        ]);

        // Update cart totals
        $cartItem->cart->updateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk berhasil diperbarui',
            'item' => [
                'id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
                'subtotal' => $cartItem->subtotal,
            ],
            'cart' => [
                'total_items' => $cartItem->cart->total_items,
                'total_price' => $cartItem->cart->total_price,
            ],
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeItem(CartItem $cartItem): JsonResponse
    {
        // Verify the item belongs to the authenticated user
        if ($cartItem->cart->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $cart = $cartItem->cart;
        $cartItem->delete();

        // Update cart totals
        $cart->updateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus dari keranjang',
            'cart' => [
                'total_items' => $cart->total_items,
                'total_price' => $cart->total_price,
            ],
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clearCart(): JsonResponse
    {
        $user = Auth::user();
        $cart = Cart::where('user_id', $user->id)->first();

        if ($cart) {
            $cart->items()->delete();
            $cart->updateTotals();
        }

        return response()->json([
            'success' => true,
            'message' => 'Keranjang berhasil dikosongkan',
        ]);
    }

    /**
     * Get abandoned carts (for admin)
     */
    public function getAbandonedCarts(): JsonResponse
    {
        // This endpoint can be restricted to admin role
        $abandonedCarts = Cart::with(['user', 'items.product'])
            ->where('total_items', '>', 0)
            ->whereDate('updated_at', '<', now()->subDays(7)) // Carts not updated in 7 days
            ->get();

        return response()->json([
            'success' => true,
            'carts' => $abandonedCarts,
            'total_abandoned' => $abandonedCarts->count(),
        ]);
    }
}
