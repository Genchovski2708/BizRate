@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Category: {{ $category->name }}</h1>

            <div class="mt-6 flex space-x-4">
                <a href="{{ route('categories.edit', $category) }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Edit Category
                </a>
                <form action="{{ route('categories.destroy', $category) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300"
                            onclick="return confirm('Are you sure?')">
                        Delete Category
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
