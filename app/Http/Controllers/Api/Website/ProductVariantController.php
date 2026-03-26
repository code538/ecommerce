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
            'product_id'=>'required',
            'variant_name'=>'nullable',
            'price'=>'required',
            'stock'=>'required'
        ]);

        $variant = ProductVariant::create([
            'product_id'=>$request->product_id,
            'variant_name'=>$request->variant_name,
            'color'=>$request->color,
            'size'=>$request->size,
            'price'=>$request->price,
            'sale_price'=>$request->sale_price,
            'stock'=>$request->stock
        ]);

        return $this->success($variant, 'Variant created successfully');

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
            'variant_name'=>$request->variant_name ?? null,
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
