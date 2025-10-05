<div>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Manage Feedback</h5>
        </div>

        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-4">
                        <input type="text" class="form-control" placeholder="Search by name, email, or message..."
                            wire:model.live="search">
                    </div>

                    <!-- Rating Filter -->
                    <div class="col-md-3">
                        <select class="form-select" wire:model.live="filterRating">
                            <option value="">All Ratings</option>
                            <option value="Excellent">Excellent</option>
                            <option value="Very Good">Very Good</option>
                            <option value="Good">Good</option>
                            <option value="Bad">Bad</option>
                            <option value="Very Bad">Very Bad</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <div class="col-md-1">
                        <button class="btn btn-outline-secondary w-100" wire:click="resetFilters" title="Reset Filters">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Rating</th>
                                    <th>Message</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            @forelse ($feedback as $feedbacks)
                                <tbody>
                                    <td>{{ $feedbacks->name }}</td>
                                    <td>{{ $feedbacks->email }}</td>
                                    <td>{{ $feedbacks->rating }}</td>
                                    <td>{{ $feedbacks->message }}</td>
                                    <td>{{ $feedbacks->created_at }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop"
                                                wire:click="editFeedback({{ $feedbacks->id }})">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                wire:click="deleteFeedback({{ $feedbacks->id }})"
                                                wire:confirm="Are you sure do you want to delete this feedback?">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tbody>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4">
                                        <i class="fa-solid fa-comments fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No Feedbacks Found</h4>
                                        <p class="text-muted">There are no feedback to manage at the moment.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $feedback->links() }}
                    </div>

                    <!-- Modal -->
                    <div wire:ignore.self class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header bg-secondary text-white">
                                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                        <i class="fas fa-edit me-2"></i>Edit Feedback
                                    </h1>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="editName" class="form-label fw-semibold">
                                                <i class="fas fa-user me-1"></i>Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="editName" name="name"
                                                required placeholder="Enter your name" wire:model="name">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="editEmail" class="form-label fw-semibold">
                                                <i class="fas fa-envelope me-1"></i>Email <span
                                                    class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="editEmail" name="email"
                                                required placeholder="Enter your email" wire:model="email">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-star me-1"></i>Rating <span class="text-danger">*</span>
                                        </label>
                                        <div class="rating-stars">
                                            <div class="btn-group" role="group" aria-label="Rating">
                                                <label class="btn btn-outline-success" for="rating-excellent">
                                                    <i class="fas fa-star me-1"></i>
                                                </label>
                                                <select class="form-control" wire:model="rating">
                                                    <option value="Excellent">Excellent</option>
                                                    <option value="Very Good">Very Good</option>
                                                    <option value="Good">Good</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="editMessage" class="form-label fw-semibold">
                                            <i class="fas fa-comment me-1"></i>Message <span
                                                class="text-danger">*</span>
                                        </label>
                                        <textarea class="form-control" id="editMessage" name="message" rows="5" required
                                            placeholder="Share your experience..." wire:model="message"></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>Close
                                    </button>
                                    <button type="submit" form="editFeedbackForm" class="btn btn-primary"
                                        wire:click="updateFeedback" data-bs-dismiss="modal">
                                        <i class="fas fa-save me-1"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
