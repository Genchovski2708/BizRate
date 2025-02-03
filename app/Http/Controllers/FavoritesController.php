<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function index()
    {
        // Change 'businesses()' to 'favoriteBusinesses()' to get only favorited businesses
        $favorites = auth()->user()->favoriteBusinesses()->with('categories')->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    public function store(Business $business)
    {
        // Use 'favoriteBusinesses()' instead of 'businesses()' for favoriting
        auth()->user()->favoriteBusinesses()->attach($business->id);
        return back()->with('success', 'Business added to favorites.');
    }

    public function destroy(Business $business)
    {
        // Use 'favoriteBusinesses()' instead of 'businesses()' for unfavoriting
        auth()->user()->favoriteBusinesses()->detach($business->id);
        return back()->with('success', 'Business removed from favorites.');
    }

    public function toggle(Business $business)
    {
        $user = auth()->user();

        // Check if the business is already favorited
        if ($user->favoriteBusinesses->contains($business->id)) {
            // Unfavorite the business
            $user->favoriteBusinesses()->detach($business->id);
            $message = 'Business removed from favorites.';
        } else {
            // Favorite the business
            $user->favoriteBusinesses()->attach($business->id);
            $message = 'Business added to favorites.';
        }

        return back()->with('success', $message);
    }
}
