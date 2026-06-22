<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CategoryResource;
use App\Http\Resources\V1\ProductCollection;
use App\Http\Resources\V1\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

/**
 * @tags Products
 */
class ProductController extends Controller
{
    /**
     * List all available products.
     *
     * Supports pagination, search by name, and category filter.
     */
    public function index(Request $request): ProductCollection
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        $perPage = min((int) $request->get('per_page', 12), 48);

        return new ProductCollection(
            $query->latest()->paginate($perPage)->appends($request->query())
        );
    }

    /**
     * Get a single product by ID.
     */
    public function show(Product $product): \Illuminate\Http\JsonResponse
    {
        $product->load('category');

        return response()->json([
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * List all categories (for filter).
     */
    public function categories(): \Illuminate\Http\JsonResponse
    {
        $categories = Category::withCount('products')->get();

        return response()->json([
            'data' => CategoryResource::collection($categories),
        ]);
    }
}
