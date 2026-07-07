<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // TODO: Ganti dengan logic ambil kategori milik owner
        return view('owner.categories.index');
    }
}
