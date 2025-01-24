@extends('layouts.app')

@section('content')
    <h1>Edit Business</h1>
    <form action="{{ route('businesses.update', $business) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="name">Business Name</label>
        <input type="text" name="name" value="{{ $business->name }}" id="name" required>

        <label for="description">Description</label>
        <textarea name="description" id="description">{{ $business->description }}</textarea>

        <label for="address">Address</label>
        <input type="text" name="address" value="{{ $business->address }}" id="address" required>

        <label for="contact">Contact</label>
        <input type="text" name="contact" value="{{ $business->contact }}" id="contact">

        <label for="category_id">Category</label>
        <select name="category_id" id="category_id">
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @if($business->category_id == $category->id) selected @endif>{{ $category->name }}</option>
            @endforeach
        </select>

        <button type="submit">Update</button>
    </form>
@endsection
