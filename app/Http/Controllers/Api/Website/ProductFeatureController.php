<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\ProductFeature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductFeatureController extends BaseController
{
    public function index($product_id)
    {
        $features = ProductFeature::where('product_id', $product_id)->get();

        if ($features->isEmpty()) {
            return $this->error('No features found for this product', null, 404);
        }

        return $this->success($features, 'Product features fetched');
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'feature' => 'required|string',
            'feature_meta' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $feature = ProductFeature::create($request->all());

        return $this->success($feature, 'Feature added', 201);
    }

    public function destroy(int $id)
    {
        $feature = ProductFeature::find($id);

        if (!$feature) {
            return $this->error('Feature not found', null, 404);
        }

        $feature->delete();

        return $this->success(null, 'Feature deleted');
    }
}