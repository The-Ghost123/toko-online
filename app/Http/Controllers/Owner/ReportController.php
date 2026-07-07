<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        // TODO: Ganti dengan logic laporan penjualan
        return view('owner.reports.index');
    }
}
