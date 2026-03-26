<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\AboutPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AboutPageController extends BaseController
{
    // ✅ Get About Page (Single)
    public function index()
    {
        $about = AboutPage::get();
        return $this->success($about, 'About page fetched');
    }

    // ✅ Store / Create
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'nullable|string|max:255',
            'page_desc' => 'nullable|string',
            'heading' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'heading_meta' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'desc_meta' => 'nullable|string',
            'image_alt' => 'nullable|string|max:255',

            // images
            'page_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        // Upload images
        $pageImage = null;
        $image = null;

        if ($request->hasFile('page_image')) {
            $pageImage = $request->file('page_image')->store('about/page', 'public');
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('about/section', 'public');
        }

        $about = AboutPage::create([
            'page_name' => $request->page_name,
            'page_desc' => $request->page_desc,
            'page_image' => $pageImage,
            'heading' => $request->heading,
            'badge_text' => $request->badge_text,
            'heading_meta' => $request->heading_meta,
            'description' => $request->description,
            'desc_meta' => $request->desc_meta,
            'image' => $image,
            'image_alt' => $request->image_alt,
        ]);

        return $this->success($about, 'About page created', 201);
    }

    public function edit($id)
    {
        $about = AboutPage::find($id);

        if (!$about) {
            return $this->error('About page not found', null, 404);
        }

        return $this->success($about, 'About page get sucessfuly');
    }

    
    public function update(Request $request, $id)
    {
        $about = AboutPage::find($id);

        if (!$about) {
            return $this->error('About page not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'page_name' => 'nullable|string|max:255',
            'page_desc' => 'nullable|string',
            'heading' => 'nullable|string|max:255',
            'badge_text' => 'nullable|string|max:255',
            'heading_meta' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'desc_meta' => 'nullable|string',
            'image_alt' => 'nullable|string|max:255',

            'page_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        // Upload new images if exists
        if ($request->hasFile('page_image')) {
            $about->page_image = $request->file('page_image')->store('about/page', 'public');
        }

        if ($request->hasFile('image')) {
            $about->image = $request->file('image')->store('about/section', 'public');
        }

        $about->update([
            'page_name' => $request->page_name,
            'page_desc' => $request->page_desc,
            'heading' => $request->heading,
            'badge_text' => $request->badge_text,
            'heading_meta' => $request->heading_meta,
            'description' => $request->description,
            'desc_meta' => $request->desc_meta,
            'image_alt' => $request->image_alt
        ]);

        return $this->success($about, 'About page updated');
    }

    // ✅ Delete
    public function destroy($id)
    {
        $about = AboutPage::find($id);

        if (!$about) {
            return $this->error('About page not found', null, 404);
        }

        $about->delete();

        return $this->success(null, 'Deleted successfully');
    }

    public function userIndex()
    {
        $about = AboutPage::get();

        if (!$about) {
            return $this->error('About page not found', null, 404);
        }

        return $this->success($about, 'About page fetched');
    }
}    
