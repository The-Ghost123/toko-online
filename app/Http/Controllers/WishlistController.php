<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggle(Product $product): JsonResponse
    {
        $user = auth()->user();
        
        $wishlist = Wishlist::where('user_id', $user->id)
                             ->where('product_id', $product->id)
                             ->first();

        if ($wishlist) {
            $wishlist->delete();
            return response()->json(['status' => 'removed', 'message' => 'Produk dihapus dari wishlist']);
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            return response()->json(['status' => 'added', 'message' => 'Produk ditambahkan ke wishlist']);
        }
    }

    public function check(Product $product): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json(['wishlisted' => false]);
        }

        $wishlisted = Wishlist::where('user_id', auth()->id())
                               ->where('product_id', $product->id)
                               ->exists();

        return response()->json(['wishlisted' => $wishlisted]);
    }

    public function index()
    {
        $wishlists = auth()->user()->wishlists()->with('product')->latest()->paginate(12);
        return view('wishlist.index', compact('wishlists'));
    }
}
