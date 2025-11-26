<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Manage Payment</h5>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" class="form-control" placeholder="Search by car id, gcash reference number..."
                        wire:model.live="search">
                </div>

                <select class="form-select" wire:model.live="filterStatus">
                    <option value="">All</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                </select>

                <!-- Reset Button -->
                {{-- <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-outline-secondary w-100" wire:click="resetFilters" title="Reset Filters">
                        <i class="fas fa-refresh"></i>
                    </button>
                </div> --}}
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
                            <th>Gcash Reference Number</th>
                            <th>Gcash Receipt</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($payment as $payments)
                            <tr>
                                <td><span class="badge bg-warning fs-6">{{ $payments->car_id }}</span></td>
                                <td><span class="badge bg-secondary fs-6">{{ $payments->gcash_reference_number }}</span>
                                </td>
                                <td>
                                    <a href="{{ asset('storage/' . $payments->gcash_receipt) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $payments->gcash_receipt) }}" alt="Valid Id"
                                            width="80" style="object-fit: cover; border-radius: 5px;">
                                    </a>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payments->status == 'pending' ? 'warning' : 'success' }}">
                                        {{ ucfirst($payments->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if ($payments->status == 'pending')
                                        <button class="btn btn-success btn-sm"
                                            wire:click="updateStatus({{ $payments->id }}, 'confirmed')"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-check"></i> Confirm
                                        </button>
                                    @elseif($payments->status == 'confirmed')
                                        <button class="btn btn-warning btn-sm"
                                            wire:click="updateStatus({{ $payments->id }}, 'pending')"
                                            wire:loading.attr="disabled">
                                            <i class="fas fa-clock"></i> Mark Pending
                                        </button>
                                    @endif
                                    <div class="btn-group-vertical btn-group-sm">
                                        <!-- Delete Button -->
                                        <button class="btn btn-danger mt-1"
                                            wire:click="deletePayment({{ $payments->id }})"
                                            wire:confirm="Are you sure do you want to delete this?"
                                            title="Delete Payment">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-4">
                                    <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                                    <h4 class="text-muted">No Payments Found</h4>
                                    <p class="text-muted">There are no payments to manage at the moment.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">

            </div>
        </div>
    </div>
</div>
