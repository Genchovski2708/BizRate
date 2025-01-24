@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Users</h1>
                <a href="{{ route('users.create') }}"
                   class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 transition duration-300">
                    Create User
                </a>
            </div>
            <table class="w-full border-collapse">
                <thead>
                <tr class="bg-gray-100">
                    <th class="border p-3 text-left">Name</th>
                    <th class="border p-3 text-left">Email</th>
                    <th class="border p-3 text-left">Role</th>
                    <th class="border p-3 text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-3">{{ $user->name }}</td>
                        <td class="border p-3">{{ $user->email }}</td>
                        <td class="border p-3">{{ ucfirst($user->role) }}</td>
                        <td class="border p-3 text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('users.show', $user) }}"
                                   class="text-blue-500 hover:text-blue-700">View</a>
                                <a href="{{ route('users.edit', $user) }}"
                                   class="text-yellow-500 hover:text-yellow-700">Edit</a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-500 hover:text-red-700"
                                            onclick="return confirm('Are you sure?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
