<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Blog;
use App\Models\Website\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogController extends BaseController
{
    /**
     * Public: Get active blogs (listing)
     */
    public function index()
    {
        $blogs = Blog::where('is_active', true)
            ->orderBy('id', 'desc')
            ->get();

        $faq = Faq::where('faq_slug', 'blog')
            ->where('is_active', true)
            ->get(); 
        $data = [
            'blogs' => $blogs,
            'faq'   => $faq,
        ];      

        return $this->success($data, 'Blogs fetched successfully');
    }

 
    public function showBySlug($slug)
    {
        $blog = Blog::where('title_slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$blog) {
            return $this->error('Blog not found', [], 404);
        }

        $related_blog = Blog::where('title_slug', '!=', $slug)
            ->where('is_active', true)
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get();

        $faq = Faq::where('faq_slug', $slug)
            ->where('is_active', true)
            ->get();

       
        $blog->related_blog = $related_blog;
        $blog->faq = $faq;

        return $this->success($blog, 'Blog fetched successfully');
    }


  
    public function adminIndex()
    {
        $blogs = Blog::orderBy('id', 'desc')->get();

        return $this->success($blogs, 'Blog list fetched successfully');
    }

    /**
     * Admin: Store blog
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'title_meta'       => 'nullable|string|max:255',
            'short_desc'       => 'nullable|string',
            'short_desc_meta'  => 'nullable|string',
            'long_desc'        => 'required|string',
            'long_desc_meta'   => 'nullable|string',
            'web_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mobile_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_alt'        => 'nullable|string',
            'youtube_link'     => 'nullable|string|url',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $webImagePath = null;
        $mobileImagePath = null;

        if ($request->hasFile('web_image')) {
            $webImagePath = $this->uploadImage($request->file('web_image'));
        }

        if ($request->hasFile('mobile_image')) {
            $mobileImagePath = $this->uploadImage($request->file('mobile_image'));
        }

        $blog = Blog::create([
            'title'           => $request->title,
            'title_slug'      => Str::slug($request->title),
            'title_meta'      => $request->title_meta,
            'short_desc'      => $request->short_desc,
            'short_desc_meta' => $request->short_desc_meta,
            'long_desc'       => $request->long_desc,
            'long_desc_meta'  => $request->long_desc_meta,
            'web_image'       => $webImagePath,
            'mobile_image'    => $mobileImagePath,
            'image_alt'       => $request->image_alt,
            'youtube_link'    => $request->youtube_link,
            'is_active'       => $request->is_active ?? true,
        ]);

        return $this->success($blog, 'Blog created successfully');
    }


    /**
     * Admin: Show blog by ID
     */
    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->error('Blog not found', [], 404);
        }

        return $this->success($blog, 'Blog fetched successfully');
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->error('Blog not found', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'title'            => 'required|string|max:255',
            'title_meta'       => 'nullable|string|max:255',
            'short_desc'       => 'nullable|string',
            'short_desc_meta'  => 'nullable|string',
            'long_desc'        => 'required|string',
            'long_desc_meta'   => 'nullable|string',
            'web_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'mobile_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_alt'        => 'nullable|string',
            'youtube_link'     => 'nullable|string|url',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        // Replace web image
        if ($request->hasFile('web_image')) {
            if ($blog->web_image && Storage::disk('public')->exists($blog->web_image)) {
                Storage::disk('public')->delete($blog->web_image);
            }
            $blog->web_image = $this->uploadImage($request->file('web_image'));
        }

        // Replace mobile image
        if ($request->hasFile('mobile_image')) {
            if ($blog->mobile_image && Storage::disk('public')->exists($blog->mobile_image)) {
                Storage::disk('public')->delete($blog->mobile_image);
            }
            $blog->mobile_image = $this->uploadImage($request->file('mobile_image'));
        }

        $blog->update([
            'title'           => $request->title,
            'title_slug'      => Str::slug($request->title),
            'title_meta'      => $request->title_meta,
            'short_desc'      => $request->short_desc,
            'short_desc_meta' => $request->short_desc_meta,
            'long_desc'       => $request->long_desc,
            'long_desc_meta'  => $request->long_desc_meta,
            'image_alt'       => $request->image_alt,
            'youtube_link'    => $request->youtube_link,
            'is_active'       => $request->is_active ?? $blog->is_active,
        ]);

        return $this->success($blog, 'Blog updated successfully');
    }



    /**
     * Admin: Delete blog
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->error('Blog not found', [], 404);
        }

        $blog->delete();

        return $this->success([], 'Blog deleted successfully');
    }

    /**
     * Admin: Toggle status
     */
    public function toggleStatus($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return $this->error('Blog not found', [], 404);
        }

        $blog->is_active = !$blog->is_active;
        $blog->save();

        return $this->success($blog, 'Blog status updated');
    }

    private function uploadImage($file, $folder = 'blogs')
    {
        return $file->store($folder, 'public');
    }

}