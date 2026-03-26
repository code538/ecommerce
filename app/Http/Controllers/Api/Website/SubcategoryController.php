<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SubcategoryController extends BaseController
{

    public function index()
    {
        $subcategories = Subcategory::with('category')->latest()->get();
        return $this->success($subcategories, 'Subcategory list fetched');
    }

    public function userIndex($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->where('status', 1)->latest()->get();
        return $this->success($subcategories, 'Subcategory list fetched');
    }

    public function productsBySubcategory($slug)
    {
        $subcategory = Subcategory::where('slug', $slug)->first();

        if (!$subcategory) {
            return $this->error('Subcategory not found', null, 404);
        }

        $products = $subcategory->products()->with(['category','subcategory','images','features','specifications','reviews','variants'])->where('status', 1)->latest()->get();

        $data = [
            'subcategory' => $subcategory,
            'products' => $products
        ];

        return $this->success($data, 'Products fetched for subcategory');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
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
            $imagePath = $request->file('image')->store('subcategories', 'public');
        }

        $subcategory = Subcategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'image_alt' => $request->image_alt,
            'name_meta' => $request->name_meta,
            'status' => $request->status ?? 1
        ]);

        return $this->success($subcategory, 'Subcategory created', 201);
    }

    public function show(int $id)
    {
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return $this->error('Subcategory not found', null, 404);
        }

        return $this->success($subcategory, 'Subcategory fetched');
    }

    public function update(Request $request, int $id)
    {
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return $this->error('Subcategory not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
            'image_alt' => 'nullable|string|max:255',
            'name_meta' => 'nullable|string|max:255',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $imagePath = $subcategory->image;

        if ($request->hasFile('image')) {

            if ($subcategory->image && Storage::disk('public')->exists($subcategory->image)) {
                Storage::disk('public')->delete($subcategory->image);
            }

            $imagePath = $request->file('image')->store('subcategories', 'public');
        }

        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
            'image_alt' => $request->image_alt,
            'name_meta' => $request->name_meta,
            'status' => $request->status ?? $subcategory->status
        ]);

        return $this->success($subcategory, 'Subcategory updated');
    }

    public function destroy(int $id)
    {
        $subcategory = Subcategory::find($id);

        if (!$subcategory) {
            return $this->error('Subcategory not found', null, 404);
        }
        if ($subcategory->image && Storage::disk('public')->exists($subcategory->image)) {
            Storage::disk('public')->delete($subcategory->image);
        }
        $subcategory->delete();

        return $this->success(null, 'Subcategory deleted');
    }
}