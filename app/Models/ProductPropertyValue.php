<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPropertyValue extends Model
{
    protected $fillable = [
        'product_property_id',
        'value'
    ];

}
