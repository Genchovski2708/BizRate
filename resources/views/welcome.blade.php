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
                        <x-business-card :business="$business" />
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
