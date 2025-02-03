@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold text-gray-800">Comment by {{ $comment->user->name }}</h1>

            <div class="mt-4">
                <p class="text-gray-700">{{ $comment->content }}</p>
                <p class="text-sm text-gray-500">On: {{ $comment->business->name }}</p>
            </div>

            <div class="mt-6 flex space-x-4">
                <a href="{{ route('comments.edit', $comment) }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                    Edit
                </a>
                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600" onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800">Replies</h2>
                <button class="mt-2 bg-gray-300 text-gray-700 px-3 py-1 rounded-md" onclick="toggleReplies()">Show Replies</button>

                <div id="replies" class="hidden mt-4 space-y-3">
                    @foreach ($comment->replies as $reply)
                        <div class="border-l-4 border-gray-400 pl-4">
                            <p class="text-gray-700"><strong>{{ $reply->user->name }}:</strong> {{ $reply->content }}</p>
                            <div class="flex space-x-2 text-sm text-gray-500">
                                <a href="{{ route('comments.edit', $reply) }}" class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleReplies() {
            document.getElementById('replies').classList.toggle('hidden');
        }
    </script>
@endsection
