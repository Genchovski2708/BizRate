@extends('layouts.app')

@section('content')
    <h1>{{ $business->name }}</h1>
    <p>{{ $business->description }}</p>
    <p>Address: {{ $business->address }}</p>
    <p>Contact: {{ $business->contact }}</p>
    <p>Category: {{ $business->category->name }}</p>
    <a href="{{ route('businesses.edit', $business) }}">Edit</a>
    <form action="{{ route('businesses.destroy', $business) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit">Delete</button>
    </form>
@endsection
