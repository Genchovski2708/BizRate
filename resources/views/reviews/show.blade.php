@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">
                Review by {{ $review->user->name }} on {{ $review->business->name }}
            </h1>

            <div class="space-y-4">
                <div class="flex items-center">
                    <span class="font-medium mr-2">Rating:</span>
                    <div class="flex">
                        @for ($i = 1; $i <= 5; $i++)
                            <svg class="w-6 h-6 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.462a1 1 0 00.95-.69l1.07-3.292z" />
                            </svg>
                        @endfor
                    </div>
                </div>

                <div class="bg-gray-100 p-4 rounded-md">
                    <p class="text-gray-700">{{ $review->comment }}</p>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <a href="{{ route('reviews.edit', $review) }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Edit Review
                </a>
                <form action="{{ route('reviews.destroy', $review) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300"
                            onclick="return confirm('Are you sure?')">
                        Delete Review
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
