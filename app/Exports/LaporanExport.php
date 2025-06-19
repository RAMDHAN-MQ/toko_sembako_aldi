<?php

namespace App\Exports;

use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LaporanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function collection()
    {
        return DB::table('detail_transaksi')
            ->join('transaksi', 'transaksi.id', '=', 'detail_transaksi.transaksi_id')
            ->join('users', 'users.id', '=', 'transaksi.user_id')
            ->join('produk', 'produk.id', '=', 'detail_transaksi.produk_id')
            ->where('transaksi.status', 'selesai')
            ->select(
                'transaksi.id as ID Transaksi',
                'users.name as Nama Pelanggan',
                'produk.nama_produk as Produk',
                'detail_transaksi.jumlah as Jumlah',
                'detail_transaksi.harga_satuan as Harga Satuan',
                DB::raw('(detail_transaksi.jumlah * detail_transaksi.harga_satuan) as SubTotal'),
                'transaksi.created_at as Tanggal Transaksi'
            )
            ->get();
    }

    public function headings(): array
    {
        return ["ID Transaksi", "Nama Pelanggan", "Produk", "Jumlah", "Harga Satuan", "SubTotal", "Tanggal Transaksi"];
    }
}
