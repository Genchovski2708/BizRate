<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use App\Models\Metadata;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    // Display a listing of the resource.
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
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'contact' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Create metadata for this business
        $metadata = Metadata::create([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Create the business entry
        Business::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'contact' => $request->contact,
            'category_id' => $request->category_id,
            'metadata_id' => $metadata->id, // Link the metadata ID
        ]);

        return redirect()->route('businesses.index');
    }

    // Display the specified resource.
    public function show(Business $business)
    {
        return view('businesses.show', compact('business'));
    }

    // Show the form for editing the specified resource.
    public function edit(Business $business)
    {
        $categories = Category::all(); // Fetch all categories for the dropdown
        return view('businesses.edit', compact('business', 'categories'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Business $business)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'contact' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Update the business entry
        $business->update($request->all());

        // Update the associated metadata timestamp
        $business->metadata->update(['updated_at' => Carbon::now()]);

        return redirect()->route('businesses.index');
    }

    // Remove the specified resource from storage.
    public function destroy(Business $business)
    {
        // Delete the associated metadata entry
        $business->metadata->delete();
        $business->delete();

        return redirect()->route('businesses.index');
    }
}
