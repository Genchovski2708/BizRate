@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">{{ $user->name }}</h1>
            <div class="space-y-4">
                <div>
                    <strong class="text-gray-700">Email:</strong>
                    <p class="text-gray-600">{{ $user->email }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">Role:</strong>
                    <p class="text-gray-600">{{ ucfirst($user->role) }}</p>
                </div>
                <div>
                    <strong class="text-gray-700">Created at:</strong>
                    <p class="text-gray-600">{{ $user->created_at->format('F j, Y') }}</p>
                </div>
            </div>
            <div class="mt-6 flex space-x-4">
                <a href="{{ route('users.edit', $user) }}"
                   class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300">
                    Edit
                </a>
                <form action="{{ route('users.destroy', $user) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition duration-300"
                            onclick="return confirm('Are you sure?')">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
