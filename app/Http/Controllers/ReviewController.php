<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\User;
use App\Models\Business;
use App\Models\Metadata;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
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
        $users = User::all(); // Fetch all users to populate the dropdown

        return view('reviews.create', compact('businesses', 'users'));
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

        // Get the first test user
        $testUser = User::first();

        // Check if a user is found, or set a default
        if ($testUser) {
            $userId = $testUser->id;
        } else {
            $userId = 1; // Default to a fallback user_id for testing purposes
        }

        // Create a new metadata record with current timestamps
        $metadata = Metadata::create([
            'created_at' => Carbon::now(), // Current datetime for created_at
            'updated_at' => Carbon::now(), // Current datetime for updated_at
        ]);

        // Create the new review with the required fields, including metadata_id and user_id
        Review::create([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'user_id' => $userId, // Assign the test user's ID
            'business_id' => $request->business_id,
            'metadata_id' => $metadata->id, // Use the created metadata's ID
        ]);

        return redirect()->route('reviews.index');
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
        // Validate the request input fields
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        $review->update($request->all());
        return redirect()->route('reviews.index');
    }

    // Remove the specified resource from storage.
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('reviews.index');
    }
}
