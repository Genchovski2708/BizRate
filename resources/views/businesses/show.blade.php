@extends('layouts.app')

@section('content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($business->photo)
                        <img src="{{ asset('storage/' . $business->photo) }}"
                             alt="{{ $business->name }}"
                             class="w-full h-64 object-cover rounded-lg mb-6">
                    @endif

                    <h1 class="text-3xl font-semibold mb-4">{{ $business->name }}</h1>
                    @if(auth()->check() && (auth()->user()->id == $business->user_id || auth()->user()->isAdmin()))
                        <div class="mb-4">
                            <a href="{{ route('businesses.edit', $business->id) }}"
                               class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600">
                                Edit Business
                            </a>
                        </div>
                    @endif

                    <div class="flex items-center mb-4">
                        <div class="text-2xl text-yellow-400">
                            {{ number_format($business->average_rating, 1) }} ★
                        </div>
                        <div class="text-gray-600 ml-2">
                            ({{ $business->reviews->count() }} reviews)
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h2 class="text-xl font-semibold mb-2">About</h2>
                            <p class="text-gray-600">{{ $business->description }}</p>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold mb-2">Contact Information</h2>
                            <p class="text-gray-600">{{ $business->address }}</p>
                            <p class="text-gray-600">{{ $business->contact }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-2">Categories</h2>
                        <div class="flex flex-wrap gap-2">
                            @foreach($business->categories as $category)
                                <span class="px-3 py-1 bg-gray-100 rounded-full">
                                    {{ $category->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <!-- Tabs Navigation -->
                    <div class="border-b border-gray-200 mb-6">
                        <div class="flex space-x-8">
                            <button
                                onclick="switchTab('reviews')"
                                id="reviewsTab"
                                class="py-4 px-1 border-b-2 border-blue-500 text-blue-600 font-medium">
                                Reviews
                            </button>
                            <button
                                onclick="switchTab('comments')"
                                id="commentsTab"
                                class="py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium">
                                Comments
                            </button>
                        </div>
                    </div>

                    <!-- Reviews Content -->
                    <div id="reviewsContent">
                        @auth
                            @php
                                $existingReview = $business->reviews->where('user_id', auth()->id())->first();
                            @endphp

                            @if(auth()->id() !== $business->user_id)
                                <form action="{{ route('reviews.store') }}" method="POST" class="mb-6 bg-gray-50 p-6 rounded-lg">
                                    @csrf
                                    <input type="hidden" name="business_id" value="{{ $business->id }}">

                                    <div class="mb-4">
                                        <label class="block text-gray-700 mb-2">Rating</label>
                                        <select name="rating" class="rounded-md w-full" required>
                                            @foreach(range(5, 1) as $rating)
                                                <option value="{{ $rating }}" {{ ($existingReview && $existingReview->rating == $rating) ? 'selected' : '' }}>
                                                    {{ $rating }} Stars
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-gray-700 mb-2">Comment</label>
                                        <textarea name="comment" rows="4" class="rounded-md w-full" required>{{ $existingReview->comment ?? '' }}</textarea>
                                    </div>

                                    <div class="flex gap-4">
                                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                            {{ $existingReview ? 'Update Review' : 'Submit Review' }}
                                        </button>

                                        @if($existingReview)
                                            <form action="{{ route('reviews.destroy', $existingReview->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </form>
                            @else
                                <p class="text-gray-600 mb-6">You cannot review your own business.</p>
                            @endif
                        @else
                            <div class="mb-6 p-4 bg-gray-100 rounded-md">
                                <p>Please <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">login</a> to leave a review.</p>
                            </div>
                        @endauth

                        <div class="space-y-6">
                            @foreach($business->reviews->sortByDesc('created_at') as $review)
                                <div class="border-b pb-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center">
                                            <div class="text-yellow-400 mr-2">
                                                {{ $review->rating }} ★
                                            </div>
                                            <div class="font-semibold">
                                                {{ $review->user->name }}
                                            </div>
                                        </div>
                                        <div class="text-gray-500 text-sm">
                                            {{ $review->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    <p class="text-gray-600">{{ $review->comment }}</p>

                                    @auth
                                        @if(auth()->id() == $review->user_id || auth()->user()->isAdmin())
                                            <div class="mt-4 flex gap-4">
                                                <a href="{{ route('reviews.edit', $review->id) }}"
                                                   class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                                    Edit
                                                </a>

                                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @endauth
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Comments Content -->
                    <div id="commentsContent" class="hidden">
                        @auth
                            <form onsubmit="submitComment(event, {{ $business->id }})" class="mb-6 bg-gray-50 p-6 rounded-lg">
                                @csrf
                                <div class="mb-4">
                                    <label class="block text-gray-700 mb-2">Your Comment</label>
                                    <textarea name="content" rows="4" class="rounded-md w-full" required></textarea>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                                    Post Comment
                                </button>
                            </form>

                        @else
                            <div class="mb-6 p-4 bg-gray-100 rounded-md">
                                <p>Please <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">login</a> to leave a comment.</p>
                            </div>
                        @endauth

                            <div class="space-y-6">
                                @foreach($comments as $comment)
                                    @if(!$comment->parent_id) <!-- Only display top-level comments -->
                                    <div class="border-b pb-6" id="comment-{{ $comment->id }}">
                                        <!-- Comment Header -->
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <i class="fas fa-user text-gray-600"></i>
                                                </div>
                                                <div class="font-semibold text-gray-800">{{ $comment->user->name }}</div>
                                            </div>
                                            <div class="text-gray-500 text-sm">
                                                {{ $comment->created_at->diffForHumans() }}
                                            </div>
                                        </div>

                                        <!-- Comment Content -->
                                        <div class="text-gray-700 comment-content" id="content-{{ $comment->id }}">
                                            {{ $comment->content }}
                                        </div>

                                        <!-- Actions (Edit/Delete) -->
                                        @auth
                                            @if(auth()->id() == $comment->user_id || auth()->user()->isAdmin())
                                                <div class="mt-4 flex gap-3">
                                                    <button type="button"
                                                            class="text-blue-500 hover:text-blue-600 transition duration-200"
                                                            onclick="editComment({{ $comment->id }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    <button type="button"
                                                            class="text-red-500 hover:text-red-600 transition duration-200"
                                                            onclick="deleteComment({{ $comment->id }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Edit Form (Hidden Initially) -->
                                                <form onsubmit="updateComment(event, {{ $comment->id }})"
                                                      class="edit-form hidden mt-4 space-y-4"
                                                      id="edit-form-{{ $comment->id }}">
                                                    @csrf
                                                    <div>
                                                        <label for="content" class="block text-gray-700 mb-2">Edit your Comment</label>
                                                        <textarea name="content" rows="4"
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
                                            @endif
                                        @endauth

                                        <!-- Reply Button & Form -->
                                        @auth
                                            <button type="button" class="text-green-500 hover:text-green-600 mt-4" onclick="toggleReplyForm({{ $comment->id }})">
                                                <i class="fas fa-reply"></i> Reply
                                            </button>

                                            <!-- Reply Form (Hidden Initially) -->
                                            <form id="reply-form-{{ $comment->id }}" class="hidden mt-4 space-y-4" onsubmit="submitReply(event, {{ $business->id }}, {{ $comment->id }})">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <div>
                                                    <label for="content" class="block text-gray-700 mb-2">Your Reply</label>
                                                    <textarea name="content" rows="4"
                                                              class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                                                              required></textarea>
                                                </div>
                                                <div class="flex gap-3">
                                                    <button type="submit"
                                                            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 transition duration-200">
                                                        <i class="fas fa-paper-plane mr-2"></i>Submit Reply
                                                    </button>
                                                    <button type="button" onclick="cancelReply({{ $comment->id }})"
                                                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200">
                                                        <i class="fas fa-times mr-2"></i>Cancel
                                                    </button>
                                                </div>
                                            </form>
                                        @endauth

                                        <!-- Reply Visibility Toggle Button -->
                                        @if($comment->replies->count() > 0)
                                            <button type="button" class="text-blue-500 mt-4" onclick="toggleReplies({{ $comment->id }})">
                                                <i class="fas fa-arrow-down"></i> Hide Replies
                                            </button>
                                        @endif

                                        <!-- Nested Replies (If any) -->
                                        <div id="replies-{{ $comment->id }}" class="mt-4 hidden pl-6 border-l-2 border-gray-300">
                                            @foreach($comment->replies as $reply)
                                                <div class="border-b pb-4" id="comment-{{ $reply->id }}">
                                                    <!-- Reply Header -->
                                                    <div class="flex items-center justify-between mb-4">
                                                        <div class="flex items-center space-x-3">
                                                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                                <i class="fas fa-user text-gray-600"></i>
                                                            </div>
                                                            <div class="font-semibold text-gray-800">{{ $reply->user->name }}</div>
                                                        </div>
                                                        <div class="text-gray-500 text-sm">
                                                            {{ $reply->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>

                                                    <!-- Reply Content -->
                                                    <div class="text-gray-700 comment-content" id="content-{{ $reply->id }}">
                                                        {{ $reply->content }}
                                                    </div>

                                                    <!-- Actions (Edit/Delete) -->
                                                    @auth
                                                        @if(auth()->id() == $reply->user_id || auth()->user()->isAdmin())
                                                            <div class="mt-4 flex gap-3">
                                                                <button type="button"
                                                                        class="text-blue-500 hover:text-blue-600 transition duration-200"
                                                                        onclick="editComment({{ $reply->id }})">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>

                                                                <button type="button"
                                                                        class="text-red-500 hover:text-red-600 transition duration-200"
                                                                        onclick="deleteComment({{ $reply->id }})">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>

                                                            <!-- Edit Form (Hidden Initially) -->
                                                            <form onsubmit="updateComment(event, {{ $reply->id }})"
                                                                  class="edit-form hidden mt-4 space-y-4"
                                                                  id="edit-form-{{ $reply->id }}">
                                                                @csrf
                                                                <div>
                                                                    <label for="content" class="block text-gray-700 mb-2">Edit your Reply</label>
                                                                    <textarea name="content" rows="4"
                                                                              class="w-full rounded-md border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition duration-200"
                                                                              required>{{ $reply->content }}</textarea>
                                                                </div>
                                                                <div class="flex gap-3">
                                                                    <button type="submit"
                                                                            class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-200">
                                                                        <i class="fas fa-check mr-2"></i>Update
                                                                    </button>
                                                                    <button type="button"
                                                                            class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition duration-200"
                                                                            onclick="cancelEdit({{ $reply->id }})">
                                                                        <i class="fas fa-times mr-2"></i>Cancel
                                                                    </button>
                                                                </div>
                                                            </form>
                                                        @endif
                                                    @endauth
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tab) {
            const reviewsContent = document.getElementById('reviewsContent');
            const commentsContent = document.getElementById('commentsContent');
            const reviewsTab = document.getElementById('reviewsTab');
            const commentsTab = document.getElementById('commentsTab');

            if (tab === 'reviews') {
                reviewsContent.classList.remove('hidden');
                commentsContent.classList.add('hidden');
                reviewsTab.classList.add('border-blue-500', 'text-blue-600');
                reviewsTab.classList.remove('border-transparent', 'text-gray-500');
                commentsTab.classList.remove('border-blue-500', 'text-blue-600');
                commentsTab.classList.add('border-transparent', 'text-gray-500');
            } else {
                reviewsContent.classList.add('hidden');
                commentsContent.classList.remove('hidden');
                commentsTab.classList.add('border-blue-500', 'text-blue-600');
                commentsTab.classList.remove('border-transparent', 'text-gray-500');
                reviewsTab.classList.remove('border-blue-500', 'text-blue-600');
                reviewsTab.classList.add('border-transparent', 'text-gray-500');
            }
        }
    </script>

    <script>
        // Show the edit form
        function editComment(commentId) {
            const contentDiv = document.getElementById('content-' + commentId);
            const editForm = document.getElementById('edit-form-' + commentId);

            contentDiv.classList.add('hidden');
            editForm.classList.remove('hidden');
        }

        // Cancel the edit and hide the form
        function cancelEdit(commentId) {
            const contentDiv = document.getElementById('content-' + commentId);
            const editForm = document.getElementById('edit-form-' + commentId);

            contentDiv.classList.remove('hidden');
            editForm.classList.add('hidden');
        }

        // Update comment using fetch
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
                    // Update the content
                    const contentDiv = document.getElementById('content-' + commentId);
                    contentDiv.textContent = content;

                    // Hide the form and show the updated content
                    cancelEdit(commentId);

                    // Optional: Show a success message
                    showNotification('Comment updated successfully', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error updating comment', 'error');
            }
        }

        // Optional: Add a notification function
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
        async function deleteComment(commentId) {
            if (!confirm('Are you sure you want to delete this comment?')) {
                return;
            }

            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(`/comments/${commentId}/json`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.success) {
                    // Remove the comment from the DOM
                    const commentDiv = document.getElementById('comment-' + commentId);
                    if (commentDiv) {
                        commentDiv.remove();
                    }

                    // Optional: Show a success message
                    showNotification('Comment deleted successfully', 'success');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error deleting comment', 'error');
            }
        }
    </script>
    <script>
        // Toggle visibility of the reply form
        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById('reply-form-' + commentId);
            replyForm.classList.toggle('hidden');
        }

        // Cancel the reply form
        function cancelReply(commentId) {
            const replyForm = document.getElementById('reply-form-' + commentId);
            replyForm.classList.add('hidden');
        }

        // Toggle replies visibility (Hide/Show)
        function toggleReplies(commentId) {
            const repliesContainer = document.getElementById('replies-' + commentId);
            const button = repliesContainer.previousElementSibling;
            repliesContainer.classList.toggle('hidden');

            // Toggle the button text
            if (repliesContainer.classList.contains('hidden')) {
                button.innerHTML = '<i class="fas fa-arrow-down"></i> Show replies';
            } else {
                button.innerHTML = '<i class="fas fa-arrow-up"></i> Hide replies';
            }
        }

        // Handle main comment form submission
        async function submitComment(event, businessId) {
            event.preventDefault();

            const form = event.target;
            const content = form.querySelector('textarea[name="content"]').value;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(`/businesses/${businessId}/comments/json`, {
                    method: 'POST',
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
                    // Clear the form
                    form.reset();

                    // Optional: Show a success message
                    showNotification('Comment posted successfully', 'success');

                    // Optional: Dynamically insert the new comment into the DOM
                    // You can create a function to append the new comment to the list
                    appendComment(data.comment);
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error posting comment', 'error');
            }
        }
        // Handle reply form submission
        async function submitReply(event, businessId, parentId) {
            event.preventDefault();

            const form = event.target;
            const content = form.querySelector('textarea[name="content"]').value;
            const token = document.querySelector('meta[name="csrf-token"]').content;

            try {
                const response = await fetch(`/businesses/${businessId}/comments/json`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ content: content, parent_id: parentId })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();

                if (data.success) {
                    // Clear the form
                    form.reset();

                    // Optional: Show a success message
                    showNotification('Reply posted successfully', 'success');

                    // Optional: Dynamically insert the new reply into the DOM
                    // You can create a function to append the new reply to the list
                    appendReply(data.comment, parentId);
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error posting reply', 'error');
            }
        }
        // Append a new comment to the list
        function appendComment(comment) {
            const commentList = document.querySelector('.space-y-6');

            const commentDiv = document.createElement('div');
            commentDiv.className = 'border-b pb-6';
            commentDiv.id = `comment-${comment.id}`;

            commentDiv.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-600"></i>
                </div>
                <div class="font-semibold text-gray-800">${comment.user.name}</div>
            </div>
            <div class="text-gray-500 text-sm">
                ${new Date(comment.created_at).toLocaleString()}
            </div>
        </div>
        <div class="text-gray-700 comment-content" id="content-${comment.id}">
            ${comment.content}
        </div>
    `;

            commentList.appendChild(commentDiv);
        }


        // Append a new reply to the list
        function appendReply(reply, parentId) {
            const repliesDiv = document.getElementById(`replies-${parentId}`);

            const replyDiv = document.createElement('div');
            replyDiv.className = 'border-b pb-4';
            replyDiv.id = `comment-${reply.id}`;

            replyDiv.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-gray-600"></i>
                </div>
                <div class="font-semibold text-gray-800">${reply.user.name}</div>
            </div>
            <div class="text-gray-500 text-sm">
                ${new Date(reply.created_at).toLocaleString()}
            </div>
        </div>
        <div class="text-gray-700 comment-content" id="content-${reply.id}">
            ${reply.content}
        </div>
    `;

            repliesDiv.appendChild(replyDiv);
        }
    </script>

@endsection
