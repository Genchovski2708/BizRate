<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with(['categories', 'reviews'])->withAvg('reviews', 'rating');

        // Filter by multiple categories
        if ($request->filled('categories')) {
            $categoryIds = $request->input('categories', []);
            $matchType = $request->input('match_type', 'or'); // Default: OR search

            if ($matchType === 'and') {
                // AND search: Only businesses that have ALL selected categories
                foreach ($categoryIds as $categoryId) {
                    $query->whereHas('categories', function ($q) use ($categoryId) {
                        $q->where('categories.id', $categoryId);
                    });
                }
            } else {
                // OR search: Businesses that have ANY of the selected categories
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('average_rating', '>=', $request->rating);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Sorting logic
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc'); // Default: Ascending

        if ($sort === 'rating') {
            $query->orderBy('average_rating', $direction);
        } elseif ($sort === 'reviews_count') {
            $query->withCount('reviews')->orderBy('reviews_count', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        // Fetch businesses
        $businesses = $query->paginate(12);

        // Fetch only categories that are used in businesses
        $categories = Category::whereHas('businesses')->get();

        return view('welcome', compact('businesses', 'categories'));
    }






    public function show(Business $business)
    {
        $business->load(['reviews.user', 'categories']);
        return view('businesses.show', compact('business'));
    }
}
