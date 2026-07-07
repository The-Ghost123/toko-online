<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentSettingsController extends Controller
{
    public function edit()
    {
        $paymentSettings = [
            'payment_bank_name' => Setting::getValue('payment_bank_name', 'Bank BCA'),
            'payment_bank_account' => Setting::getValue('payment_bank_account', '1234567890'),
            'payment_bank_account_name' => Setting::getValue('payment_bank_account_name', 'FashionStore'),
            'payment_ewallet_provider' => Setting::getValue('payment_ewallet_provider', 'DANA / GoPay / OVO'),
            'payment_ewallet_number' => Setting::getValue('payment_ewallet_number', '081234567890'),
            'payment_qris_image' => Setting::getValue('payment_qris_image', null),
        ];

        return view('admin.payment-settings.edit', compact('paymentSettings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'payment_bank_name' => 'nullable|string|max:255',
            'payment_bank_account' => 'nullable|string|max:255',
            'payment_bank_account_name' => 'nullable|string|max:255',
            'payment_ewallet_provider' => 'nullable|string|max:255',
            'payment_ewallet_number' => 'nullable|string|max:255',
            'payment_qris_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        Setting::setValue('payment_bank_name', $validated['payment_bank_name'] ?? '');
        Setting::setValue('payment_bank_account', $validated['payment_bank_account'] ?? '');
        Setting::setValue('payment_bank_account_name', $validated['payment_bank_account_name'] ?? '');
        Setting::setValue('payment_ewallet_provider', $validated['payment_ewallet_provider'] ?? '');
        Setting::setValue('payment_ewallet_number', $validated['payment_ewallet_number'] ?? '');

        if ($request->hasFile('payment_qris_image')) {
            $path = $request->file('payment_qris_image')->store('payment_qris', 'public');
            Setting::setValue('payment_qris_image', $path);
        }

        return redirect()->route('admin.payment-settings.edit')->with('success', 'Informasi pembayaran berhasil diperbarui.');
    }
}
