<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokMasuk extends Model
{
    use HasFactory;
    protected $table = 'stokmasuk';
    protected $primaryKey = 'id';
    protected $fillable = ['id_histori', 'id_produk', 'harga', 'qty', 'total', 'created_at'];

    public function histori()
    {
        return $this->belongsTo(StokHistori::class, 'id_histori');
    }

    public function produk()
    {
        return $this->belongsTo(produk::class, 'id_produk');
    }
}
