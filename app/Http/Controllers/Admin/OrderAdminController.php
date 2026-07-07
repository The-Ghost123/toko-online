<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class OrderAdminController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'items'])->latest()->get();

        return view('admin.orders.index', compact('orders'));
    }

    public function map()
    {
        $orders = Order::with(['user', 'items'])
            ->whereNotNull('shipping_latitude')
            ->whereNotNull('shipping_longitude')
            ->latest()
            ->get();

        return view('admin.orders.map', compact('orders'));
    }

    public function verifyPayment(Order $order)
    {
        if ($order->status !== Order::STATUS_PAYMENT_SUBMITTED) {
            return Redirect::back()->with('error', 'Order tidak berada pada status Payment Submitted.');
        }

        $order->update(['status' => Order::STATUS_PAYMENT_VERIFIED]);

        return Redirect::back()->with('success', 'Bukti pembayaran telah diverifikasi.');
    }

    public function notifications()
    {
        $pendingOrders = Order::with('user')
            ->where('status', Order::STATUS_PAYMENT_SUBMITTED)
            ->latest()
            ->take(5)
            ->get();

        $alerts = $pendingOrders->map(function (Order $order) {
            $firstItem = $order->items->first();
            $productLabel = $firstItem ? $firstItem->product_name : "pesanan";
            if ($order->items->count() > 1) {
                $productLabel = "$productLabel dan " . ($order->items->count() - 1) . " item lainnya";
            }

            return [
                'key' => "order_{$order->id}_payment_submitted",
                'message' => "Pesanan $productLabel menunggu verifikasi pembayaran.",
                'customer' => $order->user->name,
                'created_at' => $order->created_at->format('d M Y H:i'),
            ];
        });

        return response()->json([
            'count' => $pendingOrders->count(),
            'alerts' => $alerts,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'items']);
        return view('admin.orders.show', compact('order'));
    }

    public function ship(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number' => 'required|string|max:255',
        ]);

        if (! in_array($order->status, [Order::STATUS_PAYMENT_VERIFIED, Order::STATUS_PROCESSING], true)) {
            return Redirect::back()->with('error', 'Order harus dalam status Payment Verified atau Processing untuk dikirim.');
        }

        $order->update([
            'tracking_number' => $request->tracking_number,
            'status' => Order::STATUS_SHIPPED,
        ]);

        return Redirect::back()->with('success', 'Nomor resi berhasil disimpan dan pesanan diubah menjadi Shipped.');
    }
}
