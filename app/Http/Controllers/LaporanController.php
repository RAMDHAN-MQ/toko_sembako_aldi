<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Exports\LaporanExport;
use App\Exports\OngkirExport;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\DetailTransaksi;
use App\Models\User;
use App\Models\produk;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    public function index()
    {
        $dataTransaksi = Transaction::all();
        $dataDetail = DetailTransaksi::all();
        $dataUser = User::all();
        $dataProduk = produk::all();

        return view('dashboard_admin.laporan', compact('dataTransaksi', 'dataDetail', 'dataUser', 'dataProduk'));
    }

    public function cari(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai');
        $tanggalAkhir = $request->input('tanggal_akhir');

        // Validasi jika tanggal kosong
        if (!$tanggalMulai || !$tanggalAkhir) {
            return redirect()->route('laporan')->with('error', 'Harap pilih rentang tanggal.');
        }

        // Filter transaksi berdasarkan rentang tanggal
        $dataTransaksi = Transaction::whereBetween('created_at', [$tanggalMulai, $tanggalAkhir])->get();
        $dataDetail = DetailTransaksi::all();
        $dataUser = User::all();
        $dataProduk = produk::all();

        return view('dashboard_admin.laporan', compact('dataTransaksi', 'dataDetail', 'dataUser', 'dataProduk'));
    }

    public function excel()
    {
        return Excel::download(new LaporanExport, 'Laporan.xlsx');
    }

    public function pdf()
    {
        $dataTransaksi = Transaction::all();
        $dataDetail = DetailTransaksi::all();
        $dataUser = User::all();
        $dataProduk = produk::all();

        return view('dashboard_admin.pdfLaporan', compact('dataTransaksi', 'dataDetail', 'dataUser', 'dataProduk'));
    }

    public function chart()
    {
        // Ambil data transaksi dengan status "selesai" dan kelompokkan per bulan
        $data = Transaction::selectRaw('MONTH(created_at) as bulan, SUM(harga) as total')
            ->where('status', 'selesai')
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan'); // Menggunakan keyBy agar mudah mencari bulan tertentu

        // Daftar semua bulan (Januari - Desember)
        $bulanLengkap = [];
        $pendapatanLengkap = [];

        for ($i = 1; $i <= 12; $i++) {
            $bulanLengkap[] = Carbon::create()->month($i)->translatedFormat('F');
            $pendapatanLengkap[] = $data[$i]->total ?? 0; // Jika bulan tidak ada, set ke 0
        }

        return view('dashboard_admin.chart', compact('bulanLengkap', 'pendapatanLengkap'));
    }

    public function produkTerlaris()
    {
        $produkTerlaris = DetailTransaksi::select('produk_id')
            ->selectRaw('SUM(jumlah) as total_terjual')
            ->whereHas('transaksi', function ($query) {
                $query->where('status', 'selesai');
            })
            ->groupBy('produk_id')
            ->orderByDesc('total_terjual')
            ->take(10)
            ->get();

        $produkInfo = $produkTerlaris->map(function ($item) {
            $produk = produk::find($item->produk_id);
            return [
                'nama_produk' => $produk ? $produk->nama_produk : 'Tidak Diketahui',
                'total_terjual' => $item->total_terjual,
            ];
        });

        return view('dashboard_admin.produk-terlaris', compact('produkInfo'));
    }

    public function laporanOngkir(){
        $dataTransaksi = Transaction::all();

        return view('dashboard_admin.laporan-ongkir', compact('dataTransaksi'));
    }

    public function excelOngkir()
    {
        return Excel::download(new OngkirExport, 'laporan_ongkir.xlsx');
    }

    public function pdfOngkir()
    {
        $dataTransaksi = Transaction::all();

        return view('dashboard_admin.pdfLaporanOngkir', compact('dataTransaksi'));
    }

}
