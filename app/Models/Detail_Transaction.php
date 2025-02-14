<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail_Transaction extends Model
{
    use HasFactory;

    protected $table = 'detail_transaction';
    protected $fillable = [
        'transaksi_id',
        'food_id',
        'qty',
        'harga_bandrol',
        'diskon_persen',
        'harga_diskon',
        'total'
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'transaksi_id');
    }

    public function food()
    {
        return $this->belongsTo(Food::class, 'food_id');
    }
}
