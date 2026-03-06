<?php

namespace App\Http\Controllers;

use App\Models\Category;

use App\Models\Subcategories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubcategoryController extends Controller
{
  public function detail($slug)
  {
    return view('subcategory');
  }

  public function subcategories()
  {
    $subcategories = Subcategories::paginate(10);

    $subcategories = Subcategories::with('category')->paginate(10);

    return view('admin.subcategory.subcategories', compact('subcategories'));
  }


  public function createsubcategory()
{
    $categories = Category::all();

    return view('admin.subcategory.addsubcategory', compact('categories'));
}


  public function storesubcategory(Request $request)
  {
    $request->validate([
      'sub_category_name' => 'required',
      'category_id' => 'required',
      'description' => 'required',
    ]);

    Subcategories::create([
      'sub_category_name' => $request->sub_category_name,
      'category_id' => $request->category_id,
      'description' => $request->description,
    ]);

    return redirect()->route('admin.subcategory.subcategories')
      ->with('success', 'Sub Category added successfully');
  }


  public function editsubcategory($id)
  {
    $subcategory = Subcategories::findOrFail($id);
    $categories = Category::all();

    return view('admin.subcategory.addsubcategory', compact('subcategory', 'categories'));
  }


  public function updatesubcategory(Request $request, $id)
  {
    $subcategory = Subcategories::findOrFail($id);

    $request->validate([
      'sub_category_name' => 'required',
      'category_id' => 'required',
      'description' => 'required',
    ]);

    $subcategory->update([
      'sub_category_name' => $request->sub_category_name,
      'category_id' => $request->category_id,
      'description' => $request->description,
    ]);

    return redirect()->route('admin.subcategory.subcategories')
      ->with('success', 'Sub Category updated successfully');
  }


  public function destroy($id)
  {
    Subcategories::findOrFail($id)->delete();

    return redirect()
      ->route('admin.subcategory.subcategories')
      ->with('success', 'Category deleted successfully.');
  }
}
