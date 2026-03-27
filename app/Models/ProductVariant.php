<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'variant_name',
        'variant_type',
        'price_adjustment',
        'stock_quantity'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}