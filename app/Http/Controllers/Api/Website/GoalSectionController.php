<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\GoalSection;
use App\Models\Website\Goal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GoalSectionController extends BaseController
{

    public function index()
    {
        $sections = GoalSection::latest()->get();
        return $this->success($sections, 'Goal sections fetched');
    }

    public function userIndex()
    {
        $sections = GoalSection::where('status', true)->latest()->get();

        $goals = Goal::where('status', true)->get();
        
        $data = [
            'sections' => $sections,
            'goals' => $goals
        ];

        return $this->success($data, 'Goal sections and goals fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'badge_text' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $section = GoalSection::create([
            'badge_text' => $request->badge_text,
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 1
        ]);

        return $this->success($section, 'Goal section created');
    }

    public function show($id)
    {
        $section = GoalSection::find($id);

        if (!$section) {
            return $this->error('Section not found', null, 404);
        }

        return $this->success($section, 'Goal section fetched');
    }

    public function update(Request $request, $id)
    {
        $section = GoalSection::find($id);

        if (!$section) {
            return $this->error('Section not found', null, 404);
        }

        $section->update($request->all());

        return $this->success($section, 'Goal section updated');
    }

    public function destroy($id)
    {
        $section = GoalSection::find($id);

        if (!$section) {
            return $this->error('Section not found', null, 404);
        }

        $section->delete();

        return $this->success(null, 'Goal section deleted');
    }
}