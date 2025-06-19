<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class TransaksiController extends Controller
{

    public function index()
    {
        $data = Transaction::all();
        return view('profile', ['dataTransaksi' => $data]);
    }
    public function indexAdmin()
    {
        $data = Transaction::all();
        return view('dashboard_admin.Transaksi.index', ['dataTransaksi' => $data]);
    }
    public function sampai($id) {
        $transaksi = Transaction::find($id);
        if ($transaksi && $transaksi->status === 'dikirim') {
            $transaksi->status = 'selesai';
            $transaksi->save();
            return redirect()->back()->with('success', 'Status transaksi berhasil diubah menjadi selesai!');
        }
        return redirect()->back()->with('error', 'Transaksi tidak dapat diperbarui!');
    }
    
}
