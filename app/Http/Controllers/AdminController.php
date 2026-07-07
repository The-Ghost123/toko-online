<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\WebsiteActivity;

class AdminController extends Controller
{
    public function dashboard()
    {
        $activeCartQuery = Cart::where('total_items', '>', 0)
            ->where('updated_at', '>=', now()->subMinutes(30));

        return view('admin.dashboard', [
            'totalProducts'       => Product::count(),
            'totalCategories'     => Category::count(),
            'tersedia'            => Product::where('ketersediaan_stok', 'tersedia')->count(),
            'habis'               => Product::where('ketersediaan_stok', 'habis')->count(),
            'totalVisits'         => WebsiteActivity::where('type', 'page_view')->count(),
            'totalWhatsappClicks' => WebsiteActivity::where('type', 'whatsapp_click')->count(),
            'totalActiveCarts'    => $activeCartQuery->count(),
            'activeCartValue'     => $activeCartQuery->sum('total_price'),
            'activeCustomers'     => $activeCartQuery->distinct('user_id')->count('user_id'),
            'pendingPayments'     => Order::where('status', Order::STATUS_PAYMENT_SUBMITTED)->count(),
            'activeCarts'         => Cart::with(['user', 'items.product'])
                ->where('total_items', '>', 0)
                ->where('updated_at', '>=', now()->subMinutes(30))
                ->latest('updated_at')
                ->take(10)
                ->get(),
            'latestProducts'      => Product::with('category')->latest()->take(5)->get(),
        ]);
    }
}