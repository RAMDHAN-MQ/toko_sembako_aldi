<?php

namespace App\Http\Controllers;

use App\Models\produk;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('q');
    $dataproduk = produk::where('nama_produk', 'LIKE', "%{$query}%")->get();

    return view('order', compact('dataproduk'));
}
}
