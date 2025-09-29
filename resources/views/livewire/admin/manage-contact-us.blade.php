<div>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Manage Contact</h5>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Message</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        @forelse ($contact as $contacts)
                            <tbody>
                                <td>{{ $contacts->contact_name }}</td>
                                <td>{{ $contacts->contact_email }}</td>
                                <td>{{ $contacts->contact_subject }}</td>
                                <td>{{ $contacts->contact_message }}</td>
                                <td>{{ $contacts->created_at }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success" data-bs-toggle="modal"
                                            data-bs-target="#staticBackdrop"
                                            wire:click="editContact({{ $contacts->id }})">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="deleteContact({{ $contacts->id }})"
                                            wire:confirm="Are you sure do you want to delete this feedback?">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tbody>
                        @empty
                            <tr>
                                <td colspan="13" class="text-center py-4">
                                    {{-- <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i> --}}
                                    <i class="fa-solid fa-address-book fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No Contact Found</h4>
                                    <p class="text-muted">There are no contact to manage at the moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $contact->links() }}
                </div>

                <!-- Modal -->
                <div wire:ignore.self class="modal fade" id="staticBackdrop" data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header bg-secondary text-white">
                                <h1 class="modal-title fs-5" id="staticBackdropLabel">
                                    <i class="fas fa-edit me-2"></i>Edit Contact
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
                                            required placeholder="Enter your name" wire:model="contact_name">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="editEmail" class="form-label fw-semibold">
                                            <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control" id="editEmail" name="email"
                                            required placeholder="Enter your email" wire:model="contact_email">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="col-md-6 mb-3">
                                        <label for="editEmail" class="form-label fw-semibold">
                                            <i class="fa-solid fa-bookmark"></i>Subject <span
                                                class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control" id="editEmail" name="email"
                                            required placeholder="Enter your subject" wire:model="contact_subject">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="editMessage" class="form-label fw-semibold">
                                        <i class="fas fa-comment me-1"></i>Message <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control" id="editMessage" name="message" rows="5" required
                                        placeholder="Share your experience..." wire:model="contact_message"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Close
                                </button>
                                <button type="submit" form="editFeedbackForm" class="btn btn-primary"
                                    data-bs-dismiss="modal" wire:click="updateContact">
                                    <i class="fas fa-save me-1"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
