<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name',
        'price',
        'quantity',
        'subtotal'
    ];

    protected $casts = [
        'product_id' => 'integer',
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Get variant IDs as array
    public function getVariantIdsAttribute()
    {
        if (!$this->variant_id) {
            return [];
        }
        return explode(',', $this->variant_id);
    }
    
    // Get all variants for this order item
    public function variants()
    {
        if (!$this->variant_id) {
            return collect();
        }
        $variantIds = explode(',', $this->variant_id);
        return ProductVariant::whereIn('id', $variantIds)->get();
    }
    
    // Get single variant (if only one)
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    
    // Get formatted variant names
    public function getVariantNamesAttribute()
    {
        $variants = $this->variants();
        if ($variants->isEmpty()) {
            return '';
        }
        return $variants->pluck('variant_name')->implode(' + ');
    }
}