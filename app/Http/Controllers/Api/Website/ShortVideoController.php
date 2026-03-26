<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\ShortVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ShortVideoController extends BaseController
{
    public function userIndex()
    {
        $videos = ShortVideo::where('status', true)->get();
        return $this->success($videos, 'Short videos list');
    }
    
    public function index()
    {
        $videos = ShortVideo::latest()->get();
        return $this->success($videos, 'Short videos list');
    }

    /**
     * Store new video
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video_title' => 'nullable|string|max:255',

            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:50240',
            'youtube_link' => 'nullable|url',

            'button_name' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',

            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $videoPath = null;

        // Upload video
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('short_videos', 'public');
        }

        $video = ShortVideo::create([
            'video_title' => $request->video_title,
            'video' => $videoPath,
            'youtube_link' => $request->youtube_link,
            'button_name' => $request->button_name,
            'button_url' => $request->button_url,
            'status' => $request->status ?? 1,
        ]);

        return $this->success($video, 'Short video created');
    }

    /**
     * Show single video
     */
    public function show($id)
    {
        $video = ShortVideo::find($id);

        if (!$video) {
            return $this->error('Video not found', null, 404);
        }

        return $this->success($video, 'Video details');
    }

    /**
     * Update video
     */
    public function update(Request $request, $id)
    {
        $video = ShortVideo::find($id);

        if (!$video) {
            return $this->error('Video not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'video_title' => 'nullable|string|max:255',

            'video' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-msvideo|max:50240',
            'youtube_link' => 'nullable|url',

            'button_name' => 'nullable|string|max:255',
            'button_url' => 'nullable|string|max:255',

            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $videoPath = $video->video;

        // Update video file
        if ($request->hasFile('video')) {

            if ($video->video && Storage::disk('public')->exists($video->video)) {
                Storage::disk('public')->delete($video->video);
            }

            $videoPath = $request->file('video')->store('short_videos', 'public');
        }

        $video->update([
            'video_title' => $request->video_title ?? $video->video_title,
            'video' => $videoPath,
            'youtube_link' => $request->youtube_link ?? $video->youtube_link,
            'button_name' => $request->button_name ?? $video->button_name,
            'button_url' => $request->button_url ?? $video->button_url,
            'status' => $request->status ?? $video->status,
        ]);

        return $this->success($video, 'Short video updated');
    }

    /**
     * Delete video
     */
    public function destroy($id)
    {
        $video = ShortVideo::find($id);

        if (!$video) {
            return $this->error('Video not found', null, 404);
        }

        // Delete file
        if ($video->video && Storage::disk('public')->exists($video->video)) {
            Storage::disk('public')->delete($video->video);
        }

        $video->delete();

        return $this->success(null, 'Short video deleted');
    }
}