<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products
     * GET /api/products
     */
    public function index(Request $request)
    {
        $query = Product::with('category')->where('status', 'active');

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Search
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Display the specified product
     * GET /api/products/{id}
     */
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product);
    }

    /**
     * Store a newly created product
     * POST /api/products
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'image_url' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);

        $product = Product::create($request->all());
        return response()->json($product, 201);
    }

    /**
     * Update the specified product
     * PUT /api/products/{id}
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'category_id' => 'exists:categories,id',
            'name' => 'string|max:255',
            'description' => 'nullable|string',
            'price' => 'integer|min:0',
            'stock' => 'integer|min:0',
            'image_url' => 'nullable|string',
            'status' => 'in:active,inactive'
        ]);

        $product->update($request->all());
        return response()->json($product);
    }

    /**
     * Remove the specified product
     * DELETE /api/products/{id}
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}