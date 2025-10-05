<div>
    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Manage Booking</h5>
        </div>

        @if (session()->has('message') || session()->has('success') || session()->has('error') || session()->has('warning'))
            @if (session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('failed'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('failed') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endif

        <!-- Add this in your card header or somewhere visible -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>FOR TESTING</h5>
            <div>
                <button class="btn btn-info btn-sm" wire:click="testSMSFunctionality"
                    wire:confirm="This will send a test SMS. Continue?">
                    <i class="fas fa-paper-plane"></i> Test SMS
                </button>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-3">
                        <label class="form-label">Search</label>
                        <input type="text" class="form-control"
                            placeholder="Search by name, email, phone, car brand..." wire:model.live="search">
                    </div>

                    <!-- Status Filter -->
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" wire:model.live="filterStatus">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="rented_out">Rented Out</option>
                            <option value="returned">Returned</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Car Brand Filter -->
                    <div class="col-md-2">
                        <label class="form-label">Car Brand</label>
                        <select class="form-select" wire:model.live="filterCarBrand">
                            <option value="">All Brands</option>
                            @foreach ($carBrands as $brand)
                                <option value="{{ $brand }}">{{ $brand }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date Filter -->
                    <div class="col-md-2">
                        <label class="form-label">Start Date From</label>
                        <input type="date" class="form-control" wire:model.live="filterStartDate">
                    </div>

                    <!-- End Date Filter -->
                    <div class="col-md-2">
                        <label class="form-label">End Date To</label>
                        <input type="date" class="form-control" wire:model.live="filterEndDate">
                    </div>

                    <!-- Reset Button -->
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-outline-secondary w-100" wire:click="resetFilters" title="Reset Filters">
                            <i class="fas fa-refresh"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Car Id</th>
                                <th>Car Brand</th>
                                <th>Car Plate Number</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Operator</th>
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
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->car_id }}</td>
                                    <td>{{ $booking->car->brand }}</td>
                                    <td>{{ $booking->car->plate_number }}</td>
                                    <td>{{ $booking->guest_name }}</td>
                                    <td>{{ $booking->guest_email }}</td>
                                    <td>{{ $booking->guest_phone_number }}</td>
                                    <td><span class="badge bg-info rounded">{{ $booking->operator }}</span></td>
                                    <td>{{ $booking->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $booking->end_date->format('Y-m-d') }}</td>
                                    <td>{{ $booking->total_days }}</td>
                                    <td>₱{{ number_format($booking->total_cost, 2) }}</td>
                                    <td>{{ $booking->gcash_reference_number }}</td>
                                    <td>
                                        <a href="{{ asset('storage/' . $booking->gcash_receipt) }}" target="_blank">
                                            <img src="{{ asset('storage/' . $booking->gcash_receipt) }}"
                                                alt="Gcash Receipt" width="80"
                                                style="object-fit: cover; border-radius: 5px;">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ asset('storage/' . $booking->requirements_valid_id_photo) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/' . $booking->requirements_valid_id_photo) }}"
                                                alt="Valid Id" width="80"
                                                style="object-fit: cover; border-radius: 5px;">
                                        </a>
                                    </td>
                                    <td>
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
                                            {{ $booking->status }}
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
                                            @if (in_array($booking->status, ['pending']))
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
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No Bookings Found</h4>
                                        <p class="text-muted">There are no bookings to manage at the moment.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $bookings->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
