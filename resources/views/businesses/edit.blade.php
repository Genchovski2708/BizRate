@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-2xl mx-auto bg-white shadow-md rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-6">Edit Business</h1>

            <form action="{{ route('businesses.update', $business->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') <!-- Use PUT for updating -->

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Business Name</label>
                    <input type="text" name="name" value="{{ old('name', $business->name) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea name="description" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-md">{{ old('description', $business->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $business->address) }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Contact</label>
                    <input type="text" name="contact" value="{{ old('contact', $business->contact) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Categories</label>
                    <select name="categories[]" multiple required class="w-full px-3 py-2 border border-gray-300 rounded-md">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ in_array($category->id, old('categories', $business->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium mb-2">Photo</label>
                    <input type="file" name="photo" id="photoInput" class="w-full px-3 py-2 border border-gray-300 rounded-md">

                    <!-- Image preview -->
                    <img id="photoPreview"
                         src="{{ $business->photo ? asset('storage/' . $business->photo) : '' }}"
                         class="w-full h-48 object-cover mt-4 {{ $business->photo ? '' : 'hidden' }}">

                </div>

                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                    Update Business
                </button>
            </form>
        </div>
    </div>
    <script>
        document.getElementById('photoInput').addEventListener('change', function(event) {
            const preview = document.getElementById('photoPreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
