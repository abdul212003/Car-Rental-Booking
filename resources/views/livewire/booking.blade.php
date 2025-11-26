<div>
    <div>
        @if ($showModal)
            <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
                aria-labelledby="bookingModalLabel" aria-hidden="false" style="background-color: rgba(0,0,0,0.5);">
                <div class="modal-dialog modal-xl" role="document">
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
                                                <p class="mb-2"><strong>Downpayment:</strong>
                                                    <span
                                                        class="text-primary fw-bold">₱{{ number_format($car->downpayment, 2) }}</span>
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
                                                        <i class="fas fa-calendar text-warning me-1"></i> Year:
                                                        {{ $car->year }}
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <i class="fas fa-palette text-primary me-1"></i> Color:
                                                        {{ $car->color }}
                                                    </div>
                                                    <div class="col-6 mb-1">
                                                        <i class="fas fa-car-side text-success me-1"></i> Plate Number:
                                                        {{ $car->plate_number }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <!-- Booking Form Section -->
                                            <!-- Booking Form Section - Replace your existing form fields -->
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
                                                    <input type="number" class="form-control"
                                                        wire:model="guestPhone"
                                                        oninput="if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                                                    @error('guestPhone')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="Destination" class="font-weight-bold">Destination
                                                        <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control"
                                                        wire:model="destination">
                                                    @error('destination')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="operator" class="font-weight-bold">Operator Type <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="operator" wire:model="operator"
                                                        wire:change="calculateCost">
                                                        <option value="">-- Select Operator Type --</option>
                                                        <option value="self_drive">Self Drive (No additional cost)
                                                        </option>
                                                        <option value="with_driver">With Driver (+₱500 per day)
                                                        </option>
                                                    </select>
                                                    @error('operator')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror

                                                    @if ($operatorFee > 0)
                                                        <small class="text-info d-block mt-1">
                                                            <i class="fas fa-info-circle"></i> Driver fee:
                                                            ₱{{ number_format($operatorFee, 2) }} per day
                                                        </small>
                                                    @endif
                                                </div>

                                                <!-- PAYMENT PLAN SECTION -->
                                                <div class="form-group mb-3">
                                                    <label for="payment" class="font-weight-bold">Payment Plan <span
                                                            class="text-danger">*</span></label>
                                                    <select class="form-control" id="payment"
                                                        wire:model="paymentPlan" wire:change="calculateCost">
                                                        <option value="">-- Select Payment Plan --</option>
                                                        <option value="downpayment">Downpayment Only
                                                            (₱{{ number_format($car->downpayment ?? 0, 2) }})</option>
                                                        <option value="full_payment">Full Payment (Per Day Rate)
                                                        </option>
                                                    </select>
                                                    @error('paymentPlan')
                                                        <span class="text-danger small">{{ $message }}</span>
                                                    @enderror

                                                    @if ($paymentPlan === 'downpayment')
                                                        <div class="alert alert-info mt-2 small mb-0">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            <strong>Downpayment Plan:</strong> You will pay
                                                            ₱{{ number_format($car->downpayment ?? 0, 2) }} now
                                                            + driver fee (if applicable). The remaining balance will be
                                                            paid upon vehicle pickup.
                                                        </div>
                                                    @elseif ($paymentPlan === 'full_payment')
                                                        <div class="alert alert-info mt-2 small mb-0">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            <strong>Full Payment Plan:</strong> Total rental cost
                                                            calculated based on number of days
                                                            (₱{{ number_format($car->price_per_day ?? 0, 2) }} per day)
                                                            + driver fee (if applicable).
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- DATE SELECTION -->
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

                                                <!-- COST SUMMARY -->
                                                <div class="alert alert-info mb-3">
                                                    <h6 class="fw-bold mb-2">
                                                        <i class="fas fa-calculator me-1"></i> Booking Summary
                                                    </h6>

                                                    <p class="mb-1"><strong>Total Days:</strong> {{ $totalDays }}
                                                    </p>

                                                    @if ($paymentPlan === 'downpayment')
                                                        <hr class="my-2">
                                                        <p class="mb-1">
                                                            <strong>Downpayment:</strong>
                                                            ₱{{ number_format($downpaymentAmount, 2) }}
                                                        </p>

                                                        @if ($operatorFee > 0)
                                                            <p class="mb-1">
                                                                <strong>Driver Fee:</strong>
                                                                ₱{{ number_format($operatorFee * $totalDays, 2) }}
                                                                <small
                                                                    class="text-muted">(₱{{ number_format($operatorFee, 2) }}/day
                                                                    × {{ $totalDays }} days)</small>
                                                            </p>
                                                        @endif

                                                        <p class="mb-1 fw-bold text-primary fs-5">
                                                            <strong>Total Due Now:</strong>
                                                            ₱{{ number_format($totalCost, 2) }}
                                                        </p>

                                                        @if ($remainingBalance > 0)
                                                            <hr class="my-2">
                                                            <p class="mb-0 text-warning">
                                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                                <strong>Remaining Balance (Pay on Pickup):</strong>
                                                                ₱{{ number_format($remainingBalance, 2) }}
                                                            </p>
                                                        @endif
                                                    @elseif ($paymentPlan === 'full_payment')
                                                        <hr class="my-2">
                                                        <p class="mb-1">
                                                            <strong>Car Rental:</strong>
                                                            ₱{{ number_format($totalDays * ($car->price_per_day ?? 0), 2) }}
                                                            <small
                                                                class="text-muted">(₱{{ number_format($car->price_per_day ?? 0, 2) }}/day
                                                                × {{ $totalDays }} days)</small>
                                                        </p>

                                                        @if ($operatorFee > 0)
                                                            <p class="mb-1">
                                                                <strong>Driver Fee:</strong>
                                                                ₱{{ number_format($operatorFee * $totalDays, 2) }}
                                                                <small
                                                                    class="text-muted">(₱{{ number_format($operatorFee, 2) }}/day
                                                                    × {{ $totalDays }} days)</small>
                                                            </p>
                                                        @endif

                                                        <p class="mb-0 fw-bold text-primary fs-5">
                                                            <strong>Total Cost:</strong>
                                                            ₱{{ number_format($totalCost, 2) }}
                                                        </p>
                                                    @else
                                                        <hr class="my-2">
                                                        <p class="mb-0 text-muted">
                                                            <i class="fas fa-info-circle me-1"></i>
                                                            Please select a payment plan to see cost breakdown
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Continue with the rest of your form (Driver's License, Terms, GCash, etc.) -->
                                            <hr>
                                            <!-- Driver's License Upload Section -->
                                            <div class="card border-0 shadow-sm mb-4">
                                                <div class="card-body">
                                                    <h6 class="card-title mb-3">
                                                        <i class="fas fa-id-card text-primary me-2"></i> Driver's
                                                        License
                                                    </h6>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">
                                                            Upload Driver's License
                                                            <span class="text-danger">*</span>
                                                        </label>

                                                        <div class="upload-area-license border-2 border-dashed rounded-3 p-4 text-center bg-light"
                                                            onclick="document.getElementById('driversLicense').click()"
                                                            style="cursor: pointer; border-color: #dee2e6;">
                                                            <i
                                                                class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
                                                            <p class="mb-2 fw-semibold">Click to upload or drag and
                                                                drop</p>
                                                            <p class="text-muted small mb-0">JPG, PNG or PDF (Max 5MB)
                                                            </p>
                                                        </div>

                                                        <input type="file"
                                                            class="d-none @error('requirements_valid_id_photo') is-invalid @enderror"
                                                            id="driversLicense"
                                                            wire:model="requirements_valid_id_photo"
                                                            accept="image/*,.pdf"
                                                            onchange="displayLicenseFileName(this)">

                                                        <div id="licenseFileName" class="mt-2 small text-muted"></div>
                                                        @if ($requirements_valid_id_photo)
                                                            <div class="mt-3">
                                                                <label
                                                                    class="form-label small text-muted">Preview:</label>
                                                                <div
                                                                    class="border rounded-3 p-2 bg-white d-inline-block">
                                                                    <img src="{{ $requirements_valid_id_photo->temporaryUrl() }}"
                                                                        class="img-fluid rounded"
                                                                        style="max-height: 150px; max-width: 100%;">
                                                                </div>
                                                            </div>
                                                        @endif

                                                        @error('requirements_valid_id_photo')
                                                            <div class="text-danger small mt-2">
                                                                <i
                                                                    class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>

                                            <!-- Terms and Conditions Section -->
                                            <h6 class="mt-3"><i class="fas fa-file-contract me-2"></i> Terms &
                                                Conditions</h6>
                                            <div class="border rounded p-3 mb-3 bg-light"
                                                style="max-height: 150px; overflow-y: auto;">
                                                <div class="small text-muted">
                                                    <p class="mb-1"><strong>1.</strong>The renter
                                                        must return the vehicle to our office from the date and time
                                                        specifiedin this agreement in the same condition when they
                                                        receive it.</p>
                                                    <p class="mb-1"><strong>2.</strong>The renter
                                                        must check and maintain all fluid levels including the brake
                                                        fluid levelin the master cylinder.</p>
                                                    <p class="mb-1"><strong>3.</strong>The renter must
                                                        responsible all the damages and lost of the vehicle's
                                                        missingaccessories.</p>
                                                    <p class="mb-0"><strong>4.</strong>The renter agrees
                                                        to pay with the following rental payment;Rental Fee per day :
                                                        Php 2,000.00 (Sedan) Php 3,000.00(SUV)Car Wash
                                                        : Php 200.00
                                                        Exceeding Hour: Php 150.00 per hour(Sedan) Php 200.00(SUV)</p>
                                                    <p class="mb-0"><strong>5.</strong>If the renter failed to return
                                                        the vehicle on time specified above he/she shall becharge for
                                                        penalty of Php 150.00 per exceeding hour.</p>
                                                    <p class="mb-0"><strong>6.</strong>All fuel consume during the
                                                        trip shall be paid by the renter.</p>
                                                    <p class="mb-0"><strong>7.</strong>Strictly no refund of fuel.
                                                    </p>
                                                    <p class="mb-0"><strong>8.</strong>Strictly NO CONTRABAND allowed
                                                        inside the vehicle.
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Accident Section -->
                                            <h6 class="mt-3"><i class="fas fa-burst text-danger me-2"></i> Accidents
                                            </h6>
                                            <div class="border rounded p-3 mb-3 bg-light"
                                                style="max-height: 150px; overflow-y: auto;">
                                                <div class="small text-muted">
                                                    <p class="mb-1">In the' case where the renter
                                                        is involved in an accident and a third party is alsoinvolved in
                                                        injuries, the renter agrees to be held responsible for the total
                                                        amount ofthe damages to the Vehicle and injuries to the third
                                                        parties.</p>
                                                </div>
                                            </div>

                                            <!-- Repair Rights Section -->
                                            <h6 class="mt-3"><i class="fas fa-wrench text-primary me-2"></i> Repair
                                                Rights
                                            </h6>
                                            <div class="border rounded p-3 mb-3 bg-light"
                                                style="max-height: 150px; overflow-y: auto;">
                                                <div class="small text-muted">
                                                    <p class="mb-1">RJ Car Rental has the right to
                                                        have the vehicle repaired at the auto collision shop ofits
                                                        choice.</p>
                                                </div>
                                            </div>

                                            <!-- Owner's Warranty Section -->
                                            <h6 class="mt-3"><i class="fas fa-shield-alt text-primary me-2"></i>
                                                Owner's
                                                Warranty
                                            </h6>
                                            <div class="border rounded p-3 mb-3 bg-light"
                                                style="max-height: 150px; overflow-y: auto;">
                                                <div class="small text-muted">
                                                    <p class="mb-1">The owner represent that the
                                                        best of his knowledge and belief that the vehicle is insound and
                                                        good condition that would affect its safe operation under normal
                                                        use.</p>
                                                </div>
                                            </div>

                                            <!-- Renter's Warranty Section -->
                                            <h6 class="mt-3"><i class="fas fa-user-shield text-primary me-2"></i>
                                                Renter's
                                                Warranty
                                            </h6>
                                            <div class="border rounded p-3 mb-3 bg-light"
                                                style="max-height: 150px; overflow-y: auto;">
                                                <div class="small text-muted">
                                                    <h4 class="fw-bold">The renter agrees that he/ she will NOT;</h4>
                                                    <p class="mb-1"><strong>a.</strong>Use the vehicle to carry any
                                                        passenger other than the renter;</p>
                                                    <p class="mb-1"><strong>b.</strong>Allow any person to operate
                                                        the vehicle;</p>
                                                    <p class="mb-1"><strong>c.</strong>Operate the vehicle in
                                                        violation of any laws or any illegal purposes that therenter
                                                        does. The renter is responsible for all assorted tickets, fines
                                                        and fees;</p>
                                                    <p class="mb-1"><strong>d.</strong>Use the vehicle to push or tow
                                                        another vehicle:</p>
                                                    <p class="mb-1"><strong>e.</strong>Use the vehicle for any race
                                                        or competition</p>
                                                    <p class="mb-1"><strong>f.</strong>Operate the vehicle in
                                                        negligence manner.</p>
                                                </div>
                                            </div>

                                            <!-- Effectivity Section -->
                                            <h6 class="mt-3"><i class="fas fa-calendar-check text-success me-2"></i>
                                                Effectivity
                                            </h6>
                                            <div class="border rounded p-3 mb-3 bg-light"
                                                style="max-height: 150px; overflow-y: auto;">
                                                <div class="small text-muted">
                                                    <p class="mb-1">This agreement shall take
                                                        effect upon the delivery/ pick-up of the rented vehicle.</p>
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
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            wire:click="$set('showModal', false)">Cancel</button>
                                        <button type="submit" class="btn btn-primary">
                                            Confirm Booking
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
        function displayGcashFileName(input) {
            const fileNameDiv = document.getElementById('gcashFileName');
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                fileNameDiv.innerHTML =
                    `<i class="fas fa-file-image me-1"></i> <strong>${fileName}</strong> (${fileSize} MB)`;
            }
        }

        function displayLicenseFileName(input) {
            const fileNameDiv = document.getElementById('licenseFileName');
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                fileNameDiv.innerHTML =
                    `<i class="fas fa-file-alt me-1"></i> <strong>${fileName}</strong> (${fileSize} MB)`;
            }
        }
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
        .upload-area:hover {
            background-color: #e9ecef !important;
            border-color: #0d6efd !important;
        }

        .upload-area-license:hover {
            background-color: #e9ecef !important;
            border-color: #0d6efd !important;
        }

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
