<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FaqController extends BaseController
{
  
    public function getFaqs(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faq_type' => 'required|string',
            'faq_slug' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $faqs = Faq::where('faq_type', $request->faq_type)
            ->where('faq_slug', $request->faq_slug)
            ->where('is_active', true)
            ->orderBy('id', 'asc')
            ->get();

        return $this->success($faqs, 'FAQs fetched successfully');
    }

    /**
     * Admin: List all FAQs
     */
    public function index()
    {
        $faqs = Faq::orderBy('id', 'desc')->get();

        return $this->success($faqs, 'FAQ list fetched successfully');
    }

    /**
     * Admin: Store FAQ
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'faq_type'         => 'required|string|max:100',
            'faq_question'     => 'required|string',
            'question_meta'    => 'nullable|string',
            'faq_answer'       => 'required|string',
            'faq_answer_meta'  => 'nullable|string',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $faq = Faq::create([
            'faq_type'        => $request->faq_type,
            'faq_slug'        => Str::slug($request->faq_type),
            'faq_question'    => $request->faq_question,
            'question_meta'   => $request->question_meta,
            'faq_answer'      => $request->faq_answer,
            'faq_answer_meta' => $request->faq_answer_meta,
            'is_active'       => $request->is_active ?? true,
        ]);

        return $this->success($faq, 'FAQ created successfully');
    }

    /**
     * Admin: Show single FAQ
     */
    public function show($id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->error('FAQ not found', [], 404);
        }

        return $this->success($faq, 'FAQ fetched successfully');
    }

    /**
     * Admin: Update FAQ
     */
    public function update(Request $request, $id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->error('FAQ not found', [], 404);
        }

        $validator = Validator::make($request->all(), [
            'faq_type'         => 'required|string|max:100',
            'faq_question'     => 'required|string',
            'question_meta'    => 'nullable|string',
            'faq_answer'       => 'required|string',
            'faq_answer_meta'  => 'nullable|string',
            'is_active'        => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        $faq->update([
            'faq_type'        => $request->faq_type,
            'faq_slug'        => Str::slug($request->faq_type),
            'faq_question'    => $request->faq_question,
            'question_meta'   => $request->question_meta,
            'faq_answer'      => $request->faq_answer,
            'faq_answer_meta' => $request->faq_answer_meta,
            'is_active'       => $request->is_active ?? $faq->is_active,
        ]);

        return $this->success($faq, 'FAQ updated successfully');
    }

    /**
     * Admin: Delete FAQ
     */
    public function destroy($id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->error('FAQ not found', [], 404);
        }

        $faq->delete();

        return $this->success([], 'FAQ deleted successfully');
    }

    /**
     * Admin: Toggle FAQ status
     */
    public function toggleStatus($id)
    {
        $faq = Faq::find($id);

        if (!$faq) {
            return $this->error('FAQ not found', [], 404);
        }

        $faq->is_active = !$faq->is_active;
        $faq->save();

        return $this->success($faq, 'FAQ status updated');
    }
}
