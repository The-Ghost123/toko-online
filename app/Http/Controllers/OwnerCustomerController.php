<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OwnerCustomerController extends Controller
{
    public function index()
    {
        $customers = User::where('role', 'customer')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('owner.customers.index', compact('customers'));
    }

    public function toggleBan(User $customer): RedirectResponse
    {
        if (! $customer->isCustomer()) {
            abort(404);
        }

        $customer->update([
            'is_banned' => ! $customer->is_banned,
        ]);

        $message = $customer->is_banned
            ? 'Pembeli berhasil dibanned.'
            : 'Pembeli berhasil diaktifkan kembali.';

        return redirect()->route('owner.customers.index')->with('success', $message);
    }

    public function destroy(User $customer): RedirectResponse
    {
        if (! $customer->isCustomer()) {
            abort(404);
        }

        $customer->delete();

        return redirect()->route('owner.customers.index')->with('success', 'Pembeli berhasil dihapus.');
    }
}
