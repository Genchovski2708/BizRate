<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Metadata;
use Carbon\Carbon;
class CategoryController extends Controller
{
    // Display a listing of the resource.
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));
    }

    // Show the form for creating a new resource.
    public function create()
    {
        return view('categories.create');
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // Validate the input fields
        $request->validate([
            'name' => 'required|string',
        ]);

        // Get the first user or the one that matches some condition
        $testUser = User::first();  // Use the first user in the table or apply a specific condition

        // Check if a user is found, or set a default
        if ($testUser) {
            $userId = $testUser->id;
        } else {
            // If no test user is available (just as a fallback), set a default user_id
            $userId = 1;  // Or any default user_id you may have for testing purposes
        }

        // Create a new metadata record with current timestamps
        $metadata = Metadata::create([
            'created_at' => Carbon::now(), // Set current datetime for created_at
            'updated_at' => Carbon::now(), // Set current datetime for updated_at
        ]);

        // Create the new category, assigning the metadata_id and user_id
        Category::create([
            'name' => $request->name,
            'metadata_id' => $metadata->id,  // Use the created metadata's ID
            'user_id' => $userId, // Use the test user's ID as the creator
        ]);

        // Redirect to the categories index page
        return redirect()->route('categories.index');
    }


    // Display the specified resource.
    public function show(Category $category)
    {
        return view('categories.show', compact('category'));
    }

    // Show the form for editing the specified resource.
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string']);
        $category->update($request->all());
        return redirect()->route('categories.index');
    }

    // Remove the specified resource from storage.
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index');
    }
}
