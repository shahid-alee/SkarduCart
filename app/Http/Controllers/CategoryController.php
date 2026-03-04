<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

use function PHPUnit\Framework\returnValue;

class CategoryController extends Controller
{

    public function detail()
    {
        return view('category');
    }

    public function categories()
    {
        $categories = Category::paginate(10);

        return view('admin.category.categories', compact('categories'));
    }

    public function createcategory()
    {
        return view('admin.category.addcategory');
    }

    public function storecategory(Request $request)
    {
        $request->validate([
            'category_name' => 'required',
            'description' => 'required',
        ]);

        Category::create([
            'category_name' => $request->category_name,
            'description' => $request->description,
          
        ]);

        return redirect()->back()->with('success', 'Category added successfully');
    }


    public function editproduct($id)
    {
        $category = Category::findOrFail($id);

        return view('admin.category.addcategory', compact('product'));
    }


public function updatecategory(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'category_name' => 'required',
            'description' => 'required',
            
        ]);


        $category->category_name = $request->category_name;
        $category->description = $request->description;



        $category->save();

        return redirect()->route('admin.category.categories')
            ->with('success', 'Category updated successfully');
    }

    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return redirect()
            ->route('admin.category.categories')
            ->with('success', 'Category deleted successfully.');
    }
}
