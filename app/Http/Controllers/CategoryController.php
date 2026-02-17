<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     * GET /api/categories
     */
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return response()->json($categories);
    }

    /**
     * Display the specified category
     * GET /api/categories/{id}
     */
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return response()->json($category);
    }

    /**
     * Store a newly created category
     * POST /api/categories
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($request->all());
        return response()->json($category, 201);
    }

    /**
     * Update the specified category
     * PUT /api/categories/{id}
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'string|max:255',
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());
        return response()->json($category);
    }

    /**
     * Remove the specified category
     * DELETE /api/categories/{id}
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}