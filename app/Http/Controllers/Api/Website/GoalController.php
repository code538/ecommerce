<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GoalController extends BaseController
{

    public function index()
    {
        $goals = Goal::orderBy('sort_order')->get();
        return $this->success($goals, 'Goals fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',

            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_alt' => 'nullable|string|max:255',

            'tag1' => 'nullable|string|max:255',
            'tag2' => 'nullable|string|max:255',
            'tag3' => 'nullable|string|max:255',

            'sort_order' => 'integer',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('goals', 'public');
        }

        $goal = Goal::create([
            'title' => $request->title,
            'description' => $request->description,

            'slug' => Str::slug($request->title),

            'image' => $imagePath,
            'image_alt' => $request->image_alt,

            'tags' => $request->tags,

            'sort_order' => $request->sort_order ?? 0,
            'status' => $request->status ?? 1
        ]);

        return $this->success($goal, 'Goal created');
    }

    public function show($id)
    {
        $goal = Goal::find($id);

        if (!$goal) {
            return $this->error('Goal not found', null, 404);
        }

        return $this->success($goal, 'Goal fetched');
    }

    public function update(Request $request, $id)
    {
        $goal = Goal::find($id);

        if (!$goal) {
            return $this->error('Goal not found', null, 404);
        }

        $imagePath = $goal->image;

        if ($request->hasFile('image')) {

            if ($goal->image && Storage::disk('public')->exists($goal->image)) {
                Storage::disk('public')->delete($goal->image);
            }

            $imagePath = $request->file('image')->store('goals', 'public');
        }

        $goal->update([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title),
            'image' => $imagePath,
            'image_alt' => $request->image_alt,

            'tag1' => $request->tag1,
            'tag2' => $request->tag2,
            'tag3' => $request->tag3,

            'sort_order' => $request->sort_order ?? $goal->sort_order,
            'status' => $request->status ?? $goal->status
        ]);

        return $this->success($goal, 'Goal updated');
    }

    public function destroy($id)
    {
        $goal = Goal::find($id);

        if (!$goal) {
            return $this->error('Goal not found', null, 404);
        }

        if ($goal->image && Storage::disk('public')->exists($goal->image)) {
            Storage::disk('public')->delete($goal->image);
        }

        $goal->delete();

        return $this->success(null, 'Goal deleted');
    }
}