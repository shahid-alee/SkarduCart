<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function PHPUnit\Framework\returnValue;

class CategoryController extends Controller
{
 
public function detail(){
    return view('category');
}
}
