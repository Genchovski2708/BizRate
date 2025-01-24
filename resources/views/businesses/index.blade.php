@extends('layouts.app')

@section('content')
    <h1>Businesses</h1>
    <a href="{{ route('businesses.create') }}">Create Business</a>
    <ul>
        @foreach ($businesses as $business)
            <li>
                <a href="{{ route('businesses.show', $business) }}">{{ $business->name }}</a>
                <a href="{{ route('businesses.edit', $business) }}">Edit</a>
                <form action="{{ route('businesses.destroy', $business) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>
@endsection
