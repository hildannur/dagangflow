<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'channel',
        'quantity',
        'selling_price',
        'gross_total',
        'platform_fee',
        'net_total',
        'status',
        'note',
        'sale_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}