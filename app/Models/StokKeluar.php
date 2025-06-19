<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokKeluar extends Model
{
    use HasFactory;
    protected $table = 'stokkeluar';
    protected $primaryKey = 'id';
    protected $fillable = ['id_histori', 'id_produk', 'harga', 'qty', 'created_at'];

    public function histori()
    {
        return $this->belongsTo(StokHistori::class, 'id_histori');
    }

    public function produk()
    {
        return $this->belongsTo(produk::class, 'id_produk');
    }
}
