<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'category',
        'selling_price',
        'cost_price',
        'stock',
        'low_stock_limit',
    ];

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'Habis';
        }

        if ($this->stock <= $this->low_stock_limit) {
            return 'Rendah';
        }

        return 'Aman';
    }
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}