<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{

    protected $fillable = [
        'product_id',
        'user_id',
        'property_name'
    ];

    public function values()
    {
        return $this->hasMany(ProductPropertyValue::class);
    }

}
