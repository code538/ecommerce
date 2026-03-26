<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CmsPageController extends BaseController
{
    // ✅ List all pages
    public function index()
    {
        $pages = CmsPage::latest()->get();
        return $this->success($pages, 'CMS pages fetched');
    }

    // ✅ Show single page
    public function show($id)
    {
        $page = CmsPage::find($id);

        if (!$page) {
            return $this->error('Page not found', null, 404);
        }

        return $this->success($page, 'Page fetched');
    }

    // ✅ Store (Create)
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|string|max:255|unique:cms_pages,page_name',
            'page_heading' => 'nullable|string|max:255',
            'short_desc' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'image_alt' => 'nullable|string|max:255',
            'image_align' => 'required|in:left,right,center',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $slug = Str::slug($request->page_name);


        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('cms', 'public');
        }

        $page = CmsPage::create([
            'page_name' => $request->page_name,
            'slug' => $slug,
            'page_heading' => $request->page_heading,
            'short_desc' => $request->short_desc,
            'description' => $request->description,
            'image' => $imagePath,
            'image_alt' => $request->image_alt,
            'image_align' => $request->image_align,
            'status' => $request->status ?? 1
        ]);

        return $this->success($page, 'Page created', 201);
    }

    // ✅ Update
    public function update(Request $request, $id)
    { 
        $page = CmsPage::find($id);

        if (!$page) {
            return $this->error('Page not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'page_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cms_pages', 'page_name')->ignore($page->id)
            ],
            'page_heading' => 'nullable|string|max:255',
            'short_desc' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'image_alt' => 'nullable|string|max:255',
            'image_align' => 'required|in:left,right,center',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        // ✅ Only update slug if page_name changed
        $slug = $page->slug;

        if ($request->page_name !== $page->page_name) {
            $newSlug = Str::slug($request->page_name);

            if (CmsPage::where('slug', $newSlug)->where('id', '!=', $id)->exists()) {
                return $this->error('Slug already exists. Try different page name.', null, 422);
            }

            $slug = $newSlug;
        }

        // Upload new image
        if ($request->hasFile('image')) {
            $page->image = $request->file('image')->store('cms', 'public');
        }

        $page->update([
            'page_name' => $request->page_name,
            'slug' => $slug,
            'page_heading' => $request->page_heading,
            'short_desc' => $request->short_desc,
            'description' => $request->description,
            'image_alt' => $request->image_alt,
            'image_align' => $request->image_align,
            'status' => $request->status ?? $page->status
        ]);

        return $this->success($page, 'Page updated');
    }

  
    public function destroy($id)
    {
        $page = CmsPage::find($id);

        if (!$page) {
            return $this->error('Page not found', null, 404);
        }

        $page->delete();

        return $this->success(null, 'Page deleted');
    }

    public function userIndex()
    {
        $pages = CmsPage::select('page_name','slug')->get();
        return $this->success($pages, 'CMS pages fetched');
    }

    public function cmsDetails($slug)
    {
        $pages = CmsPage::where('slug', $slug)->first();
        if (!$pages) {
            return $this->error('Page not found', null, 404);
        }
        return $this->success($pages, 'CMS pages fetched');
    }
}