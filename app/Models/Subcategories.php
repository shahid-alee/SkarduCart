<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategories extends Model
{
    // Specify the correct table name
    protected $table = 'sub_categories';
    
    protected $fillable = [
        'sub_category_name',
        'category_id',
        'description'
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    
    public function products()
    {
        return $this->hasMany(Product::class, 'subcategory_id');
    }
}