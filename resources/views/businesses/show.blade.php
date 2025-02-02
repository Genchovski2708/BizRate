@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($business->photo)
                        <img src="{{ asset('storage/' . $business->photo) }}"
                             alt="{{ $business->name }}"
                             class="w-full h-64 object-cover rounded-lg mb-6">
                    @endif

                    <h1 class="text-3xl font-semibold mb-4">{{ $business->name }}</h1>
                    @if(auth()->check() && (auth()->user()->id == $business->user_id || auth()->user()->isAdmin()))
                        <div class="mb-4">
                            <a href="{{ route('businesses.edit', $business->id) }}"
                               class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Edit Business
                            </a>
                        </div>
                    @endif

                    <div class="flex items-center mb-4">
                        <div class="text-2xl text-yellow-400">
                            {{ number_format($business->average_rating, 1) }} ★
                        </div>
                        <div class="text-gray-600 ml-2">
                            ({{ $business->reviews->count() }} reviews)
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-2">About</h2>
                            <p class="text-gray-600">{{ $business->description }}</p>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold mb-2">Contact Information</h2>
                            <p class="text-gray-600">{{ $business->address }}</p>
                            <p class="text-gray-600">{{ $business->contact }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Categories</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($business->categories as $category)
                                <span class="px-3 py-1 bg-gray-100 rounded-full">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Reviews</h2>

                        @auth
                            @php
                                // Get the authenticated user's existing review (if any)
                                $existingReview = $business->reviews->where('user_id', auth()->id())->first();
                            @endphp

                                <!-- Don't allow the creator to review their own business -->
                            @if(auth()->id() !== $business->user_id)
                                <form action="{{ route('reviews.store') }}" method="POST" class="mb-6">
                                    @csrf
                                    <input type="hidden" name="business_id" value="{{ $business->id }}">

                                    <div class="mb-4">
                                        <label class="block text-gray-700 mb-2">Rating</label>
                                        <select name="rating" class="rounded-md w-full" required>
                                            @foreach(range(5, 1) as $rating)
                                                <option value="{{ $rating }}" {{ ($existingReview && $existingReview->rating == $rating) ? 'selected' : '' }}>
                                                    {{ $rating }} Stars
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 mb-2">Comment</label>
                                        <textarea name="comment" rows="4" class="rounded-md w-full" required>{{ $existingReview->comment ?? '' }}</textarea>
                                    </div>

                                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                                        {{ $existingReview ? 'Update Review' : 'Submit Review' }}
                                    </button>
                                </form>

                                <!-- Only show the Delete button if an existing review is present -->
                                @if($existingReview)
                                    <form action="{{ route('reviews.destroy', $existingReview->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            @else
                                <p class="text-gray-600">You cannot review your own business.</p>
                            @endif
                        @else
                            <div class="mb-6 p-4 bg-gray-100 rounded-md">
                                <p>Please <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">login</a> to leave a review.</p>
                            </div>
                        @endauth

                        <div class="space-y-6">
                            @foreach($business->reviews->sortByDesc('created_at') as $review)
                                <!-- Skip displaying the review the user has already submitted -->
                                @if(auth()->id() !== $review->user_id)
                                    <div class="border-b pb-6">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="flex items-center">
                                                <div class="text-yellow-400 mr-2">
                                                    {{ $review->rating }} ★
                                                </div>
                                                <div class="font-semibold">
                                                    {{ $review->user->name }}
                                                </div>
                                            </div>
                                            <div class="text-gray-500 text-sm">
                                                {{ $review->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <p class="text-gray-600">{{ $review->comment }}</p>

                                        <!-- Only show edit/delete buttons if the user is the creator of the review or an admin -->
                                        @auth
                                            @if(auth()->id() == $review->user_id || auth()->user()->isAdmin())
                                                <div class="mt-4 flex gap-4">
                                                    <!-- Edit Review Button -->
                                                    <a href="{{ route('reviews.edit', $review->id) }}"
                                                       class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                        Edit
                                                    </a>

                                                    <!-- Delete Review Button -->
                                                    <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
