@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-2xl font-semibold mb-6">My Comments</h2>

                    @if($comments->isEmpty())
                        <p class="text-gray-600">You haven't posted any comments yet.</p>
                    @else
                        <div class="space-y-6">
                            @foreach($comments as $comment)
                                <div class="flex gap-6 border-b pb-4">
                                    <!-- Comment Section -->
                                    <div class="w-2/3">
                                        <!-- Displayed comment content -->
                                        <p id="content-{{ $comment->id }}" class="text-gray-700">{{ $comment->content }}</p>

                                        <div class="mt-4 flex gap-3">
                                            <button type="button"
                                                    class="text-blue-500 hover:text-blue-600 transition duration-200"
                                                    onclick="editComment({{ $comment->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600 transition duration-200">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>

                                        <!-- Edit Form (Initially Hidden) -->
                                        <form onsubmit="updateComment(event, {{ $comment->id }})"
                                              class="edit-form hidden mt-4 space-y-4"
                                              id="edit-form-{{ $comment->id }}">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <label for="content" class="block text-gray-700 mb-2">Edit your Comment</label>
                                                <textarea name="content" rows="3"
                                                          class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                                                          required>{{ $comment->content }}</textarea>
                                            </div>
                                            <div class="flex gap-3">
                                                <button type="submit"
                                                        class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                                                    <i class="fas fa-check mr-2"></i>Update
                                                </button>
                                                <button type="button"
                                                        class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200"
                                                        onclick="cancelEdit({{ $comment->id }})">
                                                    <i class="fas fa-times mr-2"></i>Cancel
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Business Card Section -->
                                    <div class="w-1/3">
                                        <x-business-card :business="$comment->business" />
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $comments->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function editComment(commentId) {
            document.getElementById(`edit-form-${commentId}`).classList.remove('hidden');
        }

        function cancelEdit(commentId) {
            document.getElementById(`edit-form-${commentId}`).classList.add('hidden');
        }

        async function updateComment(event, commentId) {
            event.preventDefault();

            const form = event.target;
            const content = form.querySelector('textarea[name="content"]').value;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(`/comments/${commentId}/json`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.success) {
                    // Update the displayed content
                    document.getElementById('content-' + commentId).textContent = content;

                    // Hide the edit form
                    cancelEdit(commentId);

                    // Optional: Show a success message
                    showNotification('Comment updated successfully', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error updating comment', 'error');
            }
        }

        function showNotification(message, type = 'success') {
            // You can implement this based on your preferred notification system
            // For example, using a toast notification library or a simple DIV
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
