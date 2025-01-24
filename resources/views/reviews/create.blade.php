@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">Create Review</h1>
            <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="rating" class="block text-gray-700 font-medium mb-2">Rating</label>
                    <input type="number" name="rating" id="rating" min="1" max="5" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="comment" class="block text-gray-700 font-medium mb-2">Comment</label>
                    <textarea name="comment" id="comment" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>

                <div>
                    <label for="user_id" class="block text-gray-700 font-medium mb-2">User</label>
                    <select name="user_id" id="user_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="business_id" class="block text-gray-700 font-medium mb-2">Business</label>
                    <select name="business_id" id="business_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach($businesses as $business)
                            <option value="{{ $business->id }}">{{ $business->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Submit Review
                </button>
            </form>
        </div>
    </div>
@endsection
