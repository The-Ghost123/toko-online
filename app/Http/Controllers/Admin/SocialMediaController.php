<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function edit()
    {
        $socialLinks = [
            'social_instagram' => Setting::getValue('social_instagram', 'https://instagram.com'),
            'social_facebook'  => Setting::getValue('social_facebook', 'https://facebook.com'),
            'social_twitter'   => Setting::getValue('social_twitter', 'https://twitter.com'),
            'social_whatsapp'  => Setting::getValue('social_whatsapp', 'https://wa.me/6281234567890'),
        ];

        return view('admin.social-media.edit', compact('socialLinks'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'social_instagram' => 'nullable|url',
            'social_facebook'  => 'nullable|url',
            'social_twitter'   => 'nullable|url',
            'social_whatsapp'  => 'nullable|url',
        ]);

        foreach ($validated as $key => $value) {
            Setting::setValue($key, $value);
        }

        return redirect()->route('admin.social-media.edit')
                         ->with('success', 'Link media sosial berhasil disimpan.');
    }
}
