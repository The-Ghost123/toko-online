<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Log::info('ProfileController@update called', $data);

        // Handle cropped avatar (base64) if provided
        if (! empty($data['avatar_cropped'])) {
            $dataUrl = $data['avatar_cropped'];
            Log::info('avatar_cropped present', ['len' => strlen($dataUrl)]);
            if (preg_match('/^data:image\/(\w+);base64,/', $dataUrl, $type)) {
                $extension = strtolower($type[1]) === 'jpeg' ? 'jpg' : strtolower($type[1]);
                $dataStr = substr($dataUrl, strpos($dataUrl, ',') + 1);
                $decoded = base64_decode($dataStr);
                if ($decoded !== false) {
                    $filename = 'avatars/' . uniqid() . '.' . $extension;
                    // delete old avatar if exists
                    if ($request->user()->avatar) {
                        Storage::disk('public')->delete($request->user()->avatar);
                    }
                    Storage::disk('public')->put($filename, $decoded);
                    $request->user()->avatar = $filename;
                        Log::info('avatar saved', ['filename' => $filename, 'user_id' => $request->user()->id]);
                }
            }
        }

        // Update other fields
        $request->user()->fill(Arr::except($data, ['avatar_cropped']));

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        Log::info('user saved', ['id' => $request->user()->id, 'updated_at' => $request->user()->updated_at]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
