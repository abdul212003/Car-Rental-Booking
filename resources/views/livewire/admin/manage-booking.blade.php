<div>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Manage Booking</h5>
        </div>

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
                                            alt="Valid Id" width="80" style="object-fit: cover; border-radius: 5px;">
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
                                    <span
                                        class="badge bg-{{ $booking->status == 'pending' ? 'warning' : ($booking->status == 'confirmed' ? 'success' : 'danger') }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success"
                                            wire:click="updateStatus({{ $booking->id }}, 'confirmed')">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger"
                                            wire:click="updateStatus({{ $booking->id }}, 'cancelled')"
                                            wire:confirm="Are you sure you want to cancelled the book?">
                                            <i class="fa-solid fa-circle-xmark"></i>
                                        </button>
                                    </div>
                                </td>

                            @empty
                                <h3>No Booking Found.</h3>
                        @endforelse
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
