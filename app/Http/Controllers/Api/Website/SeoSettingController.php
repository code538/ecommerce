<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\SeoSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SeoSettingController extends BaseController
{

    public function show(string $pageKey)
    {
        $seo = SeoSetting::where('page_key', $pageKey)
            ->where('is_active', true)
            ->first();

        if (! $seo) {
            return $this->error('SEO not found for this page', null, 404);
        }

        return $this->success($seo, 'SEO data fetched');
    }

    /**
     * List all SEO settings (Admin)
     * GET /api/admin/seo
     */
    public function index()
    {
        $seo = SeoSetting::latest()->get();
        return $this->success($seo, 'SEO list fetched');
    }

  
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_key'          => 'required|string|unique:seo_settings,page_key',
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'og_title'          => 'nullable|string|max:255',
            'og_description'    => 'nullable|string',
            'og_image'          => 'nullable|string',
            'canonical_url'     => 'nullable|url',
            'robots'            => 'nullable|string',
            'schema_json'       => 'nullable|array',
            'is_active'         => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $seo = SeoSetting::create($request->all());

        return $this->success($seo, 'SEO settings created', 201);
    }

    public function edit(int $id){
        $seo = SeoSetting::find($id);
        if (! $seo) {
            return $this->error('SEO record not found', null, 404);
        }
        return $this->success($seo, 'SEO settings updated');
    }


    public function update(Request $request, int $id)
    {
        $seo = SeoSetting::find($id);

        if (! $seo) {
            return $this->error('SEO record not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'page_key'          => 'required|string|unique:seo_settings,page_key,' . $id,
            'meta_title'        => 'nullable|string|max:255',
            'meta_description'  => 'nullable|string',
            'meta_keywords'     => 'nullable|string',
            'og_title'          => 'nullable|string|max:255',
            'og_description'    => 'nullable|string',
            'og_image'          => 'nullable|string',
            'canonical_url'     => 'nullable|url',
            'robots'            => 'nullable|string',
            'schema_json'       => 'nullable|array',
            'is_active'         => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $seo->update($request->all());

        return $this->success($seo, 'SEO settings updated');
    }

    /**
     * Delete SEO settings (Admin)
     * DELETE /api/admin/seo/{id}
     */
    public function destroy(int $id)
    {
        $seo = SeoSetting::find($id);

        if (! $seo) {
            return $this->error('SEO record not found', null, 404);
        }

        $seo->delete();

        return $this->success(null, 'SEO settings deleted');
    }
}
