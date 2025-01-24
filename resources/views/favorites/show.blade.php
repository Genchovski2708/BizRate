@extends('layouts.app')

@section('content')
    <h1>Favorite Details</h1>
    <table class="table">
        <tr>
            <th>ID</th>
            <td>{{ $favorite->id }}</td>
        </tr>
        <tr>
            <th>User</th>
            <td>{{ $favorite->user->name }}</td>
        </tr>
        <tr>
            <th>Business</th>
            <td>{{ $favorite->business->name }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $favorite->created_at }}</td>
        </tr>
    </table>

    <a href="{{ route('favorites.edit', $favorite->id) }}" class="btn btn-warning">Edit</a>
    <form action="{{ route('favorites.destroy', $favorite->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
    </form>
    <a href="{{ route('favorites.index') }}" class="btn btn-secondary">Back to Favorites</a>
@endsection
