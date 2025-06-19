<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriOpname extends Model
{
    use HasFactory;

    protected $table = 'stokhistoriopname';
    protected $fillable = ['keterangan', 'created_at', 'updated_at'];

    public function detailStokOpname()
    {
        return $this->hasMany(StokOpname::class, 'id_histori');
    }
}
