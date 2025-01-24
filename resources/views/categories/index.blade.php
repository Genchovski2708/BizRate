@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Categories</h1>
                <a href="{{ route('categories.create') }}"
                   class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-300">
                    Create Category
                </a>
            </div>

            <div class="space-y-4">
                @foreach ($categories as $category)
                    <div class="border-b pb-4 last:border-b-0">
                        <div class="flex justify-between items-center mb-2">
                            <h2 class="text-lg font-semibold text-gray-700">{{ $category->name }}</h2>
                            <div class="flex space-x-2">
                                <a href="{{ route('categories.edit', $category) }}"
                                   class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                <form action="{{ route('categories.destroy', $category) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
