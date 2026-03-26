<?php

namespace App\Http\Controllers\Api\Website;

use App\Http\Controllers\Api\BaseController;
use App\Models\Website\ProductReview;
use App\Models\Website\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductReviewController extends BaseController
{

    public function index()
    {
        $reviews = ProductReview::with('product')->latest()->get();
        return $this->success($reviews, 'Product reviews fetched');
    }

    public function showByProduct($product_id)
    {
        $reviews = ProductReview::where('product_id', $product_id)
            ->with('product')
            ->latest()
            ->get();

        if ($reviews->isEmpty()) {
            return $this->error('No reviews found for this product', null, 404);
        }

        return $this->success($reviews, 'Product reviews fetched');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',

            'review_type' => 'nullable|in:text,image,video,youtube',

            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'video' => 'nullable|mimes:mp4,mov,avi',
            'youtube_shorts' => 'nullable|url',

            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        // Handle uploads
        $imagePath = null;
        $videoPath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('reviews/images', 'public');
        }

        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('reviews/videos', 'public');
        }

        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'review' => $request->review,

            // ✅ NEW
            'review_type' => $request->review_type,
            'image' => $imagePath,
            'video' => $videoPath,
            'youtube_shorts' => $request->youtube_shorts,

            'status' => $request->status ?? 1
        ]);

        // Update product rating
        $product = Product::find($request->product_id);

        $avgRating = ProductReview::where('product_id', $product->id)
            ->where('status', 1)
            ->avg('rating');

        $reviewCount = ProductReview::where('product_id', $product->id)
            ->where('status', 1)
            ->count();

        $product->update([
            'rating' => $avgRating,
            'review_count' => $reviewCount
        ]);

        return $this->success($review, 'Review added', 201);
    }

    public function show(int $id)
    {
        $review = ProductReview::with('product')->find($id);

        if (!$review) {
            return $this->error('Review not found', null, 404);
        }

        return $this->success($review, 'Review fetched');
    }

    public function update(Request $request, int $id)
    {
        $review = ProductReview::find($id);

        if (!$review) {
            return $this->error('Review not found', null, 404);
        }

        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',

            'review_type' => 'nullable|in:text,image,video,youtube',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
            'video' => 'nullable|mimes:mp4,mov,avi',
            'youtube_shorts' => 'nullable|url',

            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->error('Validation error', $validator->errors(), 422);
        }

        // Handle uploads
        if ($request->hasFile('image')) {
            $review->image = $request->file('image')->store('reviews/images', 'public');
        }

        if ($request->hasFile('video')) {
            $review->video = $request->file('video')->store('reviews/videos', 'public');
        }

        $review->update([
            'customer_name' => $request->customer_name,
            'rating' => $request->rating,
            'review' => $request->review,

            'review_type' => $request->review_type,
            'youtube_shorts' => $request->youtube_shorts,

            'status' => $request->status ?? $review->status
        ]);

        return $this->success($review, 'Review updated');
    }

    public function destroy(int $id)
    {
        $review = ProductReview::find($id);

        if (!$review) {
            return $this->error('Review not found', null, 404);
        }

        $review->delete();

        return $this->success(null, 'Review deleted');
    }

    public function shortsReview()
    {
        $reviews = ProductReview::with('product')->where('review_type', 'video')->get();
        return $this->success($reviews, 'Product reviews fetched'); 
    }
}