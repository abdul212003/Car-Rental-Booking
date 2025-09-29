<div>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Manage Booking</h5>
        </div>

        @if (session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Days</th>
                                <th>Total Cost</th>
                                <th>Gcash Reference Number</th>
                                <th>Gcash Receipt</th>
                                <th>Valid Id Photo</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        @forelse ($bookings as $booking)
                            <tbody>

                                <td>{{ $booking->guest_name }}</td>
                                <td>{{ $booking->guest_email }}</td>
                                <td>{{ $booking->guest_phone_number }}</td>
                                <td>{{ $booking->start_date->format('Y-m-d') }}</td>
                                <td>{{ $booking->end_date->format('Y-m-d') }}</td>
                                <td>{{ $booking->total_days }}</td>
                                <td>{{ $booking->total_cost }}</td>
                                <td>{{ $booking->gcash_reference_number }}</td>
                                <td>
                                    <a href="{{ asset('storage/' . $booking->requirements_valid_id_photo) }}"
                                        target="_blank">
                                        <img src="{{ asset('storage/' . $booking->requirements_valid_id_photo) }}"
                                            alt="Valid Id" width="80"
                                            style="object-fit: cover; border-radius: 5px;">
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $booking->gcash_receipt) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $booking->gcash_receipt) }}"
                                            alt="Gcash Receipt" width="80"
                                            style="object-fit: cover; border-radius: 5px;">
                                    </a>
                                </td>
                                <td>
                                    {{-- <span
                                        class="badge bg-{{ $booking->status == 'pending'
                                            ? 'warning'
                                            : ($booking->status == 'confirmed'
                                                ? 'success'
                                                : ($booking->status == 'cancelled'
                                                    ? 'danger'
                                                    : 'secondary')) }} fs-8">
                                        {{ ucfirst($booking->status) }}
                                    </span> --}}
                                    <span
                                        class="badge bg-{{ $booking->status == 'pending'
                                            ? 'warning'
                                            : ($booking->status == 'confirmed'
                                                ? 'success'
                                                : ($booking->status == 'rented_out'
                                                    ? 'info'
                                                    : ($booking->status == 'returned'
                                                        ? 'secondary'
                                                        : 'danger'))) }} fs-8">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    @if ($booking->rented_out_at)
                                        <br><small class="text-muted">Rented:
                                            {{ $booking->rented_out_at->format('M d, Y') }}</small>
                                    @endif
                                    @if ($booking->returned_at)
                                        <br><small class="text-muted">Returned:
                                            {{ $booking->returned_at->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        {{-- <button class="btn btn-sm btn-success"
                                            wire:click="updateStatus({{ $booking->id }}, 'confirmed')">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="updateStatus({{ $booking->id }}, 'cancelled')"
                                            wire:confirm="Are you sure you want to cancelled the book?">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </button> --}}

                                        <!-- Accept Button -->
                                        {{-- <button
                                            class="btn btn-sm btn-success {{ $booking->status == 'confirmed' ? 'disabled' : '' }}"
                                            wire:click="updateStatus({{ $booking->id }}, 'confirmed')"
                                            {{ $booking->status == 'confirmed' ? 'disabled' : '' }}
                                            title="{{ $booking->status == 'confirmed' ? 'Already Accepted' : 'Accept Booking' }}"
                                            wire:confirm="Are you sure you want to accept this booking?">
                                            <i class="fas fa-check"></i>
                                        </button>

                                        <!-- Decline Button -->
                                        <button
                                            class="btn btn-sm btn-danger {{ $booking->status == 'cancelled' ? 'disabled' : '' }}"
                                            wire:click="updateStatus({{ $booking->id }}, 'cancelled')"
                                            {{ $booking->status == 'cancelled' ? 'disabled' : '' }}
                                            wire:confirm="Are you sure you want to decline this booking?"
                                            title="{{ $booking->status == 'cancelled' ? 'Already Declined' : 'Decline Booking' }}">
                                            <i class="fas fa-times"></i>
                                        </button> --}}

                                        <div class="btn-group-vertical btn-group-sm">
                                            <!-- Accept/Confirm Button -->
                                            @if ($booking->status == 'pending')
                                                <button class="btn btn-success"
                                                    wire:click="updateStatus({{ $booking->id }}, 'confirmed')"
                                                    wire:confirm="Are you sure you want to accept this booking?"
                                                    title="Accept Booking">
                                                    <i class="fas fa-check"></i> Accept
                                                </button>
                                            @endif

                                            <!-- Mark as Rented Out Button -->
                                            @if ($booking->status == 'confirmed')
                                                <button class="btn btn-info text-white"
                                                    wire:click="updateStatus({{ $booking->id }}, 'rented_out')"
                                                    wire:confirm="Are you sure you want to mark this as rented out?"
                                                    title="Mark as Rented Out">
                                                    <i class="fas fa-key"></i> Rented Out
                                                </button>
                                            @endif

                                            <!-- Mark as Returned Button -->
                                            @if ($booking->status == 'rented_out')
                                                <button class="btn btn-secondary"
                                                    wire:click="updateStatus({{ $booking->id }}, 'returned')"
                                                    wire:confirm="Are you sure you want to mark this as returned?"
                                                    title="Mark as Returned">
                                                    <i class="fas fa-undo"></i> Returned
                                                </button>
                                            @endif

                                            <!-- Decline/Cancel Button -->
                                            @if (in_array($booking->status, ['pending', 'confirmed']))
                                                <button class="btn btn-danger mt-1"
                                                    wire:click="updateStatus({{ $booking->id }}, 'cancelled')"
                                                    wire:confirm="Are you sure you want to cancel this booking?"
                                                    title="Cancel Booking">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            @endif
                                        </div>

                                        <!-- Status Message -->
                                        @if ($booking->status == 'confirmed')
                                            <small class="text-success d-block mt-1">
                                                <i class="fas fa-check-circle"></i> Accepted
                                            </small>
                                        @elseif($booking->status == 'rented_out')
                                            <small class="text-info d-block mt-1">
                                                <i class="fas fa-key"></i> Vehicle Rented Out
                                            </small>
                                        @elseif($booking->status == 'returned')
                                            <small class="text-secondary d-block mt-1">
                                                <i class="fas fa-undo"></i> Vehicle Returned
                                            </small>
                                        @elseif($booking->status == 'cancelled')
                                            <small class="text-danger d-block mt-1">
                                                <i class="fas fa-times-circle"></i> Cancelled
                                            </small>
                                        @endif
                                    </div>

                                    <!-- Status Message -->
                                    @if ($booking->status == 'confirmed')
                                        <small class="text-success d-block mt-1">
                                            <i class="fas fa-check-circle"></i> Accepted
                                        </small>
                                    @elseif($booking->status == 'cancelled')
                                        <small class="text-danger d-block mt-1">
                                            <i class="fas fa-times-circle"></i> Declined
                                        </small>
                                    @endif
                                </td>

                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No Bookings Found</h4>
                                        <p class="text-muted">There are no bookings to manage at the moment.</p>
                                    </td>
                                </tr>
                        @endforelse
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
