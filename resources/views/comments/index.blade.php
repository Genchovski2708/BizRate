@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6 text-gray-800">All Comments</h1>

            <div class="space-y-4">
                @foreach ($comments as $comment)
                    <div class="border-b pb-4 last:border-b-0">
                        <p class="text-gray-700"><strong>{{ $comment->user->name }}</strong> on
                            <strong>{{ $comment->business->name }}</strong></p>
                        <p class="text-gray-600">{{ $comment->content }}</p>

                        <!-- Comment Actions (Only for Creator or Admin) -->
                        @can('update', $comment)
                            <div class="flex space-x-2 mt-2">
                                <a href="{{ route('comments.edit', $comment) }}"
                                   class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        @endcan
                        <a href="{{ route('comments.show', $comment) }}" class="text-green-500 hover:text-green-700">Details</a>

                        <!-- Replies Section -->
                        @if ($comment->replies->count())
                            <details class="mt-2">
                                <summary class="cursor-pointer text-blue-500">View Replies ({{ $comment->replies->count() }})</summary>
                                <div class="ml-6 mt-2 border-l pl-4 space-y-2">
                                    @foreach ($comment->replies as $reply)
                                        <div class="border-b pb-2 last:border-b-0">
                                            <p class="text-gray-600"><strong>{{ $reply->user->name }}:</strong> {{ $reply->content }}</p>

                                            <!-- Reply Actions (Only for Creator or Admin) -->
                                            @can('update', $reply)
                                                <div class="flex space-x-2 mt-1">
                                                    <a href="{{ route('comments.edit', $reply) }}"
                                                       class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                                    <form action="{{ route('comments.destroy', $reply) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700"
                                                                onclick="return confirm('Are you sure?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @endcan
                                            <a href="{{ route('comments.show', $reply) }}" class="text-green-500 hover:text-green-700">Details</a>
                                        </div>
                                    @endforeach
                                </div>
                            </details>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
@endsection
