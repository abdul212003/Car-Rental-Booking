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
                                <th>Booking Id</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Operator</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Total Days</th>
                                <th>Total Cost</th>
                                <th>Valid Id Photo</th>
                                <th>Payment Plan</th>
                                <th>Downpayment Amount</th>
                                <th>Remaining Balance</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                                <tr>
                                    <td>{{ $booking->car_id }}</td>
                                    <td>{{ $booking->car->brand ?? 'N/A' }}</td>
                                    <td>{{ $booking->car->plate_number ?? 'N/A' }}</td>
                                    <td>{{ $booking->id }}</td>
                                    <td>{{ $booking->guest_name }}</td>
                                    <td>{{ $booking->guest_email }}</td>
                                    <td>{{ $booking->guest_phone_number }}</td>
                                    <td><span class="badge bg-info rounded">{{ $booking->operator }}</span></td>
                                    <td>{{ $booking->start_date->format('Y-m-d') }}</td>
                                    <td>{{ $booking->end_date->format('Y-m-d') }}</td>
                                    <td>{{ $booking->total_days }}</td>
                                    <td>₱{{ number_format($booking->total_cost, 2) }}</td>

                                    <td>
                                        <a href="{{ asset('storage/' . $booking->requirements_valid_id_photo) }}"
                                            target="_blank">
                                            <img src="{{ asset('storage/' . $booking->requirements_valid_id_photo) }}"
                                                alt="Valid Id" width="80"
                                                style="object-fit: cover; border-radius: 5px;">
                                        </a>
                                    </td>
                                    <td><span class="badge bg-secondary">{{ $booking->payment_plan }}</span></td>
                                    <td>{{ $booking->downpayment_amount ?? 0 }}</td>
                                    <td>{{ $booking->remaining_balance }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $booking->status == 'pending'
                                                ? 'warning'
                                                : ($booking->status == 'in_progress'
                                                    ? 'primary'
                                                    : ($booking->status == 'confirmed'
                                                        ? 'success'
                                                        : ($booking->status == 'rented_out'
                                                            ? 'info'
                                                            : ($booking->status == 'returned'
                                                                ? 'secondary'
                                                                : 'danger')))) }} fs-8">
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
                                            <button wire:click="editBooking({{ $booking->id }})"
                                                class="btn btn-warning mb-1">
                                                <i class="fas fa-edit"></i>Edit
                                            </button>
                                            <!-- Print Button -->
                                            <button class="btn btn-primary mb-1"
                                                onclick="printBooking({{ $booking->id }})" title="Print Booking">
                                                <i class="fas fa-print"></i> Print
                                            </button>
                                            <!-- Pending → In Progress -->
                                            @if ($booking->status == 'pending')
                                                <button class="btn btn-warning text-white"
                                                    wire:click="updateStatus({{ $booking->id }}, 'in_progress')"
                                                    wire:confirm="Mark this booking as IN PROGRESS?"
                                                    title="Mark as In Progress">
                                                    <i class="fas fa-spinner"></i> In Progress
                                                </button>
                                            @endif

                                            <!-- In Progress → Confirm -->
                                            @if ($booking->status == 'in_progress')
                                                <button class="btn btn-success"
                                                    wire:click="updateStatus({{ $booking->id }}, 'confirmed')"
                                                    wire:confirm="Confirm this booking?" title="Confirm Booking">
                                                    <i class="fas fa-check-circle"></i> Confirm
                                                </button>
                                            @endif

                                            <!-- Confirmed → Rented Out -->
                                            @if ($booking->status == 'confirmed')
                                                <button class="btn btn-info text-white"
                                                    wire:click="updateStatus({{ $booking->id }}, 'rented_out')"
                                                    wire:confirm="Mark this as rented out?"
                                                    title="Mark as Rented Out">
                                                    <i class="fas fa-key"></i> Rented Out
                                                </button>
                                            @endif

                                            <!-- Rented Out → Returned -->
                                            @if ($booking->status == 'rented_out')
                                                <button class="btn btn-secondary"
                                                    wire:click="updateStatus({{ $booking->id }}, 'returned')"
                                                    wire:confirm="Mark this as returned?" title="Mark as Returned">
                                                    <i class="fas fa-undo"></i> Returned
                                                </button>
                                            @endif

                                            <!-- Cancel Button (Pending or In Progress) -->
                                            @if (in_array($booking->status, ['pending', 'in_progress']))
                                                <button class="btn btn-danger mt-1"
                                                    wire:click="updateStatus({{ $booking->id }}, 'cancelled')"
                                                    wire:confirm="Cancel this booking?" title="Cancel Booking">
                                                    <i class="fas fa-times"></i> Cancel
                                                </button>
                                            @endif

                                            <!-- Delete Button -->
                                            <button class="btn btn-danger mt-1"
                                                wire:click="deleteBooking({{ $booking->id }})"
                                                wire:confirm="Are you sure you want to delete this?"
                                                title="Delete Booking">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
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

    <!-- Edit Booking Modal -->
    <div wire:ignore.self class="modal fade" id="editBookingModal" tabindex="-1"
        aria-labelledby="editBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editBookingModalLabel">
                        <i class="fas fa-edit"></i> Edit Booking
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row g-3">

                        <!-- Booking Information Section -->
                        <div class="col-12">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-info-circle"></i> Booking Information
                            </h6>
                        </div>

                        <!-- Booking ID -->
                        <div class="col-md-4">
                            <label class="form-label">Booking ID</label>
                            <input type="text" class="form-control" value="#{{ $id }}" readonly>
                        </div>

                        <!-- Car -->
                        <div class="col-md-8">
                            <label class="form-label">Car</label>
                            <input type="text" class="form-control"
                                value="{{ $car_brand }} - {{ $car_plate_number }}" readonly>
                        </div>

                        <!-- Guest Name -->
                        <div class="col-md-6">
                            <label class="form-label">Guest Name</label>
                            <input type="text" class="form-control" value="{{ $guest_name }}" readonly>
                        </div>

                        <!-- Guest Email -->
                        <div class="col-md-6">
                            <label class="form-label">Guest Email</label>
                            <input type="email" class="form-control" value="{{ $guest_email }}" readonly>
                        </div>

                        <!-- Guest Phone -->
                        <div class="col-md-6">
                            <label class="form-label">Guest Phone</label>
                            <input type="text" class="form-control" value="{{ $guest_phone_number }}" readonly>
                        </div>

                        <!-- Operator -->
                        <div class="col-md-6">
                            <label class="form-label">Operator</label>
                            <input type="text" class="form-control" value="{{ $operator }}" readonly>
                        </div>

                        <!-- Start Date -->
                        <div class="col-md-4">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" value="{{ $start_date }}" readonly>
                        </div>

                        <!-- End Date -->
                        <div class="col-md-4">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" value="{{ $end_date }}" readonly>
                        </div>

                        <!-- Total Days -->
                        <div class="col-md-4">
                            <label class="form-label">Total Days</label>
                            <input type="number" class="form-control" value="{{ $total_days }}" readonly>
                        </div>

                        <!-- Payment Information Section -->
                        <div class="col-12 mt-4">
                            <h6 class="border-bottom pb-2 mb-3">
                                <i class="fas fa-money-bill-wave"></i> Payment Information
                            </h6>
                        </div>

                        <!-- Total Cost -->
                        <div class="col-md-12">
                            <label class="form-label">Total Cost</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" wire:model="total_cost" class="form-control" readonly>
                            </div>
                        </div>

                        <!-- Payment Plan -->
                        <div class="col-md-12">
                            <label class="form-label">Payment Plan <span class="text-danger">*</span></label>
                            <select wire:model.live="payment_plan" class="form-select">
                                <option value="">Select Payment Plan</option>
                                <option value="downpayment">Downpayment</option>
                                <option value="full_payment">Full Payment</option>
                            </select>
                            @error('payment_plan')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Downpayment -->
                        <div class="col-md-6">
                            <label class="form-label">
                                Downpayment Amount <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" wire:model.live="downpayment_amount" class="form-control"
                                    min="0" step="0.01"
                                    {{ $payment_plan === 'full_payment' ? 'readonly' : '' }}>
                            </div>
                            @error('downpayment_amount')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Remaining Balance -->
                        <div class="col-md-6">
                            <label class="form-label">Remaining Balance</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" wire:model="remaining_balance" class="form-control">
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        @if ($total_cost > 0)
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-calculator"></i> Payment Summary:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Total Cost: <strong>₱{{ number_format($total_cost, 2) }}</strong></li>
                                        <li>Downpayment:
                                            <strong>₱{{ number_format($downpayment_amount ?? 0, 2) }}</strong>
                                        </li>
                                        <li>Remaining Balance:
                                            <strong>₱{{ number_format($remaining_balance ?? 0, 2) }}</strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button wire:click="updateBooking" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>
<script>
    function printBooking(id) {
        window.open('/booking/print/' + id, '_blank');
    }

    document.addEventListener('livewire:init', () => {
        Livewire.on('openEditModal', () => {
            var modal = new bootstrap.Modal(document.getElementById('editBookingModal'));
            modal.show();
        });

        Livewire.on('closeEditModal', () => {
            var modal = bootstrap.Modal.getInstance(document.getElementById('editBookingModal'));
            modal.hide();
        });
    });
</script>
