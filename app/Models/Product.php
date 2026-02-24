<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'sku', 'purchase_price', 'sell_price',
        'opening_stock', 'current_stock', 'description'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getTotalSoldAttribute(): int
    {
        return $this->sales()->sum('quantity');
    }

    public function getStockValueAttribute(): float
    {
        return $this->current_stock * $this->purchase_price;
    }
}