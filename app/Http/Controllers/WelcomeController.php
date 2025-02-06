<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Business::with(['categories', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');

        // Filter by multiple categories
        if ($request->filled('categories')) {
            $categoryIds = $request->input('categories', []);
            $matchType = $request->input('match_type', 'or');

            if ($matchType === 'and') {
                foreach ($categoryIds as $categoryId) {
                    $query->whereHas('categories', function ($q) use ($categoryId) {
                        $q->where('categories.id', $categoryId);
                    });
                }
            } else {
                $query->whereHas('categories', function ($q) use ($categoryIds) {
                    $q->whereIn('categories.id', $categoryIds);
                });
            }
        }


        if ($request->filled('rating')) {
            $query->where('average_rating', '>=', $request->rating);
        }


        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }


        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc'); // Default: Ascending

        if ($sort === 'rating') {
            $query->orderBy('average_rating', $direction);
        } elseif ($sort === 'reviews_count') {
            $query->orderBy('reviews_count', $direction);
        } else {
            $query->orderBy($sort, $direction);
        }

        $businesses = $query->paginate(12);

        $categories = Category::whereHas('businesses')->get();

        return view('welcome', compact('businesses', 'categories'));
    }







    public function show(Business $business, Request $request)
    {

        $business->load(['reviews.user', 'categories', 'comments.user', 'comments.replies.user']);

        $sortOrder = $request->query('sort', 'desc');
        $activeTab = $request->query('tab', 'reviews');

        $reviews = $business->reviews()->orderBy('created_at', $sortOrder)->get();
        $comments = $business->comments()->orderBy('created_at', $sortOrder)->get();

        return view('businesses.show', compact('business', 'reviews', 'comments', 'sortOrder', 'activeTab'));
    }


}
