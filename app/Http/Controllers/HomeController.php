<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $topDeals = Product::latest()->take(4)->get();
        
        $popularCategories = Product::where('category_id', 1)->latest()->take(8)->get();

        $recentlyViewed = Product::latest()->take(4)->get();
        
        return view('pages.home', compact(
            'topDeals', 
            'popularCategories', 
            'recentlyViewed'
        ));
    }
}
