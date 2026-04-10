<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'price',
        'category_id',
        'subcategory_id',
        'quantity',
        'description',
        'image',
        'base_price'
    ];

    protected $casts = [
        'image' => 'array',
        'base_price' => 'decimal:2'
    ];

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
   

    public function properties()
    {
        return $this->hasMany(ProductProperty::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategories::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Get price for specific variant
    public function getVariantPrice($variantId)
    {
        $variant = $this->variants()->find($variantId);
        if ($variant) {
            return $this->base_price + $variant->price_adjustment;
        }
        return $this->base_price;
    }

    // Get available variants by type
    public function getVariantsByType($type)
    {
        return $this->variants()->where('variant_type', $type)->get();
    }
}