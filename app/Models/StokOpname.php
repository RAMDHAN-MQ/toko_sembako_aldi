<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;
    protected $table = 'stokopname';
    protected $primaryKey = 'id';
    protected $fillable = ['id_histori', 'id_produk', 'stok_fisik', 'stok_sistem', 'selisih', 'created_at'];

    public function histori()
    {
        return $this->belongsTo(HistoriOpname::class, 'id_histori');
    }

    public function produk()
    {
        return $this->belongsTo(produk::class, 'id_produk');
    }
}
