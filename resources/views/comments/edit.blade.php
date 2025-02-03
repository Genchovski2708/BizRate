@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800">Edit Comment</h1>
            <form action="{{ route('comments.update', $comment) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="content" class="block text-gray-700 font-medium mb-2">Comment</label>
                    <textarea name="content" id="content" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500">{{ $comment->content }}</textarea>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Update Comment
                </button>
            </form>
        </div>
    </div>
@endsection
