@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Admin Dashboard Layout -->
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-6">Admin Dashboard</h2>

                <!-- Admin Actions -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Create Category Button -->
                    <div class="bg-blue-500 hover:bg-blue-700 text-white text-center rounded-lg shadow-lg p-6 transition-all">
                        <a href="{{ route('categories.index') }}" class="block text-xl font-semibold">
                            Manage Categories
                        </a>
                        <p class="mt-2 text-sm">Add new categories for businesses.</p>
                    </div>

                    <!-- Examine Reviews Button -->
                    <div class="bg-green-500 hover:bg-green-700 text-white text-center rounded-lg shadow-lg p-6 transition-all">
                        <a href="{{ route('reviews.index') }}" class="block text-xl font-semibold">
                            Examine Reviews
                        </a>
                        <p class="mt-2 text-sm">Manage and moderate reviews from users.</p>
                    </div>
                    <!-- Examine Reviews Button -->
                    <div class="bg-yellow-500 hover:bg-yellow-700 text-white text-center rounded-lg shadow-lg p-6 transition-all">
                        <a href="{{ route('users.index') }}" class="block text-xl font-semibold">
                            Manage Users
                        </a>
                        <p class="mt-2 text-sm">Manage and moderate users.</p>
                    </div>

                    <!-- More options can be added later -->
                    <!-- Example: Manage Users, View Reports, etc. -->
                </div>
            </div>
        </div>
    </div>
@endsection
