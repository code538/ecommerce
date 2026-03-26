<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends BaseController
{

    public function index()
    {
        $products = Product::with(['category','subcategory','images','features','specifications','reviews','variants'])->latest()->get();
        return $this->success($products, 'Product list fetched');
    }

    public function userIndex()
    {
        $products = Product::with(['category','subcategory','images','features','specifications','reviews','variants'])->where('status', 1)->latest()->get();
        return $this->success($products, 'Product list fetched');
    }

    public function premiumProduct()
    {
        $products = Product::with(['category','subcategory','images','features','specifications','reviews','variants'])->where('status', 1)->where('premium_product', 1)->latest()->get();
        return $this->success($products, 'Premium product list fetched');
    }

    public function showProductDetails(string $slug)
    {
        $product = Product::with([
            'category',
            'subcategory',
            'images',
            'features',
            'specifications',
            'variants',
            'reviews' => function ($q) {
                $q->where('status', 1);
            }
        ])
        ->where('status', 1)
        ->where('slug', $slug)
        ->first();

        if (!$product) {
            return $this->error('Product not found', null, 404);
        }

        // ✅ Total Reviews
        $reviewCount = $product->reviews->count();

        // ✅ Average Rating
        $avgRating = $product->reviews->avg('rating');

        // ✅ Rating Breakdown
        $ratingBreakdown = [
            5 => $product->reviews->where('rating', 5)->count(),
            4 => $product->reviews->where('rating', 4)->count(),
            3 => $product->reviews->where('rating', 3)->count(),
            2 => $product->reviews->where('rating', 2)->count(),
            1 => $product->reviews->where('rating', 1)->count(),
        ];

        // ✅ Rating Ratio (percentage)
        $ratingPercentage = [];
        foreach ($ratingBreakdown as $star => $count) {
            $ratingPercentage[$star] = $reviewCount > 0 
                ? round(($count / $reviewCount) * 100, 2) 
                : 0;
        }

        // ✅ Attach extra data
        $product->review_summary = [
            'average_rating' => round($avgRating, 1),
            'total_reviews' => $reviewCount,
            'rating_breakdown' => $ratingBreakdown,
            'rating_percentage' => $ratingPercentage
        ];

        return $this->success($product, 'Product details fetched');
    }

    public function getFeaturedProducts()
    {
        $products = Product::with(['category','subcategory','images','features','specifications','reviews'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        return $this->success($products, 'Featured products fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',

            'name' => 'required|string|max:255',
            'name_meta' => 'nullable|string|max:255',
            'tag_line' => 'nullable|string|max:255',
            'premium_product' => 'nullable|boolean',

            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',

            'stock' => 'nullable|integer|min:0',

            'description' => 'nullable|string',
            'description_meta' => 'nullable|string',

            'shipping_policy' => 'nullable|string',
            'shipping_policy_meta' => 'nullable|string',

            'return_policy' => 'nullable|string',
            'return_policy_meta' => 'nullable|string',

            'status' => 'nullable|boolean'

        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

       $product = Product::create([

            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,

            'name' => $request->name,
            'slug' => Str::slug($request->name),

            'name_meta' => $request->name_meta,
            'tag_line' => $request->tag_line,
            'premium_product' => $request->premium_product,

            'price' => $request->price,
            'sale_price' => $request->sale_price,

            'stock' => $request->stock ?? 0,

            'rating' => 0,
            'review_count' => 0,

            'description' => $request->description,
            'description_meta' => $request->description_meta,

            'shipping_policy' => $request->shipping_policy,
            'shipping_policy_meta' => $request->shipping_policy_meta,

            'return_policy' => $request->return_policy,
            'return_policy_meta' => $request->return_policy_meta,

            'status' => $request->status ?? 1

        ]);

        return $this->success($product, 'Product created', 201);
    }

    public function show(int $id)
    {
        $product = Product::with([
            'images',
            'features',
            'specifications',
            'reviews'
        ])->find($id);

        if (!$product) {
            return $this->error('Product not found', null, 404);
        }

        return $this->success($product, 'Product fetched');
    }

    public function update(Request $request, int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->error('Product not found', null, 404);
        }

        $validator = Validator::make($request->all(), [

            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'nullable|exists:subcategories,id',

            'name' => 'nullable|string|max:255',
            'name_meta' => 'nullable|string|max:255',
            'tag_line' => 'nullable|string|max:255',
            'premium_product' => 'nullable|boolean',

            'price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',

            'stock' => 'nullable|integer|min:0',

            'description' => 'nullable|string',
            'description_meta' => 'nullable|string',

            'shipping_policy' => 'nullable|string',
            'shipping_policy_meta' => 'nullable|string',

            'return_policy' => 'nullable|string',
            'return_policy_meta' => 'nullable|string',

            'status' => 'nullable|boolean'

        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $product->update([

            // 'category_id' => $request->category_id,
            // 'subcategory_id' => $request->subcategory_id,
            // 'name' => $request->name,
            // 'slug' => Str::slug($request->name),
            // 'price' => $request->price,
            // 'stock' => $request->stock ?? $product->stock,
            // 'description' => $request->description,
            // 'status' => $request->status ?? $product->status

            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,

            'name' => $request->name,
            'slug' => Str::slug($request->name),

            'name_meta' => $request->name_meta,
            'tag_line' => $request->tag_line,
            'premium_product' => $request->premium_product,

            'price' => $request->price,
            'sale_price' => $request->sale_price,

            'stock' => $request->stock ?? $product->stock,

            'rating' => 0,
            'review_count' => 0,

            'description' => $request->description,
            'description_meta' => $request->description_meta,

            'shipping_policy' => $request->shipping_policy,
            'shipping_policy_meta' => $request->shipping_policy_meta,

            'return_policy' => $request->return_policy,
            'return_policy_meta' => $request->return_policy_meta,

            'status' => $request->status ?? $product->status

        ]);

        return $this->success($product, 'Product updated');
    }

    public function destroy(int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->error('Product not found', null, 404);
        }

        $product->delete();

        return $this->success(null, 'Product deleted');
    }
}