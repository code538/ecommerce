<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\Request;
use App\Models\Website\ProductVariant;
use App\Models\Website\Product;

class ProductVariantController extends BaseController
{

    // Get variants of product
    public function index($product_id)
    {
        $variants = ProductVariant::where('product_id',$product_id)->get();
        return $this->success($variants, 'Product variants fetched');
    }

    // Add variant
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color' => 'required|string',

            'size' => 'required|array|min:1',
            'size.*' => 'required|string',

            'price' => 'required|array',
            'price.*' => 'required|numeric|min:0',

            'stock' => 'required|array',
            'stock.*' => 'required|integer|min:0',

            'sale_price' => 'nullable|array'
        ]);

        $product = Product::find($request->product_id);

        if ($product->product_type == 'simple') {
            return $this->error('Cannot add variants to simple product', null, 400);
        }

        $created = [];

        foreach ($request->size as $index => $size) {

            $price = $request->price[$index] ?? null;
            $stock = $request->stock[$index] ?? 0;
            $salePrice = $request->sale_price[$index] ?? null;

            if (!$price) {
                continue;
            }

            $exists = ProductVariant::where([
                'product_id' => $request->product_id,
                'color' => $request->color,
                'size' => $size
            ])->exists();

            if ($exists) {
                continue;
            }

            $variant = ProductVariant::create([
                'product_id' => $request->product_id,
                'color' => $request->color,
                'size' => $size,
                'price' => $price,
                'sale_price' => $salePrice,
                'stock' => $stock
            ]);

            $created[] = $variant;
        }

        return $this->success($created, 'Variants created successfully');
    }

    public function edit ($id){
        $variant = ProductVariant::findOrFail($id);
        if(!$variant){
          return $this->success($variant, 'Variant not found');  
        }

        return $this->success($variant, 'Variant fetch successfully');
    }

    // Update variant
    public function update(Request $request,$id)
    {

        $variant = ProductVariant::findOrFail($id);

        $variant->update([
            //'variant_name'=>$request->variant_name ?? null,
            'color'=>$request->color,
            'size'=>$request->size,
            'price'=>$request->price,
            'sale_price'=>$request->sale_price,
            'stock'=>$request->stock
        ]);

        return $this->success($variant, 'Variant updated successfully');
    }

    // Delete variant
    public function destroy($id)
    {
        $variant = ProductVariant::findOrFail($id);

        $variant->delete();
        return $this->success(null,'Variant deleted successfully');
    }

}
