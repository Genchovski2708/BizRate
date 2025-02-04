@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-6">My Reviews</h2>

                    @if($reviews->isEmpty())
                        <p class="text-gray-600">You haven't posted any reviews yet.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($reviews as $review)
                                <div class="flex gap-6 border-b pb-4">
                                    <!-- Review Section -->
                                    <div class="w-2/3">
                                        <!-- Displayed review content -->
                                        <p id="content-{{ $review->id }}" class="text-gray-700">
                                            <strong>Rating:</strong> {{ $review->rating }} Stars
                                            <br>
                                            {{ $review->comment }}
                                        </p>

                                        <div class="mt-4 flex gap-3">
                                            <button type="button"
                                                    class="text-blue-500 hover:text-blue-600 transition duration-200"
                                                    onclick="editReview({{ $review->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                                  onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600 transition duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Edit Form (Initially Hidden) -->
                                        <form onsubmit="updateReview(event, {{ $review->id }})"
                                              class="edit-form hidden mt-4 space-y-4"
                                              id="edit-form-{{ $review->id }}">
                                            @csrf
                                            @method('PUT')

                                            <div>
                                                <label class="block text-gray-700 mb-2">Edit Rating</label>
                                                <select name="rating" class="rounded-md w-full" required>
                                                    @foreach(range(5, 1) as $rating)
                                                        <option value="{{ $rating }}" {{ $review->rating == $rating ? 'selected' : '' }}>
                                                            {{ $rating }} Stars
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-gray-700 mb-2">Edit Comment</label>
                                                <textarea name="comment" rows="3"
                                                          class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                                                          required>{{ $review->comment }}</textarea>
                                            </div>

                                            <div class="flex gap-3">
                                                <button type="submit"
                                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                                                    <i class="fas fa-check mr-2"></i>Update
                                                </button>
                                                <button type="button"
                                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200"
                                                        onclick="cancelEdit({{ $review->id }})">
                                                    <i class="fas fa-times mr-2"></i>Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Business Card Section -->
                                    <div class="w-1/3">
                                        <x-business-card :business="$review->business" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $reviews->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function editReview(reviewId) {
            document.getElementById(`edit-form-${reviewId}`).classList.remove('hidden');
        }

        function cancelEdit(reviewId) {
            document.getElementById(`edit-form-${reviewId}`).classList.add('hidden');
        }

        async function updateReview(event, reviewId) {
            event.preventDefault();

            const form = event.target;
            const rating = form.querySelector('select[name="rating"]').value;
            const comment = form.querySelector('textarea[name="comment"]').value;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(`/reviews/${reviewId}/json`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ rating: rating, comment: comment })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.success) {
                    // Update the displayed content
                    const contentDiv = document.getElementById('content-' + reviewId);
                    contentDiv.innerHTML = `<strong>Rating:</strong> ${rating} Stars<br>${comment}`;

                    // Hide the edit form
                    cancelEdit(reviewId);

                    // Optional: Show a success message
                    showNotification('Review updated successfully', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error updating review', 'error');
            }
        }

        function showNotification(message, type = 'success') {
            const notificationDiv = document.createElement('div');
            notificationDiv.className = `fixed top-4 right-4 p-4 rounded-md ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white`;
            notificationDiv.textContent = message;

            document.body.appendChild(notificationDiv);

            setTimeout(() => {
                notificationDiv.remove();
            }, 3000);
        }
    </script>
@endsection
