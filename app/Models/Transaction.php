<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    protected $fillable = [
        'user_id',
        'product_id',
        'harga',
        'status',
        'snap_token',
    ];
    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
