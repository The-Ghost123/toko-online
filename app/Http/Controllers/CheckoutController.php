<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::with(['items.product'])->firstOrCreate(['user_id' => $user->id]);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong. Tambahkan produk terlebih dahulu.');
        }

        $selectedItemIds = array_filter(array_map('intval', (array) $request->query('items', [])));
        $checkoutItems = $cart->items;

        if (!empty($selectedItemIds)) {
            $checkoutItems = $cart->items->filter(function ($item) use ($selectedItemIds) {
                return in_array($item->id, $selectedItemIds, true);
            });
        }

        if ($checkoutItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Pilih minimal satu barang untuk checkout.');
        }

        $checkoutTotalItems = $checkoutItems->sum('quantity');
        $checkoutTotalPrice = $checkoutItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        return view('public.checkout', [
            'cart' => $cart,
            'checkoutItems' => $checkoutItems,
            'checkoutTotalItems' => $checkoutTotalItems,
            'checkoutTotalPrice' => $checkoutTotalPrice,
            'paymentMethods' => [
                Order::PAYMENT_METHOD_BANK => 'Bank Transfer',
                Order::PAYMENT_METHOD_EWALLET => 'E-Wallet',
                Order::PAYMENT_METHOD_QRIS => 'QRIS',
            ],
            'paymentSettings' => [
                'bank_name' => Setting::getValue('payment_bank_name', 'Bank BCA'),
                'bank_account' => Setting::getValue('payment_bank_account', '1234567890'),
                'bank_account_name' => Setting::getValue('payment_bank_account_name', 'FashionStore'),
                'ewallet_provider' => Setting::getValue('payment_ewallet_provider', 'DANA / GoPay / OVO'),
                'ewallet_number' => Setting::getValue('payment_ewallet_number', '081234567890'),
                'qris_image' => Setting::getValue('payment_qris_image', null),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:' . implode(',', [Order::PAYMENT_METHOD_BANK, Order::PAYMENT_METHOD_EWALLET, Order::PAYMENT_METHOD_QRIS]),
            'proof_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
            'shipping_address' => 'required|string|max:1000',
            'shipping_latitude' => 'nullable|numeric|between:-90,90',
            'shipping_longitude' => 'nullable|numeric|between:-180,180',
            'selected_items' => 'nullable|array',
            'selected_items.*' => 'integer',
        ]);

        $user = Auth::user();
        $cart = Cart::with(['items.product'])->firstOrCreate(['user_id' => $user->id]);

        if ($cart->items->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Keranjang Anda kosong. Tambahkan produk terlebih dahulu.');
        }

        $proofPath = $request->file('proof_photo')->store('payment_proofs', 'public');
        $selectedItemIds = array_filter(array_map('intval', (array) $request->input('selected_items', [])));
        $selectedCartItems = $cart->items()->whereIn('id', $selectedItemIds)->with('product')->get();

        if ($selectedCartItems->isEmpty()) {
            return redirect()->route('cart')->with('error', 'Pilih minimal satu barang untuk checkout.');
        }

        $totalItems = $selectedCartItems->sum('quantity');
        $totalPrice = $selectedCartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        DB::transaction(function () use ($cart, $request, $proofPath, $user, $selectedCartItems, $totalItems, $totalPrice, $selectedItemIds) {
            $order = Order::create([
                'user_id' => $user->id,
                'payment_method' => $request->payment_method,
                'proof_photo' => $proofPath,
                'status' => Order::STATUS_PAYMENT_SUBMITTED,
                'total_items' => $totalItems,
                'total_price' => $totalPrice,
                'shipping_address' => $request->shipping_address,
                'shipping_latitude' => $request->shipping_latitude,
                'shipping_longitude' => $request->shipping_longitude,
            ]);

            foreach ($selectedCartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->nama_produk,
                    'product_photo' => $item->product->foto_produk,
                    'quantity' => $item->quantity,
                    'price' => $item->price,
                ]);
            }

            $cart->items()->whereIn('id', $selectedItemIds)->delete();
            $cart->updateTotals();
        });

        return Redirect::route('orders.index')->with('success', 'Pesanan berhasil dibuat. Tunggu konfirmasi pembayaran dari admin.');
    }
}
