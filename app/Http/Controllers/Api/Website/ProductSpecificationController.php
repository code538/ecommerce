<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\ProductSpecification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductSpecificationController extends BaseController
{
    public function index($product_id)
    {
        $specification = ProductSpecification::where('product_id', $product_id)->get();

        if ($specification->isEmpty()) {
            return $this->error('No specification found for this product', null, 404);
        }

        return $this->success($specification, 'Product specification fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'spec_key' => 'required|string',
            'spec_key_meta' => 'nullable|string',
            'spec_value' => 'required|string',
            'spec_value_meta' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $spec = ProductSpecification::create($request->all());

        return $this->success($spec, 'Specification added', 201);
    }

    public function destroy(int $id)
    {
        $spec = ProductSpecification::find($id);

        if (!$spec) {
            return $this->error('Specification not found', null, 404);
        }

        $spec->delete();

        return $this->success(null, 'Specification deleted');
    }
}