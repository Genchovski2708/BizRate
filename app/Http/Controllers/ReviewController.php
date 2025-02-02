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
    // Display a listing of the resource.
    public function index()
    {
        $reviews = Review::all();
        return view('reviews.index', compact('reviews'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $businesses = Business::all(); // Get all businesses
        return view('reviews.create', compact('businesses'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validate the request input fields
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'business_id' => 'required|exists:businesses,id',
        ]);

        $user = Auth::user();
        $businessId = $request->business_id;

        // Check if the user has already reviewed this business
        $review = Review::where('user_id', $user->id)
            ->where('business_id', $businessId)
            ->first();

        if ($review) {
            // Update existing review
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
        } else {
            // Create a new review
            Review::create([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'user_id' => $user->id,
                'business_id' => $businessId,
            ]);
        }

        // Recalculate the business's average rating
        $this->updateBusinessRating($businessId);

        return back()->with('success', 'Review created successfully.');
    }

    // Helper method to update the business rating
    private function updateBusinessRating($businessId)
    {
        $business = Business::find($businessId);

        if ($business) {
            // Check if there are any reviews left
            $averageRating = Review::where('business_id', $businessId)->avg('rating');

            // If no reviews are left, set a default value (e.g., 0)
            if ($averageRating === null) {
                $averageRating = 0;
            }

            $business->update(['average_rating' => $averageRating]);
        }
    }



    // Display the specified resource.
    public function show(Review $review)
    {
        return view('reviews.show', compact('review'));
    }

    // Show the form for editing the specified resource.
    public function edit(Review $review)
    {
        $businesses = Business::all();
        return view('reviews.edit', compact('review', 'businesses'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Review $review)
    {
        // Ensure user can update the review
        $this->authorize('update', $review);  // This will call the ReviewPolicy's update method

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Recalculate business rating
        $this->updateBusinessRating($review->business_id);

        return back()->with('success', 'Review updated successfully.');
    }


    // Remove the specified resource from storage.
    public function destroy(Review $review)
    {
        // Ensure user can delete the review
        $this->authorize('delete', $review);  // This will call the ReviewPolicy's delete method

        $businessId = $review->business_id;
        $review->delete();

        // Recalculate business rating
        $this->updateBusinessRating($businessId);

        return back()->with('success', 'Review deleted successfully.');
    }
}
