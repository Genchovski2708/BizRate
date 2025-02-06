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


    public function welcome()
    {
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

    public function create()
    {
        $categories = Category::all();
        return view('businesses.create', compact('categories'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ], [
            'name.required' => 'Business name is required.',
            'description.required' => 'Description is required.',
            'address.required' => 'Address is required.',
            'contact.required' => 'Contact information is required.',
            'categories.required' => 'Please select at least one category.',
            'categories.*.exists' => 'Invalid category selection.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.mimes' => 'Allowed image formats: jpeg, png, jpg, gif.',
            'photo.max' => 'Image size must not exceed 4MB.',
        ]);

        $user = Auth::user();

        $photoPath = $request->hasFile('photo') ?
            $request->file('photo')->store('businesses', 'public') : null;

        $business = Business::create([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'contact' => $request->contact,
            'photo' => $photoPath,
            'user_id' => $user->id,
        ]);

        $business->categories()->attach($request->categories);

        return redirect('/')->with('success', 'Business created successfully.');
    }



    public function show(Business $business)
    {
        $business->load('reviews.user');
        $business->average_rating = $business->reviews->avg('rating');
        return view('businesses.show', compact('business'));
    }

    public function edit(Business $business)
    {
        $this->authorize('update', $business);
        $categories = Category::all();
        return view('businesses.edit', compact('business', 'categories'));
    }

    public function update(Request $request, Business $business)
    {
        $this->authorize('update', $business);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'address' => 'required|string|max:255',
            'contact' => 'required|string|max:255',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ], [
            'name.required' => 'Business name is required.',
            'description.required' => 'Description is required.',
            'address.required' => 'Address is required.',
            'contact.required' => 'Contact information is required.',
            'categories.required' => 'Please select at least one category.',
            'categories.*.exists' => 'Invalid category selection.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.mimes' => 'Allowed image formats: jpeg, png, jpg, gif.',
            'photo.max' => 'Image size must not exceed 4MB.',
        ]);


        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('businesses', 'public');

            if ($business->photo) {
                Storage::disk('public')->delete($business->photo);
            }

            $business->photo = $photoPath;
        }


        $business->update([
            'name' => $request->name,
            'description' => $request->description,
            'address' => $request->address,
            'contact' => $request->contact,
            'photo' => $business->photo,
        ]);

        $business->categories()->sync($request->categories);

        return redirect()->route('businesses.show', $business)->with('success', 'Business updated successfully!');
    }


    public function destroy(Business $business)
    {

        $business->delete();

        return redirect()->route('businesses.index');
    }
    public function userBusinesses()
    {
        $businesses = auth()->user()->businesses()->paginate(10);
        return view('businesses.user-index', compact('businesses'));
    }


}
