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
                                <x-business-card :business="$business" />
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
