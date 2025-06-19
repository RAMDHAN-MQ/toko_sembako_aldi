<?php

namespace App\Exports;

use App\Models\DetailTransaksi;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OngkirExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('transaksi')
            ->join('users', 'transaksi.user_id', '=', 'users.id')
            ->select(
                'transaksi.id as ID_Transaksi',
                'users.name as Nama_Pelanggan',
                DB::raw('"Rp16.000" as Biaya_Ongkir'),
                DB::raw('DATE_FORMAT(transaksi.updated_at, "%d-%m-%Y %H:%i") as Tanggal_Kirim')
            )
            ->where('transaksi.status', 'selesai')
            ->get();
    }

    public function headings(): array
    {
        return ["ID Transaksi", "Nama Pelanggan", "Biaya Ongkir", "Tanggal Kirim"];
    }
}
