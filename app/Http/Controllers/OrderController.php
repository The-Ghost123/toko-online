<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()
            ->orders()
            ->with(['items.product'])
            ->latest()
            ->get();

        return view('public.orders', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'items.review']);

        return view('public.orders.show', compact('order'));
    }

    public function complete(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== Order::STATUS_SHIPPED) {
            return back()->with('error', 'Pesanan hanya dapat ditandai selesai setelah dikirim.');
        }

        $order->status = Order::STATUS_COMPLETED;
        $order->save();

        return back()->with('success', 'Pesanan berhasil ditandai selesai.');
    }

    public function refund(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (in_array($order->status, [Order::STATUS_COMPLETED, Order::STATUS_REFUNDED], true)) {
            return back()->with('error', 'Refund tidak dapat diajukan untuk status saat ini.');
        }

        $order->status = Order::STATUS_REFUNDED;
        $order->save();

        return back()->with('success', 'Permintaan refund berhasil diajukan.');
    }

    public function cancel(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if (! $order->canBeCancelled()) {
            return back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah dikirim, selesai, refund, atau sudah dibatalkan.');
        }

        $order->status = Order::STATUS_CANCELLED;
        $order->save();

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function notifications()
    {
        $orders = Auth::user()
            ->orders()
            ->whereIn('status', [
                Order::STATUS_PAYMENT_SUBMITTED,
                Order::STATUS_PAYMENT_VERIFIED,
                Order::STATUS_SHIPPED,
            ])
            ->latest()
            ->get();

        $alerts = $orders->map(function (Order $order) {
            $firstItem = $order->items->first();
            $productLabel = $firstItem ? $firstItem->product_name : "pesanan";
            if ($order->items->count() > 1) {
                $productLabel = "$productLabel dan " . ($order->items->count() - 1) . " item lainnya";
            }

            if ($order->status === Order::STATUS_PAYMENT_SUBMITTED) {
                return [
                    'key' => "order_{$order->id}_submitted",
                    'message' => "Pesanan $productLabel berhasil dibuat. Bukti pembayaran sedang menunggu verifikasi.",
                ];
            }

            if ($order->status === Order::STATUS_PAYMENT_VERIFIED) {
                return [
                    'key' => "order_{$order->id}_verified",
                    'message' => "Pembayaran pesanan $productLabel sudah diverifikasi oleh admin.",
                ];
            }

            if ($order->status === Order::STATUS_SHIPPED) {
                return [
                    'key' => "order_{$order->id}_shipped",
                    'message' => "Pesanan $productLabel sedang dalam pengiriman.",
                ];
            }

            return null;
        })->filter()->values();

        return response()->json([
            'count' => $alerts->count(),
            'alerts' => $alerts,
        ]);
    }
}
