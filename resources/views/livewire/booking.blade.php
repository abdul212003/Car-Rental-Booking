<div>
    @if ($showModal)
        <div wire:ignore.self class="modal fade show d-block" tabindex="-1" role="dialog"
            aria-labelledby="bookingModalLabel" aria-hidden="true" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title" id="bookingModalLabel">Book {{ $car->brand ?? '' }}
                            {{ $car->model ?? '' }}</h5>
                        <button type="button" class="close text-black" data-dismiss="modal" aria-label="Close"
                            wire:click="$set('showModal', false)">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @if ($car)
                            <form wire:submit.prevent="bookCar">
                                <div class="row">
                                    <div class="col-md-6">
                                        <img src="{{ $car->image ? asset('storage/' . $car->image) : asset('storage/images/placeholder-car.jpg') }}"
                                            class="img-fluid rounded" alt="Car Image">
                                        <p class="mt-2"><strong>Price per Day:</strong>
                                            ₱{{ number_format($car->price_per_day, 2) }}</p>

                                        <!-- Car Features -->
                                        <div class="d-flex justify-content-around my-3 text-center">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-user text-secondary mb-1"></i>
                                                <small class="text-muted">5 Seats</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-gas-pump text-secondary mb-1"></i>
                                                <small class="text-muted">Hybrid</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-cog text-secondary mb-1"></i>
                                                <small class="text-muted">Automatic</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="Name" class="font-weight-bold">Name <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" wire:model="guestName">
                                            @error('guestName')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="Email" class="font-weight-bold">Email <span
                                                    class="text-danger">*</span></label>
                                            <input type="email" class="form-control" wire:model="guestEmail">
                                            @error('guestEmail')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="PhoneNumber" class="font-weight-bold">Phone Number <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" wire:model="guestPhone">
                                            @error('guestPhone')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="startDate" class="font-weight-bold">Start Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="startDate"
                                                wire:model="startDate" wire:change="calculateCost">
                                            @error('startDate')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="endDate" class="font-weight-bold">End Date <span
                                                    class="text-danger">*</span></label>
                                            <input type="date" class="form-control" id="endDate"
                                                wire:model="endDate" wire:change="calculateCost">
                                            @error('endDate')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="alert alert-info">
                                            <p class="mb-1"><strong>Total Days:</strong> {{ $totalDays }}</p>
                                            <p class="mb-0"><strong>Total Cost:</strong>
                                                ₱{{ number_format($totalCost, 2) }}</p>
                                        </div>
                                        <hr>

                                        <!-- Terms and Conditions Section -->
                                        <h5 class="mt-3"><i class="fas fa-file-contract mr-2"></i> Terms & Conditions
                                        </h5>
                                        <div class="border rounded p-3 mb-3 bg-light"
                                            style="max-height: 200px; overflow-y: auto;">
                                            <h6 class="font-weight-bold text-dark">Rental Agreement Terms:</h6>
                                            <div class="small text-muted">
                                                <p class="mb-1">1. The renter must be at least 21 years old and
                                                    possess a valid driver's license.</p>
                                                <p class="mb-1">2. A security deposit of ₱5,000.00 will be held and
                                                    released upon return.</p>
                                                <p class="mb-1">3. The vehicle must be returned with the same fuel
                                                    level as at rental time.</p>
                                                <p class="mb-1">4. No smoking is allowed in the vehicle (₱1,500
                                                    cleaning fee for violations).</p>
                                                <p class="mb-1">5. The rental includes basic insurance coverage
                                                    (₱10,000 deductible).</p>
                                                <p class="mb-1">6. Late returns will be charged at 1.5x the hourly
                                                    rate after 1-hour grace period.</p>
                                                <p class="mb-1">7. The vehicle must not be used for illegal activities
                                                    or towing without permission.</p>
                                                <p class="mb-1">8. In case of accident or breakdown, contact us
                                                    immediately at our 24/7 hotline.</p>
                                                <p class="mb-1">9. The renter is responsible for all traffic and
                                                    parking violations.</p>
                                                <p class="mb-0">10. We reserve the right to charge for damages or
                                                    missing items.</p>
                                            </div>
                                        </div>

                                        <div class="form-check mb-3">
                                            <input type="checkbox" class="form-check-input" id="agreeTerms"
                                                wire:model="agreeTerms">
                                            <label class="form-check-label" for="agreeTerms">I have read and agree to
                                                the terms and conditions</label>
                                            @error('agreeTerms')
                                                <span class="text-danger small d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <h6 class="mt-4">GCash Payment</h6>
                                        <p class="small text-muted">Please send payment to GCash Account #:
                                            <strong>09XX-XXX-XXXX</strong>
                                        </p>

                                        <div class="form-group">
                                            <label for="gcashReferenceNumber" class="font-weight-bold">GCash Reference
                                                Number (13 digits) <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="gcashReferenceNumber"
                                                wire:model="gcashReferenceNumber" placeholder="e.g., 1234567890123">
                                            @error('gcashReferenceNumber')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="gcashReceipt" class="font-weight-bold">Upload GCash Receipt
                                                Screenshot <span class="text-danger">*</span></label>
                                            <input type="file" class="form-control-file" id="gcashReceipt"
                                                wire:model="gcashReceipt" accept="image/*">
                                            @error('gcashReceipt')
                                                <span class="text-danger small">{{ $message }}</span>
                                            @enderror
                                            @if ($gcashReceipt)
                                                <img src="{{ $gcashReceipt->temporaryUrl() }}"
                                                    class="img-fluid mt-2 rounded border" style="max-height: 150px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        wire:click="$set('showModal', false)">Cancel</button>
                                    <button type="submit" class="btn btn-primary">Confirm Booking</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
