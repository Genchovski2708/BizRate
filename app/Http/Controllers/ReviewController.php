<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Business;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import the Auth facade

class ReviewController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $reviews = Review::all();
        return view('reviews.index', compact('reviews'));
    }

    public function create()
    {
        $businesses = Business::all();
        return view('reviews.create', compact('businesses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'business_id' => 'required|exists:businesses,id',
        ]);

        $user = Auth::user();
        $businessId = $request->business_id;

        $review = Review::where('user_id', $user->id)
            ->where('business_id', $businessId)
            ->first();

        if ($review) {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            Review::create([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'user_id' => $user->id,
                'business_id' => $businessId,
            ]);
        }

        $this->updateBusinessRating($businessId);

        return back()->with('success', 'Review created successfully.');
    }

    private function updateBusinessRating($businessId)
    {
        $business = Business::find($businessId);

        if ($business) {
            $averageRating = Review::where('business_id', $businessId)->avg('rating');

            if ($averageRating === null) {
                $averageRating = 0;
            }

            $business->update(['average_rating' => $averageRating]);
        }
    }



    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        $businesses = Business::all();
        return view('reviews.edit', compact('review', 'businesses'));
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $this->updateBusinessRating($review->business_id);

        return back()->with('success', 'Review updated successfully.');
    }

    public function updateJson(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($validated);

        $this->updateBusinessRating($review->business_id);

        return response()->json([
            'success' => true,
            'rating' => $review->rating,
            'comment' => $review->comment,
        ]);
    }

    public function destroy(Review $review)
    {
        // Ensure user can delete the review
        $this->authorize('delete', $review);

        $businessId = $review->business_id;
        $review->delete();

        $this->updateBusinessRating($businessId);

        return back()->with('success', 'Review deleted successfully.');
    }
    public function myReviews()
    {
        $reviews = auth()->user()->reviews()->with('business')->paginate(10);
        return view('reviews.my-reviews', compact('reviews'));
    }

}
