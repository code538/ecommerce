<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class BannerController extends BaseController
{

    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return $this->success($banners, 'Banner list fetched');
    }

    public function userIndex()
    {   
        $banners = Banner::where('status', true)->orderBy('sort_order')->get();
        return $this->success($banners, 'Banner list fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'badge_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',

            'video' => 'nullable|mimes:mp4,mov,avi|max:10240',
            'youtube_url' => 'nullable|url',
            'banner_type' => 'required|in:image,video,youtube',

            'button1_text' => 'nullable|string|max:255',
            'button1_link' => 'nullable|string|max:255',

            'button2_text' => 'nullable|string|max:255',
            'button2_link' => 'nullable|string|max:255',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'status' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $imagePath = null;
        $videoPath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('banners', 'public');
        }
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('banners/videos', 'public');
        }

        $banner = Banner::create([
            'badge_text' => $request->badge_text,
            'title' => $request->title,
            'description' => $request->description,

            'image' => $imagePath,
            'image_alt' => $request->image_alt,

            'video' => $videoPath,
            'youtube_url' => $request->youtube_url,
            'banner_type' => $request->banner_type,

            'button1_text' => $request->button1_text,
            'button1_link' => $request->button1_link,

            'button2_text' => $request->button2_text,
            'button2_link' => $request->button2_link,

            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,

            'status' => $request->status ?? 1,
            'sort_order' => $request->sort_order ?? 0
        ]);

        return $this->success($banner, 'Banner created', 201);
    }

    public function show(int $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return $this->error('Banner not found', null, 404);
        }

        return $this->success($banner, 'Banner fetched');
    }

    public function update(Request $request, int $id)
    {   
        //dd($request->hasFile('video'));
        $banner = Banner::find($id);

        if (!$banner) {
            return $this->error('Banner not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'badge_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',

            //'video' => 'nullable|file|mimes:mp4,mov,avi|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:10240',
            'youtube_url' => 'nullable|url',
            'banner_type' => 'required|in:image,video,youtube',

            'button1_text' => 'nullable|string|max:255',
            'button1_link' => 'nullable|string|max:255',

            'button2_text' => 'nullable|string|max:255',
            'button2_link' => 'nullable|string|max:255',

            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',

            'status' => 'boolean',
            'sort_order' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $imagePath = $banner->image;
        $videoPath = $banner->video;

        if ($request->hasFile('image')) {

            if ($banner->image && Storage::disk('public')->exists($banner->image)) {
                Storage::disk('public')->delete($banner->image);
            }

            $imagePath = $request->file('image')->store('banners', 'public');
        }
        if ($request->hasFile('video')) {

            if ($banner->video && Storage::disk('public')->exists($banner->video)) {
                Storage::disk('public')->delete($banner->video);
            }

            $videoPath = $request->file('video')->store('banners/videos', 'public');
            //dd($videoPath);
        }

        $banner->update([
            'badge_text' => $request->badge_text,
            'title' => $request->title,
            'description' => $request->description,

            'image' => $imagePath,
            'image_alt' => $request->image_alt,

            'video' => $videoPath,
            'youtube_url' => $request->youtube_url,
            'banner_type' => $request->banner_type,

            'button1_text' => $request->button1_text,
            'button1_link' => $request->button1_link,

            'button2_text' => $request->button2_text,
            'button2_link' => $request->button2_link,

            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords,

            'status' => $request->status ?? $banner->status,
            'sort_order' => $request->sort_order ?? $banner->sort_order
        ]);

        return $this->success($banner, 'Banner updated');
    }

    public function destroy(int $id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return $this->error('Banner not found', null, 404);
        }

        if ($banner->image && Storage::disk('public')->exists($banner->image)) {
            Storage::disk('public')->delete($banner->image);
        }

        if ($banner->video && Storage::disk('public')->exists($banner->video)) {
            Storage::disk('public')->delete($banner->video);
        }

        $banner->delete();

        return $this->success(null, 'Banner deleted');
    }
}