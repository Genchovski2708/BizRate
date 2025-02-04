@props(['business'])

<a href="{{ route('businesses.show', $business) }}" class="block">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg relative group">
        <!-- Image Container with Overlay -->
        <div class="relative">
            @if($business->photo)
                <img src="{{ asset('storage/' . $business->photo) }}"
                     alt="{{ $business->name }}"
                     class="w-full h-48 object-cover rounded-t-lg">
            @else
                <div class="w-full h-48 bg-gray-200 rounded-t-lg flex items-center justify-center">
                    <span class="text-gray-500">No photo available</span>
                </div>
            @endif

            <!-- Overlay for Name and Favorite Button -->
            <div class="absolute top-0 left-0 right-0 p-4">
                <div class="flex justify-between items-start">
                    <h2 class="text-xl font-semibold text-white drop-shadow-lg pr-10">
                        {{ $business->name }}
                    </h2>

                    @auth
                        <form action="{{ route('favorites.toggle', $business) }}" method="POST" class="z-10 flex-shrink-0">
                            @csrf
                            <button
                                type="submit"
                                class="transition-all duration-200 ease-in-out rounded-full p-1.5
                                    @if(auth()->user()->favoriteBusinesses->contains($business->id))
                                        bg-red-100 text-red-600 hover:bg-red-200
                                    @else
                                        bg-white/30 backdrop-blur-sm text-white hover:bg-white/50 border border-white/30
                                    @endif"
                                onclick="event.preventDefault(); event.stopPropagation(); this.closest('form').submit();"
                            >
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
            </div>

            <!-- Gradient Overlay for Better Readability -->
            <div class="absolute inset-x-0 top-0 h-16 bg-gradient-to-b from-black/50 to-transparent rounded-t-lg"></div>
        </div>

        <!-- Card Content -->
        <div class="p-4">
            <!-- Rating and Reviews -->
            <div class="flex items-center mb-2">
                <div class="text-yellow-400">
                    {{ number_format($business->average_rating, 1) }} ★
                </div>
                <div class="text-gray-600 text-sm ml-2">
                    ({{ $business->reviews_count ?? 0 }} reviews)
                </div>
            </div>

            <!-- Description -->
            <div class="text-sm text-gray-600 mb-2">
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
</a>
