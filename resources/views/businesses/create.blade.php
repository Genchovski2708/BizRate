@extends('layouts.app')

@section('content')
    <h1>Create Business</h1>
    <form action="{{ route('businesses.store') }}" method="POST">
        @csrf
        <label for="name">Business Name</label>
        <input type="text" name="name" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description"></textarea>

        <label for="address">Address</label>
        <input type="text" name="address" id="address" required>

        <label for="contact">Contact</label>
        <input type="text" name="contact" id="contact">

        <label for="category_id">Category</label>
        <select name="category_id" id="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <button type="submit">Create</button>
    </form>
@endsection
