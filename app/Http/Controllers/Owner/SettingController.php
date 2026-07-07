<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // TODO: Ganti dengan logic pengaturan toko
        return view('owner.settings.index');
    }
}
