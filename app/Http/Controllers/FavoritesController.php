<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favoriteBusinesses()->with('categories')->paginate(12);
        return view('favorites.index', compact('favorites'));
    }

    public function store(Business $business)
    {
        auth()->user()->favoriteBusinesses()->attach($business->id);
        return back()->with('success', 'Business added to favorites.');
    }

    public function destroy(Business $business)
    {
        auth()->user()->favoriteBusinesses()->detach($business->id);
        return back()->with('success', 'Business removed from favorites.');
    }

    public function toggle(Business $business)
    {
        $user = auth()->user();

        if ($user->favoriteBusinesses->contains($business->id)) {
            $user->favoriteBusinesses()->detach($business->id);
            $message = 'Business removed from favorites.';
        } else {
            $user->favoriteBusinesses()->attach($business->id);
            $message = 'Business added to favorites.';
        }

        return back()->with('success', $message);
    }
}
