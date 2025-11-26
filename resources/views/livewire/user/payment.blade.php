<div>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-lg border-0 payment-card">
                    <div class="card-header payment-header text-white text-center py-4">
                        <!-- Back Button -->
                        <button type="button" class="btn btn-outline-light btn-sm position-absolute start-0 ms-4"
                            onclick="window.history.back()">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </button>
                        <h1 class="h3 mb-2 fw-bold"><i class="fas fa-mobile-alt me-2"></i>GCASH Payment</h1>
                        <p class="mb-0 opacity-75">Complete your transaction securely</p>
                    </div>
                    <div class="card-body p-4 p-md-5">

                        <!-- Step Indicator -->
                        <div class="step-indicator">
                            <div class="step active">
                                <div class="step-number">1</div>
                                <div class="step-label">Scan QR Code</div>
                            </div>
                            <div class="step-line"></div>
                            <div class="step active">
                                <div class="step-number">2</div>
                                <div class="step-label">Enter Details</div>
                            </div>
                            <div class="step-line"></div>
                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-label">Confirmation</div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- QR Code Section -->
                            <div class="col-md-6 mb-4 mb-md-0">
                                <div class="text-center">
                                    <div class="qr-container mb-3">
                                        <img src="{{ asset('images/gcash-pic-qrcode.jpg') }}" alt="GCASH QR Code"
                                            class="img-fluid">
                                    </div>
                                    <h5 class="fw-bold text-primary">Scan to Pay Or Input Gcash Number</h5>
                                    <p class="text-muted small">Open GCASH app and scan this QR code to make payment or
                                    </p>
                                    <p class="text-muted fw-bold">(Gcash Number: 09952184322)</p>

                                    <div class="mt-4">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-shield-alt text-success me-2"></i>
                                            <span class="small">Secure & Encrypted</span>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <i class="fas fa-bolt text-warning me-2"></i>
                                            <span class="small">Instant Processing</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Form Section -->
                            <div class="col-md-6">
                                @if (session()->has('message'))
                                    <div class="alert alert-success d-flex align-items-center" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <div>{{ session('message') }}</div>
                                    </div>
                                @endif

                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <div>Please enter your GCASH transaction details below to complete your payment.
                                    </div>
                                </div>
                                <form wire:submit.prevent="submitPayment">
                                    @csrf

                                    <!-- Upload Receipt -->
                                    <div class="mb-4">
                                        <label class="form-label fw-semibold">
                                            Upload GCash Receipt <span class="text-danger">*</span>
                                        </label>

                                        <input type="file" class="@error('gcash_receipt') is-invalid @enderror"
                                            id="gcashReceipt" wire:model="gcash_receipt" accept="image/*">

                                        <p class="text-muted small mb-0">JPG or PNG (Max 5MB)</p>

                                        @error('gcash_receipt')
                                            <div class="text-danger small mt-2">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Reference Number -->
                                    <div class="mb-4">
                                        <label for="gcashReference" class="form-label fw-semibold">
                                            <i class="fas fa-receipt me-1 text-primary"></i>GCASH Reference Number
                                        </label>

                                        <input type="text"
                                            class="form-control @error('gcash_reference_number') is-invalid @enderror"
                                            id="gcashReference" placeholder="Enter your GCASH reference number"
                                            wire:model="gcash_reference_number" maxlength="13">

                                        @error('gcash_reference_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="form-text">
                                            <i class="fas fa-lightbulb me-1 text-warning"></i>
                                            This is the reference number from your GCASH transaction.
                                        </div>
                                    </div>

                                    <!-- Car ID -->
                                    <div class="mb-4">
                                        <label for="carId" class="form-label fw-semibold">
                                            <i class="fas fa-car me-1 text-primary"></i>Car ID
                                        </label>

                                        <input type="number" class="form-control @error('car_id') is-invalid @enderror"
                                            id="carId" placeholder="Enter your Car ID" wire:model="car_id">

                                        @error('car_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror

                                        <div class="form-text">
                                            <i class="fas fa-lightbulb me-1 text-warning"></i>
                                            This is the ID associated with your vehicle.
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-primary btn-lg fw-semibold py-3"
                                            wire:loading.attr="disabled">

                                            <span wire:loading.remove>
                                                <i class="fas fa-paper-plane me-2"></i>Submit Payment
                                            </span>

                                            <span wire:loading>
                                                <span class="spinner-border spinner-border-sm me-2"
                                                    role="status"></span>
                                                Processing...
                                            </span>

                                        </button>
                                    </div>

                                </form>

                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row text-center text-muted">
                            <div class="col-md-4 mb-2 mb-md-0">
                                <small><i class="fas fa-lock me-1"></i>Secure Payment</small>
                            </div>
                            <div class="col-md-4 mb-2 mb-md-0">
                                <small><i class="fas fa-clock me-1"></i>24/7 Support</small>
                            </div>
                            <div class="col-md-4">
                                <small><i class="fas fa-shield-alt me-1"></i>Data Protected</small>
                            </div>
                        </div>

                        <div class="text-center mt-3">
                            <small>Need help? Contact our support team at <a href="mailto:support@example.com"
                                    class="text-decoration-none">gilsilverioalviorjr@gmail.com</a></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .upload-area {
            transition: background-color 0.2s ease, border-color 0.2s ease;
        }

        .upload-area:hover {
            background-color: #e9ecef !important;
            border-color: #0d6efd !important;
        }

        .payment-header {
            background: linear-gradient(135deg, #0033a0 0%, #0066cc 100%);
        }

        .payment-card {
            transition: transform 0.3s ease;
        }

        .payment-card:hover {
            transform: translateY(-5px);
        }

        .qr-container {
            border: 2px dashed #dee2e6;
            border-radius: 12px;
            padding: 15px;
            background-color: #f8f9fa;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            margin-bottom: 25px;
        }

        .step {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100px;
        }

        .step-number {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #6c757d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .step.active .step-number {
            background-color: #0033a0;
            color: white;
        }

        .step-label {
            font-size: 0.8rem;
            text-align: center;
            color: #6c757d;
        }

        .step.active .step-label {
            color: #0033a0;
            font-weight: 600;
        }

        .step-line {
            flex-grow: 1;
            height: 2px;
            background-color: #e9ecef;
            margin: 15px 10px 0;
        }

        .form-control:focus {
            border-color: #0033a0;
            box-shadow: 0 0 0 0.2rem rgba(0, 51, 160, 0.25);
        }

        .btn-primary {
            background-color: #0033a0;
            border-color: #0033a0;
        }

        .btn-primary:hover {
            background-color: #00257a;
            border-color: #00257a;
        }
    </style>
</div>
