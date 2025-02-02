@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-6">My Favorite Businesses</h2>

                    @if($favorites->isEmpty())
                        <p class="text-gray-600">You haven't added any businesses to your favorites yet.</p>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($favorites as $business)
                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border">
                                    @if($business->photo)
                                        <img src="{{ asset('storage/' . $business->photo) }}"
                                             alt="{{ $business->name }}"
                                             class="w-full h-48 object-cover">
                                    @endif
                                    <div class="p-6">
                                        <h3 class="text-xl font-semibold mb-2">
                                            <a href="{{ route('businesses.show', $business) }}"
                                               class="text-blue-600 hover:text-blue-800">
                                                {{ $business->name }}
                                            </a>
                                        </h3>
                                        <div class="flex items-center mb-2">
                                            <div class="text-yellow-400">
                                                {{ number_format($business->average_rating, 1) }} ★
                                            </div>
                                            <div class="text-gray-600 text-sm ml-2">
                                                ({{ $business->reviews_count ?? 0 }} reviews)
                                            </div>
                                        </div>
                                        <p class="text-gray-600 text-sm mb-4">
                                            {{ Str::limit($business->description, 100) }}
                                        </p>
                                        <div class="flex justify-between items-center">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($business->categories as $category)
                                                    <span class="px-2 py-1 bg-gray-100 rounded-full text-xs">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            <form action="{{ route('favorites.destroy', $business) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $favorites->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
