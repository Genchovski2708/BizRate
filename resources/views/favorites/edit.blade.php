@extends('layouts.app')

@section('content')
    <h1>Edit Favorite</h1>
    <form action="{{ route('favorites.update', $favorite->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $user->id == $favorite->user_id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="business_id">Business</label>
            <select name="business_id" id="business_id" class="form-control" required>
                @foreach($businesses as $business)
                    <option value="{{ $business->id }}" {{ $business->id == $favorite->business_id ? 'selected' : '' }}>
                        {{ $business->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Favorite</button>
    </form>
@endsection
