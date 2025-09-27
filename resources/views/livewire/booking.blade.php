<div>
    <div>
        @if ($showModal)
            <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
                aria-labelledby="bookingModalLabel" aria-hidden="false" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-dark text-white">
                            <h5 class="modal-title" id="bookingModalLabel">Book {{ $car->brand ?? '' }}
                                {{ $car->model ?? '' }}</h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close"
                                wire:click="$set('showModal', false)">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            @if ($car)
                                <form wire:submit.prevent="bookCar">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- Car Details Picture in 3 Sections -->
                                            <div class="car-details-section mb-4">
                                                <h6 class="mb-3"><i class="fas fa-images me-2"></i>Car Details</h6>

                                                <!-- Car Image Carousel -->
                                                <div id="carImageCarousel" class="carousel slide"
                                                    data-bs-ride="carousel">
                                                    <div class="carousel-indicators">
                                                        <button type="button" data-bs-target="#carImageCarousel"
                                                            data-bs-slide-to="0" class="active" aria-current="true"
                                                            aria-label="Slide 1"></button>
                                                        <button type="button" data-bs-target="#carImageCarousel"
                                                            data-bs-slide-to="1" aria-label="Slide 2"></button>
                                                        <button type="button" data-bs-target="#carImageCarousel"
                                                            data-bs-slide-to="2" aria-label="Slide 3"></button>
                                                    </div>
                                                    <div class="carousel-inner rounded">
                                                        <!-- Main Image -->
                                                        <div class="carousel-item active">
                                                            <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('storage/images/placeholder-car.jpg') }}"
                                                                class="d-block w-100 car-image" alt="Car Main Image"
                                                                style="height: 200px; object-fit: cover;">
                                                            <div
                                                                class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                                                <small>Main View</small>
                                                            </div>
                                                        </div>
                                                        <!-- Interior Image -->
                                                        <div class="carousel-item">
                                                            <img src="{{ $car->interior_image ? asset('storage/' . $car->interior_image) : asset('storage/images/placeholder-interior.jpg') }}"
                                                                class="d-block w-100 car-image" alt="Car Interior"
                                                                style="height: 200px; object-fit: cover;">
                                                            <div
                                                                class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                                                <small>Interior View</small>
                                                            </div>
                                                        </div>
                                                        <!-- Additional Image -->
                                                        <div class="carousel-item">
                                                            <img src="{{ $car->additional_image ? asset('storage/' . $car->additional_image) : asset('storage/images/placeholder-additional.jpg') }}"
                                                                class="d-block w-100 car-image"
                                                                alt="Car Additional View"
                                                                style="height: 200px; object-fit: cover;">
                                                            <div
                                                                class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                                                                <small>Additional View</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button class="carousel-control-prev" type="button"
                                                        data-bs-target="#carImageCarousel" data-bs-slide="prev">
                                                        <span class="carousel-control-prev-icon"
                                                            aria-hidden="true"></span>
                                                        <span class="visually-hidden">Previous</span>
                                                    </button>
                                                    <button class="carousel-control-next" type="button"
                                                        data-bs-target="#carImageCarousel" data-bs-slide="next">
                                                        <span class="carousel-control-next-icon"
                                                            aria-hidden="true"></span>
                                                        <span class="visually-hidden">Next</span>
                                                    </button>
                                                </div>

                                                <!-- Thumbnail Navigation -->
                                                <div class="row mt-2">
                                                    <div class="col-4">
                                                        <a href="javascript:void(0)" class="thumbnail-link"
                                                            data-bs-target="#carImageCarousel" data-bs-slide-to="0">
                                                            <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('storage/images/placeholder-car.jpg') }}"
                                                                class="img-thumbnail w-100 " alt="Main Thumbnail"
                                                                style="height: 60px; object-fit: cover;">
                                                        </a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="javascript:void(0)" class="thumbnail-link"
                                                            data-bs-target="#carImageCarousel" data-bs-slide-to="1">
                                                            <img src="{{ $car->interior_image ? asset('storage/' . $car->interior_image) : asset('storage/images/placeholder-interior.jpg') }}"
                                                                class="img-thumbnail w-100 " alt="Interior Thumbnail"
                                                                style="height: 60px; object-fit: cover;">
                                                        </a>
                                                    </div>
                                                    <div class="col-4">
                                                        <a href="javascript:void(0)" class="thumbnail-link"
                                                            data-bs-target="#carImageCarousel" data-bs-slide-to="2">
                                                            <img src="{{ $car->additional_image ? asset('storage/' . $car->additional_image) : asset('storage/images/placeholder-additional.jpg') }}"
                                                                class="img-thumbnail w-100 "
                                                                alt="Additional Thumbnail"
                                                                style="height: 60px; object-fit: cover;">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Price and Features Section -->
                                            <div class="car-info-section">
                                                <p class="mb-2"><strong>Price per Day:</strong>
                                                    <span
                                                        class="text-primary fw-bold">₱{{ number_format($car->price_per_day, 2) }}</span>
                                                </p>

                                                <!-- Car Features -->
                                                <div
                                                    class="d-flex justify-content-around my-3 text-center bg-light rounded p-2">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-user text-primary mb-1 fs-5"></i>
                                                        <small class="text-muted">{{ $car->setting_capacity }}
                                                            Seats</small>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-gas-pump text-primary mb-1 fs-5"></i>
                                                        <small class="text-muted">{{ $car->fuel }}</small>
                                                    </div>
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-cog text-primary mb-1 fs-5"></i>
                                                        <small class="text-muted">{{ $car->transmission }}</small>
                                                    </div>
                                                </div>

                                                <!-- Additional Car Details -->
                                                <div class="row small text-muted">
                                                    <div class="col-6 mb-1">
                                                        <i class="fas fa-car me-1"></i> Brand: {{ $car->brand }}
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <i class="fas fa-calendar me-1"></i> Year: {{ $car->year }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Booking Form Section -->
                                            <div class="booking-form-section">
                                                <h6 class="mb-3"><i class="fas fa-clipboard-list me-2"></i>Booking
                                                    Information</h6>

                                                <div class="form-group mb-3">
                                                    <label for="Name" class="font-weight-bold">Name <span
                                                            class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        wire:model="guestName">
                                                    @error('guestName')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="Email" class="font-weight-bold">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input type="email" class="form-control"
                                                        wire:model="guestEmail">
                                                    @error('guestEmail')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="PhoneNumber" class="font-weight-bold">Phone Number
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        wire:model="guestPhone">
                                                    @error('guestPhone')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="startDate" class="font-weight-bold">Start Date
                                                                <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" id="startDate"
                                                                wire:model="startDate" wire:change="calculateCost">
                                                            @error('startDate')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="endDate" class="font-weight-bold">End Date
                                                                <span class="text-danger">*</span></label>
                                                            <input type="date" class="form-control" id="endDate"
                                                                wire:model="endDate" wire:change="calculateCost">
                                                            @error('endDate')
                                                                <span class="text-danger small">{{ $message }}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="alert alert-info mb-3">
                                                    <p class="mb-1"><strong>Total Days:</strong> {{ $totalDays }}
                                                    </p>
                                                    <p class="mb-0"><strong>Total Cost:</strong>
                                                        ₱{{ number_format($totalCost, 2) }}</p>
                                                </div>
                                            </div>

                                            <!-- Continue with the rest of your form (Driver's License, Terms, GCash, etc.) -->
                                            <hr>

                                            <!-- Driver's License Upload Section -->
                                            <h6 class="mt-3"><i class="fas fa-id-card me-2"></i> Driver's License
                                            </h6>
                                            <div class="form-group mb-3">
                                                <label for="driversLicense" class="font-weight-bold">Upload Driver's
                                                    License <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="driversLicense"
                                                    wire:model="requirements_valid_id_photo" accept="image/*,.pdf">
                                                <small class="form-text text-muted">Upload a clear photo or scan of
                                                    your valid driver's license (JPG or PNG)</small>
                                                @error('requirements_valid_id_photo')
                                                    <span class="text-danger small d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <hr>

                                            <!-- Terms and Conditions Section -->
                                            <h6 class="mt-3"><i class="fas fa-file-contract me-2"></i> Terms &
                                                Conditions</h6>
                                            <div class="border rounded p-3 mb-3 bg-light"
                                                style="max-height: 150px; overflow-y: auto;">
                                                <div class="small text-muted">
                                                    <p class="mb-1"><strong>1. Age Requirement:</strong> Renter must
                                                        be at least 21 years old with valid driver's license.</p>
                                                    <p class="mb-1"><strong>2. Security Deposit:</strong> ₱5,000.00
                                                        held and released upon return.</p>
                                                    <p class="mb-1"><strong>3. Fuel Policy:</strong> Return with same
                                                        fuel level as at rental time.</p>
                                                    <p class="mb-0"><strong>4. Insurance:</strong> Basic coverage
                                                        included (₱10,000 deductible).</p>
                                                </div>
                                            </div>

                                            <div class="form-check mb-3">
                                                <input type="checkbox" class="form-check-input" id="agreeTerms"
                                                    wire:model="agreeTerms">
                                                <label class="form-check-label small" for="agreeTerms">I agree to the
                                                    terms and conditions</label>
                                                @error('agreeTerms')
                                                    <span class="text-danger small d-block">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <!-- GCash Payment Section -->
                                            <h6 class="mt-3"><i class="fas fa-mobile-alt me-2"></i> GCash Payment
                                            </h6>
                                            <p class="small text-muted mb-2">Send payment to:
                                                <strong>09XX-XXX-XXXX</strong>
                                            </p>

                                            <div class="form-group mb-3">
                                                <label for="gcashReferenceNumber" class="font-weight-bold">GCash
                                                    Reference Number (13 digits) <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="gcashReferenceNumber"
                                                    wire:model="gcashReferenceNumber"
                                                    placeholder="e.g., 1234567890123">
                                                @error('gcashReferenceNumber')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="form-group mb-3">
                                                <label for="gcashReceipt" class="font-weight-bold">Upload GCash
                                                    Receipt <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="gcashReceipt"
                                                    wire:model="gcashReceipt" accept="image/*">
                                                @error('gcashReceipt')
                                                    <span class="text-danger small">{{ $message }}</span>
                                                @enderror
                                                @if ($gcashReceipt)
                                                    <img src="{{ $gcashReceipt->temporaryUrl() }}"
                                                        class="img-fluid mt-2 rounded border"
                                                        style="max-height: 100px;">
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            wire:click="$set('showModal', false)">Cancel</button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-check me-1"></i> Confirm Booking
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Add this script to handle carousel interactions -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Update active thumbnail when carousel slides
            var carousel = document.getElementById('carImageCarousel');
            if (carousel) {
                carousel.addEventListener('slid.bs.carousel', function(event) {
                    var activeIndex = event.to;
                    // Update active thumbnail styling
                    document.querySelectorAll('.thumbnail-link img').forEach(function(img, index) {
                        img.classList.toggle('active-thumbnail', index === activeIndex);
                        img.classList.toggle('border-primary', index === activeIndex);
                    });
                });
            }

            // Make thumbnails clickable
            document.querySelectorAll('.thumbnail-link').forEach(function(link) {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    var target = this.getAttribute('data-bs-target');
                    var slideTo = this.getAttribute('data-bs-slide-to');

                    var carousel = bootstrap.Carousel.getInstance(target);
                    if (carousel) {
                        carousel.to(slideTo);
                    }
                });
            });
        });
    </script>

    <style>
        .active-thumbnail {
            border: 2px solid #0d6efd !important;
        }

        .car-image {
            border-radius: 0.375rem;
        }

        .thumbnail-link img {
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .thumbnail-link img:hover {
            opacity: 0.8;
            transform: scale(1.05);
        }
    </style>
</div>
