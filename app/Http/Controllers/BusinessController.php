<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
class BusinessController extends Controller
{
    use AuthorizesRequests;

    // Display a listing of the resource.

    // Welcome page to display all businesses with their average ratings
    public function welcome()
    {
        // Fetch all businesses with their reviews and calculate average ratings
        $businesses = Business::with('reviews')->get()->map(function ($business) {
            $business->average_rating = $business->reviews->avg('rating');
            return $business;
        });

        return view('welcome', compact('businesses'));
    }

    public function index()
    {
        $businesses = Business::all();
        return view('businesses.index', compact('businesses'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $categories = Category::all(); // Fetch all categories
        return view('businesses.create', compact('categories'));
    }

    // Store a newly created resource in storage.


    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'contact' => 'nullable|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();

        // Handle file upload
        $photoPath = $request->hasFile('photo') ?
            $request->file('photo')->store('businesses', 'public') : null;

        // Create the business with user_id
        $business = Business::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'contact' => $request->contact,
            'photo' => $photoPath,
            'user_id' => $user->id, // Associate business with the logged-in user
        ]);

        // Attach categories
        $business->categories()->attach($request->categories);

        return redirect('/')->with('success', 'Business created successfully.');
    }


    // Display the specified resource.
    public function show(Business $business)
    {
        $business->load('reviews.user'); // Load reviews with their associated users
        $business->average_rating = $business->reviews->avg('rating');
        return view('businesses.show', compact('business'));
    }

    // Show the form for editing the specified resource.
    public function edit(Business $business)
    {
        $this->authorize('update', $business); // Ensure only authorized users can edit
        $categories = Category::all();
        return view('businesses.edit', compact('business', 'categories'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Business $business)
    {
        // Ensure only the owner or an admin can update
        $this->authorize('update', $business);

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'contact' => 'nullable|string',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Store new photo
            $photoPath = $request->file('photo')->store('businesses', 'public');

            // Delete old photo if it exists
            if ($business->photo) {
                Storage::disk('public')->delete($business->photo);
            }

            // Save new photo path
            $business->photo = $photoPath;
        }

        // Update business details
        $business->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'contact' => $request->contact,
            'photo' => $business->photo, // Ensure this gets updated
        ]);

        // Sync categories
        $business->categories()->sync($request->categories);

        return redirect()->route('businesses.show', $business)->with('success', 'Business updated successfully!');
    }



    // Remove the specified resource from storage.
    public function destroy(Business $business)
    {

        $business->delete();

        return redirect()->route('businesses.index');
    }
    public function userBusinesses()
    {
        // Fetch only businesses owned by the user (HasMany relationship)
        $businesses = auth()->user()->businesses()->paginate(10);
        return view('businesses.user-index', compact('businesses'));
    }
}
