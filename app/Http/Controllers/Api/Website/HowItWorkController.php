<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\HowItWork;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HowItWorkController extends BaseController
{

    public function index()
    {
        $data = HowItWork::orderBy('sort_order')->get();
        return $this->success($data, 'How It Works list fetched');
    }

    public function userIndex()
    {
        $data = HowItWork::where('status', true)->orderBy('sort_order')->get();

        if (!$data) {
            return $this->error('Record not found', null, 404);
        }

        return $this->success($data, 'Record fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_title' => 'required|string|max:255',
            'section_subtitle' => 'nullable|string|max:255',
            'tab_name' => 'nullable|string|max:255',

            'youtube_url' => 'nullable|url',
            'video_title' => 'nullable|string|max:255',

            'step1' => 'nullable|string|max:255',
            'step2' => 'nullable|string|max:255',
            'step3' => 'nullable|string|max:255',
            'step4' => 'nullable|string|max:255',

            'feature1' => 'nullable|string|max:255',
            'feature2' => 'nullable|string|max:255',
            'feature3' => 'nullable|string|max:255',
            'feature4' => 'nullable|string|max:255',

            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',

            'status' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $data = HowItWork::create([
            'section_title' => $request->section_title,
            'section_subtitle' => $request->section_subtitle,
            'tab_name' => $request->tab_name,

            'youtube_url' => $request->youtube_url,
            'video_title' => $request->video_title,

            'step1' => $request->step1,
            'step2' => $request->step2,
            'step3' => $request->step3,
            'step4' => $request->step4,

            'feature1' => $request->feature1,
            'feature2' => $request->feature2,
            'feature3' => $request->feature3,
            'feature4' => $request->feature4,

            'button_text' => $request->button_text,
            'button_link' => $request->button_link,

            'status' => $request->status ?? 1,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return $this->success($data, 'How It Works created', 201);
    }

    public function show($id)
    {
        $data = HowItWork::find($id);

        if (!$data) {
            return $this->error('Record not found', null, 404);
        }

        return $this->success($data, 'Record fetched');
    }

    public function update(Request $request, $id)
    {
        $data = HowItWork::find($id);

        if (!$data) {
            return $this->error('Record not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'section_title' => 'required|string|max:255',
            'section_subtitle' => 'nullable|string|max:255',
            'tab_name' => 'nullable|string|max:255',

            'youtube_url' => 'nullable|url',
            'video_title' => 'nullable|string|max:255',

            'step1' => 'nullable|string|max:255',
            'step2' => 'nullable|string|max:255',
            'step3' => 'nullable|string|max:255',
            'step4' => 'nullable|string|max:255',

            'feature1' => 'nullable|string|max:255',
            'feature2' => 'nullable|string|max:255',
            'feature3' => 'nullable|string|max:255',
            'feature4' => 'nullable|string|max:255',

            'button_text' => 'nullable|string|max:255',
            'button_link' => 'nullable|string|max:255',

            'status' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $data->update($request->all());

        return $this->success($data, 'How It Works updated');
    }

    public function destroy($id)
    {
        $data = HowItWork::find($id);

        if (!$data) {
            return $this->error('Record not found', null, 404);
        }

        $data->delete();

        return $this->success(null, 'Record deleted');
    }
}