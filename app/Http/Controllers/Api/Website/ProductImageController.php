<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductImageController extends BaseController
{

    public function index()
    {
        $images = ProductImage::latest()->get();
        return $this->success($images, 'Product images fetched');
    }

    public function showByProduct($product_id)
    {
        $images = ProductImage::where('product_id', $product_id)
                    ->orderBy('sort_order')
                    ->get();

        if ($images->isEmpty()) {
            return $this->error('No images found for this product', null, 404);
        }

        return $this->success($images, 'Product images fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id'   => 'required|exists:products,id',
            'image'        => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_alt'    => 'nullable|string',
            'is_thumbnail' => 'nullable|boolean',
            'sort_order'   => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        // Upload image to storage/app/public/product
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product', 'public');
        }

        $image = ProductImage::create([
            'product_id'   => $request->product_id,
            'image'        => $imagePath,
            'image_alt'    => $request->image_alt,
            'is_thumbnail' => $request->is_thumbnail ?? 0,
            'sort_order'   => $request->sort_order ?? 0
        ]);

        return $this->success($image, 'Product image uploaded successfully', 201);
    }

    public function destroy(int $id)
    {
        $image = ProductImage::find($id);

        if (!$image) {
            return $this->error('Image not found', null, 404);
        }

        $image->delete();

        return $this->success(null, 'Image deleted');
    }
}