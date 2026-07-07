<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\WebsiteActivity;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function dashboard()
    {
        $totalVisits = WebsiteActivity::where('type', 'page_view')->count();
        $totalWhatsappClicks = WebsiteActivity::where('type', 'whatsapp_click')->count();
        $totalProducts = Product::count();
        $totalAvailable = Product::where('ketersediaan_stok', 'tersedia')->count();
        $totalOutOfStock = Product::where('ketersediaan_stok', 'habis')->count();

        $whatsappProducts = WebsiteActivity::with('product')
            ->select('product_id', DB::raw('count(*) as total'))
            ->where('type', 'whatsapp_click')
            ->groupBy('product_id')
            ->orderByDesc('total')
            ->take(5)
            ->get();

        return view('owner.dashboard', compact(
            'totalVisits',
            'totalWhatsappClicks',
            'totalProducts',
            'totalAvailable',
            'totalOutOfStock',
            'whatsappProducts'
        ));
    }
}
