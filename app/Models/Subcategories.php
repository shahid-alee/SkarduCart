<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    protected $table = 'sub_categories';
    protected $fillable = [
        'sub_category_name',
        'category_id',
        'description'
    ];

    public function category()
{
    return $this->belongsTo(Category::class);
}
}