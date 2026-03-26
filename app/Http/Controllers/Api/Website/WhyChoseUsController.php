<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\WhyChoseUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WhyChoseUsController extends BaseController
{

    public function index()
    {
        $data = WhyChoseUs::orderBy('short_order')->get();
        return $this->success($data, 'Why Choose Us list fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'badge' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'title_meta' => 'nullable|string|max:255',
            'description_meta' => 'nullable|string',

            'status' => 'boolean',
            'short_order' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $data = WhyChoseUs::create([
            'badge' => $request->badge,
            'title' => $request->title,
            'description' => $request->description,

            'title_meta' => $request->title_meta,
            'description_meta' => $request->description_meta,

            'status' => $request->status ?? 1,
            'short_order' => $request->short_order ?? 0
        ]);

        return $this->success($data, 'Record created', 201);
    }

    public function show($id)
    {
        $data = WhyChoseUs::find($id);

        if (!$data) {
            return $this->error('Record not found', null, 404);
        }

        return $this->success($data, 'Record fetched');
    }

    public function update(Request $request, $id)
    {
        $data = WhyChoseUs::find($id);

        if (!$data) {
            return $this->error('Record not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'badge' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'title_meta' => 'nullable|string|max:255',
            'description_meta' => 'nullable|string',

            'status' => 'boolean',
            'short_order' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $data->update([
            'badge' => $request->badge,
            'title' => $request->title,
            'description' => $request->description,

            'title_meta' => $request->title_meta,
            'description_meta' => $request->description_meta,

            'status' => $request->status ?? $data->status,
            'short_order' => $request->short_order ?? $data->short_order
        ]);

        return $this->success($data, 'Record updated');
    }

    public function destroy($id)
    {
        $data = WhyChoseUs::find($id);

        if (!$data) {
            return $this->error('Record not found', null, 404);
        }

        $data->delete();

        return $this->success(null, 'Record deleted');
    }


    // frontend api
    public function userIndex()
    {
        $data = WhyChoseUs::where('status', true)
                ->orderBy('short_order')
                ->get();

        return $this->success($data, 'Why Choose Us fetched');
    }

}