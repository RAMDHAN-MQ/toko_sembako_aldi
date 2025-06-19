<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokHistori extends Model
{
    use HasFactory;
    protected $table = 'stokhistori';
    protected $primaryKey = 'id';
    protected $fillable = ['keterangan','tipe', 'created_at', 'updated_at'];

    public function detailStokMasuk()
    {
        return $this->hasMany(StokMasuk::class, 'id_histori');
    }
    
    public function detailStokKeluar()
    {
        return $this->hasMany(StokKeluar::class, 'id_histori');
    }
}
