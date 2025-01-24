@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Review</h1>
            <form action="{{ route('reviews.update', $review) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="rating" class="block text-gray-700 font-medium mb-2">Rating</label>
                    <input type="number" name="rating" id="rating" min="1" max="5"
                           value="{{ $review->rating }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="comment" class="block text-gray-700 font-medium mb-2">Comment</label>
                    <textarea name="comment" id="comment" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $review->comment }}</textarea>
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Update Review
                </button>
            </form>
        </div>
    </div>
@endsection
