<div class="container-fluid py-4">
    <!-- Search Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Find Your Perfect Car</h2>
                <span class="badge bg-primary fs-6">
                    {{ $cars->total() }} cars available
                </span>
            </div>

            <!-- Search Filters Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">
                        <i class="fas fa-filter me-2"></i>Search Filters
                    </h5>

                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Brand</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" wire:model.live="searchBrand" class="form-control border-start-0"
                                    placeholder="Search brand...">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Max Price</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">₱</span>
                                <input type="number" wire:model.live="searchPrice" class="form-control"
                                    placeholder="Max price per day">
                            </div>
                        </div>

                        {{-- <div class="col-md-3">
                            <label class="form-label fw-semibold">Start Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-calendar text-muted"></i>
                                </span>
                                <input type="date" wire:model.live="searchStartDate" class="form-control border-start-0" min="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">End Date</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-calendar text-muted"></i>
                                </span>
                                <input type="date" wire:model.live="searchEndDate" class="form-control border-start-0" min="{{ date('Y-m-d') }}">
                            </div>
                        </div> --}}
                    </div>

                    <div class="mt-4 d-flex justify-content-end">
                        <button class="btn btn-outline-secondary me-2" wire:click="resetFilters">
                            <i class="fas fa-undo me-1"></i> Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Car Grid -->
    <div class="row g-4">
        @forelse ($cars as $car)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 shadow-sm border-0 overflow-hidden">
                    <!-- Car Image with Status Badge -->
                    <div class="position-relative">
                        <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('storage/images/placeholder-car.jpg') }}"
                            class="card-img-top" alt="{{ $car->brand }} {{ $car->model }}"
                            style="height: 200px; object-fit: cover;">

                        @php
                            // Check if search dates are provided
                            $isAvailable = true;
                            $statusText = 'Available';
                            $statusClass = 'bg-success';

                            if (!empty($searchStartDate) && !empty($searchEndDate)) {
                                $isAvailable = $car->isAvailable($searchStartDate, $searchEndDate);
                                $statusText = $isAvailable ? 'Available' : 'Unavailable';
                                $statusClass = $isAvailable ? 'bg-success' : 'bg-danger';
                            }
                        @endphp

                        <span class="position-absolute top-0 end-0 m-2 badge {{ $statusClass }}">
                            {{ $statusText }}
                        </span>
                    </div>

                    <div class="card-body d-flex flex-column">
                        <!-- Car Details -->
                        <h5 class="card-title">{{ $car->brand }} {{ $car->model }} {{ $car->year }}</h5>

                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-1">
                                <i class="fas fa-tag text-primary me-2"></i>
                                <span class="fw-semibold">₱{{ number_format($car->price_per_day, 2) }} / day</span>
                            </div>

                            <!-- Car Features -->
                            <div class="d-flex text-muted small mt-2 flex-wrap">
                                <span class="me-3 mb-1">
                                    <i class="fas fa-gas-pump me-1"></i> {{ $car->fuel }}
                                </span>
                                <span class="me-3 mb-1">
                                    <i class="fas fa-car me-1"></i> {{ $car->transmission }}
                                </span>
                                <span class="mb-1">
                                    <i class="fas fa-user me-1"></i> {{ $car->setting_capacity }}
                                </span>
                            </div>
                        </div>

                        <!-- Book Button -->
                        <div class="mt-auto">
                            @if ($isAvailable)
                                <button class="btn btn-primary w-100 d-flex align-items-center justify-content-center"
                                    wire:click="$dispatch('openBookingModal', { carId: {{ $car->id }} })">
                                    <i class="fas fa-calendar-check me-2"></i> Book Now
                                </button>
                            @else
                                <button class="btn btn-secondary w-100 d-flex align-items-center justify-content-center"
                                    disabled>
                                    <i class="fas fa-calendar-times me-2"></i> Unavailable
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No cars found</h4>
                        <p class="text-muted mb-4">We couldn't find any cars matching your criteria.</p>
                        <button class="btn btn-primary" wire:click="resetFilters">
                            <i class="fas fa-undo me-1"></i> Reset Filters
                        </button>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($cars instanceof \Illuminate\Pagination\LengthAwarePaginator && $cars->total() > 0)
        <div class="d-flex justify-content-center mt-5">
            <nav aria-label="Car pagination">
                {{ $cars->links() }}
            </nav>
        </div>
    @endif

    <!-- Booking Modal Component -->
    @livewire('booking')
</div>
