<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HargaBarangPokok;

class HomeController extends Controller
{
    public function index()
    {
        $barang_pokok = HargaBarangPokok::orderBy('uraian')->take(8)->get();
        $total = HargaBarangPokok::count();
        $last_update = HargaBarangPokok::orderBy('updated_at', 'desc')->first();

        return view('home', compact('barang_pokok', 'total', 'last_update'));
    }

    public function admin()
    {
        // Recent products updated in last 5 days
        $recentProducts = \App\Models\HargaBarangPokok::where('updated_at', '>=', now()->subDays(5))
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        // Totals
        $notaCount = \App\Models\Nota::count();
        $customerCount = \App\Models\User::where('role', 'user')->count();

        $companyName = \App\Models\Setting::get('company_name', '');
        return view('admin', compact('recentProducts', 'notaCount', 'customerCount', 'companyName'));
    }

    public function contact()
    {
        return view('contact');
    }
}
