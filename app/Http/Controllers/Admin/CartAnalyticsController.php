<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use Illuminate\Http\Request;

class CartAnalyticsController extends Controller
{
    private const ABANDONED_DAYS = 7;

    /**
     * Show abandoned carts dashboard
     */
    public function abandonedCarts()
    {
        $abandonedBefore = now()->subDays(self::ABANDONED_DAYS);

        $abandonedCarts = Cart::with(['user', 'items.product'])
            ->where('total_items', '>', 0)
            ->where('updated_at', '<', $abandonedBefore)
            ->orderBy('total_price', 'desc')
            ->paginate(20);

        $totalAbandonedValue = Cart::where('total_items', '>', 0)
            ->where('updated_at', '<', $abandonedBefore)
            ->sum('total_price');

        $totalAbandonedCount = Cart::where('total_items', '>', 0)
            ->where('updated_at', '<', $abandonedBefore)
            ->count();

        return view('admin.carts.abandoned', [
            'carts' => $abandonedCarts,
            'totalCount' => $totalAbandonedCount,
            'totalValue' => $totalAbandonedValue,
            'abandonedDays' => self::ABANDONED_DAYS,
        ]);
    }

    /**
     * Show cart recovery insights
     */
    public function cartInsights()
    {
        // Carts created in last 30 days
        $cartsLast30Days = Cart::where('total_items', '>', 0)
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();

        $completedCarts = Order::where('created_at', '>=', now()->subDays(30))->count();

        // Average cart value
        $avgCartValue = Cart::where('total_items', '>', 0)
            ->avg('total_price');

        // Most frequently carted products
        $popularCartProducts = CartItem::selectRaw('product_id, COUNT(*) as cart_count, SUM(quantity) as total_qty, SUM(price * quantity) as total_value')
            ->groupBy('product_id')
            ->orderByDesc('cart_count')
            ->with('product')
            ->limit(10)
            ->get();

        // Customers with most abandoned carts
        $frequentAbandoners = Cart::with('user')
            ->where('total_items', '>', 0)
            ->where('updated_at', '<', now()->subDays(self::ABANDONED_DAYS))
            ->selectRaw('user_id, COUNT(*) as count, SUM(total_price) as value')
            ->groupBy('user_id')
            ->orderByDesc('count')
            ->limit(10)
            ->get();

        return view('admin.carts.insights', [
            'cartsLast30Days' => $cartsLast30Days,
            'completedCarts' => $completedCarts,
            'avgCartValue' => $avgCartValue,
            'popularCartProducts' => $popularCartProducts,
            'frequentAbandoners' => $frequentAbandoners,
            'abandonedDays' => self::ABANDONED_DAYS,
        ]);
    }

    /**
     * Get abandoned cart details
     */
    public function showAbandonedCart(Cart $cart)
    {
        $cart->load(['user', 'items.product']);

        return view('admin.carts.show', [
            'cart' => $cart,
            'daysSinceUpdate' => now()->diffInDays($cart->updated_at),
        ]);
    }

    /**
     * Get active carts data for admin dashboard refresh
     */
    public function activeCartsData()
    {
        $carts = Cart::with(['user', 'items.product'])
            ->where('total_items', '>', 0)
            ->where('updated_at', '>=', now()->subMinutes(30))
            ->latest('updated_at')
            ->take(20)
            ->get()
            ->map(function ($cart) {
                return [
                    'id' => $cart->id,
                    'user' => [
                        'name' => $cart->user->name,
                        'email' => $cart->user->email,
                    ],
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total_price,
                    'updated_at' => $cart->updated_at->format('d M Y H:i'),
                    'items' => $cart->items->map(function ($item) {
                        return [
                            'product_name' => $item->product->nama_produk,
                            'quantity' => $item->quantity,
                        ];
                    }),
                ];
            });

        return response()->json([
            'success' => true,
            'carts' => $carts,
            'active_count' => $carts->count(),
            'active_customers' => $carts->pluck('user.email')->unique()->count(),
            'total_value' => $carts->sum('total_price'),
        ]);
    }

    /**
     * Send reminder email (future enhancement)
     */
    public function sendReminder(Cart $cart)
    {
        $message = "Reminder untuk {$cart->user->email} berhasil dicatat. Fitur kirim email otomatis belum dikonfigurasi.";

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->back()->with('success', $message);
    }
}
