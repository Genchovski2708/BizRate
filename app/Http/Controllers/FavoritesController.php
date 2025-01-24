<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Metadata;
use App\Models\Business;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class FavoritesController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $favorites = Favorite::with(['metadata', 'user', 'business'])->get();
        return view('favorites.index', compact('favorites'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        $users = User::all();
        $businesses = Business::all();
        return view('favorites.create', compact('users', 'businesses'));
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'business_id' => 'required|exists:businesses,id',
        ]);

        // Create metadata for the favorite
        $metadata = Metadata::create([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create the favorite entry
        Favorite::create([
            'user_id' => $request->user_id,
            'business_id' => $request->business_id,
            'metadata_id' => $metadata->id, // Link the metadata ID
        ]);

        return redirect()->route('favorites.index')->with('success', 'Favorite added successfully!');
    }

    // Display the specified resource.
    public function show(Favorite $favorite)
    {
        return view('favorites.show', compact('favorite'));
    }

    // Show the form for editing the specified resource.
    public function edit(Favorite $favorite)
    {
        $users = User::all();
        $businesses = Business::all();
        return view('favorites.edit', compact('favorite', 'users', 'businesses'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Favorite $favorite)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'business_id' => 'required|exists:businesses,id',
        ]);

        $favorite->update([
            'user_id' => $request->user_id,
            'business_id' => $request->business_id,
        ]);

        // Update the metadata's updated_at timestamp
        $favorite->metadata->update(['updated_at' => Carbon::now()]);

        return redirect()->route('favorites.index')->with('success', 'Favorite updated successfully!');
    }

    // Remove the specified resource from storage.
    public function destroy(Favorite $favorite)
    {
        // Delete the associated metadata entry
        $favorite->metadata->delete();
        $favorite->delete();

        return redirect()->route('favorites.index')->with('success', 'Favorite removed successfully!');
    }
}
