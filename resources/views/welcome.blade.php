@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">
            <!-- Sidebar Filter -->
            <div class="w-1/4 bg-white shadow-sm rounded-lg p-6">
                <h2 class="text-lg font-semibold mb-4">Filters</h2>
                <form action="{{ route('welcome') }}" method="GET">
                    <!-- Search -->
                    <div class="mb-4">
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="w-full rounded-md border-gray-300" placeholder="Search businesses...">
                    </div>

                    <!-- Categories (Multiple Selection) -->
                    <div class="mb-4">
                        <h3 class="text-md font-medium">Categories</h3>
                        @foreach($categories as $category)
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                    {{ in_array($category->id, request('categories', [])) ? 'checked' : '' }}>
                                <span>{{ $category->name }}</span>
                            </label>
                        @endforeach
                    </div>

                    <!-- Category Match Type (AND / OR) -->
                    <div class="mb-4">
                        <h3 class="text-md font-medium">Match Type</h3>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="match_type" value="or"
                                {{ request('match_type', 'or') == 'or' ? 'checked' : '' }}>
                            <span>Match Any (OR)</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="match_type" value="and"
                                {{ request('match_type') == 'and' ? 'checked' : '' }}>
                            <span>Match All (AND)</span>
                        </label>
                    </div>

                    <!-- Rating -->
                    <div class="mb-4">
                        <h3 class="text-md font-medium">Minimum Rating</h3>
                        <select name="rating" class="w-full rounded-md border-gray-300">
                            <option value="">Any Rating</option>
                            @foreach(range(5, 1) as $rating)
                                <option value="{{ $rating }}" {{ request('rating') == $rating ? 'selected' : '' }}>
                                    {{ $rating }}+ Stars
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div class="mb-4">
                        <h3 class="text-md font-medium">Sort By</h3>
                        <select name="sort" class="w-full rounded-md border-gray-300">
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                            <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating</option>
                            <option value="reviews_count" {{ request('sort') == 'reviews_count' ? 'selected' : '' }}>Most Reviewed</option>
                        </select>
                    </div>

                    <!-- Sort Direction -->
                    <div class="mb-4">
                        <h3 class="text-md font-medium">Sort Direction</h3>
                        <select name="direction" class="w-full rounded-md border-gray-300">
                            <option value="asc" {{ request('direction', 'asc') == 'asc' ? 'selected' : '' }}>Ascending</option>
                            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md">
                        Apply Filters
                    </button>
                </form>
            </div>

            <!-- Business Listings -->
            <div class="w-3/4 pl-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($businesses as $business)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <!-- Business Name and Favorite Button -->
                                <div class="flex justify-between items-center mb-4">
                                    <!-- Business Name -->
                                    <h2 class="text-xl font-semibold">
                                        <a href="{{ route('businesses.show', $business) }}"
                                           class="text-blue-600 hover:text-blue-800">
                                            {{ $business->name }}
                                        </a>
                                    </h2>

                                    <!-- Favorite Button (Only shown if logged in) -->
                                    @auth
                                        <form action="{{ route('favorites.toggle', $business) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                @if(auth()->user()->favoriteBusinesses->contains($business->id))
                                                    <!-- Filled Heart (Already Favorited) -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                                                    </svg>
                                                @else
                                                    <!-- Outline Heart (Not Favorited) -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                                    </svg>
                                                @endif
                                            </button>
                                        </form>
                                    @endauth
                                </div>


                                <!-- Business Image -->
                                @if($business->photo)
                                    <img src="{{ asset('storage/' . $business->photo) }}"
                                         alt="{{ $business->name }}"
                                         class="w-full h-48 object-cover rounded-lg mb-4">
                                @else
                                    <div class="w-full h-48 bg-gray-200 rounded-lg mb-4 flex items-center justify-center">
                                        <span class="text-gray-500">No photo available</span>
                                    </div>
                                @endif

                                <!-- Rating and Reviews -->
                                <div class="flex items-center mb-4">
                                    <div class="text-yellow-400">
                                        {{ number_format($business->average_rating, 1) }} ★
                                    </div>
                                    <div class="text-gray-600 text-sm ml-2">
                                        ({{ $business->reviews->count() }} reviews)
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="text-sm text-gray-600 mb-4">
                                    {{ Str::limit($business->description, 100) }}
                                </div>

                                <!-- Categories -->
                                <div class="flex flex-wrap gap-2">
                                    @foreach($business->categories as $category)
                                        <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">
                                {{ $category->name }}
                            </span>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $businesses->links() }}
                </div>
            </div>

        </div>
    </div>
@endsection
