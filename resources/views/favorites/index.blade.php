@extends('layouts.app')

@section('content')
    <h1>Favorites</h1>
    <a href="{{ route('favorites.create') }}" class="btn btn-primary">Add Favorite</a>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Business</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($favorites as $favorite)
            <tr>
                <td>{{ $favorite->id }}</td>
                <td>{{ $favorite->user->name }}</td>
                <td>{{ $favorite->business->name }}</td>
                <td>{{ $favorite->created_at }}</td>
                <td>
                    <a href="{{ route('favorites.show', $favorite->id) }}" class="btn btn-info">View</a>
                    <a href="{{ route('favorites.edit', $favorite->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
