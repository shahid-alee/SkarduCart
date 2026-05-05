<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subcategories;
use App\Models\Category;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    
        public function index()
    {
        try {
            $subcategories = Subcategories::with('category')->get();
            
            return response()->json($subcategories);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve subcategories',
                'error' => $e->getMessage()
            ], 500);
        }
    }


        
    

    // Store new subcategory
    public function subcategorystore(Request $request)
    {
        $request->validate([
            'sub_category_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $subcategory = Subcategories::create([
            'sub_category_name' => $request->sub_category_name,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        return response()->json([
            'message' => 'Subcategory created successfully!',
            'subcategory' => $subcategory
        ], 201);
    }

    
    public function show($id)
    {
        $subcategory = Subcategories::with('category')->find($id);
        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }
        return response()->json($subcategory);
    }

    // Update subcategory
    public function update(Request $request, $id)
    {
        $subcategory = Subcategories::find($id);
        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $request->validate([
            'sub_category_name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
        ]);

        $subcategory->update([
            'sub_category_name' => $request->sub_category_name,
            'category_id' => $request->category_id,
            'description' => $request->description,
        ]);

        return response()->json(['message' => 'Subcategory updated successfully!', 'subcategory' => $subcategory]);
    }

    // Delete subcategory
    public function destroy($id)
    {
        $subcategory = Subcategories::find($id);
        if (!$subcategory) {
            return response()->json(['message' => 'Subcategory not found'], 404);
        }

        $subcategory->delete();
        return response()->json(['message' => 'Subcategory deleted successfully!']);
    }
}