<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $table = 'transaction';
    protected $fillable = [
        'customer_id',
        'no_transaksi',
        'tanggal',
        'subtotal',
        'diskon',
        'ongkir',
        'total_bayar',
        'status'
    ];

    public function details()
    {
        return $this->hasMany(Detail_Transaction::class, 'transaksi_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
