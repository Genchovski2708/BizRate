@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-6">My Businesses</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($businesses as $business)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">
                                <a href="{{ route('businesses.show', $business) }}"
                                   class="text-blue-600 hover:text-blue-800">
                                    {{ $business->name }}
                                </a>
                            </h2>

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
@endsection
