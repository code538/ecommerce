<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Category;
use App\Models\Website\Product;
use App\Models\Website\Subcategory;
use App\Models\Website\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CategoryController extends BaseController
{

    public function index()
    {
        $categories = Category::latest()->get();
        return $this->success($categories, 'Category list fetched');
    }

    public function userIndex()
    {
        $categories = Category::where('status', 1)->latest()->get();
        return $this->success($categories, 'Category list fetched');
    }

    // public function showProducts($slug)
    // {
    //     $category = Category::where('slug', $slug)->first();

    //     if (!$category) {
    //         return $this->error('Category not found', null, 404);
    //     }

    //     $products = $category->products()->with(['category','subcategory','images','features','specifications','reviews'])->where('status', 1)->latest()->get();

    //     $subCategory = Subcategory::where('category_id', $category->id)->where('status', 1)->get();

    //     $data = [
    //         'category' => $category,
    //         'subcategories' => $subCategory,
    //         'products' => $products
    //     ];
    //     //dd($subCategory);
    //     return $this->success($products, 'Products fetched for category');
    // }

    public function showProducts($slug)
    {
        $category = Category::where('slug', $slug)->first();

        if (!$category) {
            return $this->error('Category not found', null, 404);
        }

        // Products directly under category
        $categoryProducts = $category->products()
            ->with(['category','subcategory','images','features','specifications','reviews','variants'])
            ->where('status', 1)
            ->latest()
            ->get();

        // Subcategories with their products
        $subcategories = Subcategory::with(['products' => function ($query) {
            $query->with(['category','subcategory','images','features','specifications','reviews'])
                ->where('status',1)
                ->latest();
        }])
        ->where('category_id', $category->id)
        ->where('status', 1)
        ->get();

        $data = [
            'category' => $category,
            'category_products' => $categoryProducts,
            'subcategories' => $subcategories
        ];

        return $this->success($data, 'Products fetched for category');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'image_alt' => 'nullable|string|max:255',
            'name_meta' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'image_alt' => $request->image_alt,
            'name_meta' => $request->name_meta,
            'status' => $request->status ?? 1
        ]);

        return $this->success($category, 'Category created', 201);
    }

    public function show(int $id)
    {   
        $category = Category::find($id);

        if (!$category) {
            return $this->error('Category not found', null, 404);
        }

        return $this->success($category, 'Category fetched');
    }

    public function update(Request $request, int $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->error('Category not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'image_alt' => 'nullable|string|max:255',
            'name_meta' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $imagePath = $category->image;

        if ($request->hasFile('image')) {

            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'image_alt' => $request->image_alt,
            'name_meta' => $request->name_meta,
            'status' => $request->status ?? $category->status
        ]);

        return $this->success($category, 'Category updated');
    }

    public function destroy(int $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->error('Category not found', null, 404);
        }

        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return $this->success(null, 'Category deleted');
    }
}