@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Reviews</h1>
                <a href="{{ route('reviews.create') }}"
                   class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-300">
                    Create Review
                </a>
            </div>

            <div class="space-y-4">
                @foreach ($reviews as $review)
                    <div class="border-b pb-4 last:border-b-0">
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-lg font-semibold text-gray-700">
                                {{ $review->user->name }} reviewed {{ $review->business->name }}
                            </h2>
                            <div class="flex space-x-2">
                                <a href="{{ route('reviews.edit', $review) }}"
                                   class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                        <p class="text-gray-600 mb-2">{{ $review->comment }}</p>
                        <div class="flex items-center">
                            <span class="font-medium mr-2">Rating:</span>
                            <div class="flex">
                                @for ($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
