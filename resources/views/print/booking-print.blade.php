<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking #{{ $booking->id }} - Print</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            background: #f8f9fa;
            font-size: 11px;
        }

        .print-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 25px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            color: #0066cc;
            font-size: 22px;
            margin-bottom: 3px;
        }

        .header p {
            color: #666;
            font-size: 10px;
            margin: 2px 0;
        }

        .booking-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
        }

        .info-section {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 5px;
        }

        .info-section h3 {
            color: #0066cc;
            font-size: 13px;
            margin-bottom: 8px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }

        .info-row {
            display: flex;
            margin-bottom: 6px;
            font-size: 10px;
        }

        .info-label {
            font-weight: 600;
            color: #495057;
            width: 110px;
            flex-shrink: 0;
        }

        .info-value {
            color: #212529;
            flex: 1;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 12px;
            font-size: 9px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-confirmed {
            background: #d4edda;
            color: #155724;
        }

        .status-rented_out {
            background: #d1ecf1;
            color: #0c5460;
        }

        .status-returned {
            background: #e2e3e5;
            color: #383d41;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
        }

        .documents-section {
            margin-top: 15px;
        }

        .documents-section h3 {
            color: #0066cc;
            font-size: 13px;
            margin-bottom: 10px;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }

        .documents-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .document-item {
            text-align: center;
        }

        .document-item img {
            width: 100%;
            max-width: 280px;
            height: auto;
            max-height: 180px;
            object-fit: contain;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            margin-bottom: 5px;
        }

        .document-label {
            font-weight: 600;
            color: #495057;
            font-size: 10px;
        }

        .footer {
            margin-top: 15px;
            padding-top: 12px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            color: #6c757d;
            font-size: 9px;
        }

        .footer p {
            margin: 2px 0;
        }

        .print-button {
            background: #0066cc;
            color: white;
            border: none;
            padding: 10px 25px;
            font-size: 13px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }

        .print-button:hover {
            background: #0052a3;
        }

        .summary-box {
            background: #0066cc;
            color: white;
            padding: 12px;
            border-radius: 5px;
            margin-top: 15px;
            text-align: center;
        }

        .summary-box h3 {
            font-size: 11px;
            margin-bottom: 5px;
            font-weight: normal;
        }

        .summary-box .total {
            font-size: 24px;
            font-weight: bold;
        }

        @media print {
            @page {
                size: A4;
                margin: 10mm;
            }

            body {
                padding: 0;
                background: white;
                font-size: 10px;
            }

            .print-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }

            .print-button {
                display: none;
            }

            .header {
                padding-bottom: 10px;
                margin-bottom: 12px;
            }

            .header h1 {
                font-size: 20px;
            }

            .booking-info {
                gap: 10px;
                margin-bottom: 12px;
            }

            .info-section {
                padding: 10px;
            }

            .info-section h3 {
                font-size: 12px;
                margin-bottom: 6px;
            }

            .documents-section {
                margin-top: 12px;
            }

            .documents-grid {
                gap: 10px;
            }

            .document-item img {
                max-height: 150px;
                max-width: 250px;
            }

            .summary-box {
                padding: 10px;
                margin-top: 12px;
            }

            .summary-box .total {
                font-size: 20px;
            }

            .footer {
                margin-top: 12px;
                padding-top: 10px;
            }
        }
    </style>
</head>

<body>
    <div class="print-container">
        <button class="print-button" onclick="window.print()">🖨️ Print Booking</button>

        <!-- Header -->
        <div class="header">
            <h1>RJ Car Rental and Services</h1>
            <p>Villarica, Midsayap, Cotabato</p>
            <p>Booking Confirmation</p>
        </div>

        <!-- Booking Information -->
        <div class="booking-info">
            <!-- Customer Information -->
            <div class="info-section">
                <h3>Customer Information</h3>
                <div class="info-row">
                    <span class="info-label">Booking ID:</span>
                    <span class="info-value"><strong>#{{ $booking->id }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $booking->guest_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $booking->guest_email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $booking->guest_phone_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge status-{{ $booking->status }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </span>
                </div>
            </div>

            <!-- Vehicle Information -->
            <div class="info-section">
                <h3>Vehicle Information</h3>
                <div class="info-row">
                    <span class="info-label">Car ID:</span>
                    <span class="info-value">{{ $booking->car_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Brand:</span>
                    <span class="info-value">{{ $booking->car->brand ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Model:</span>
                    <span class="info-value">{{ $booking->car->year ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Plate Number:</span>
                    <span class="info-value"><strong>{{ $booking->car->plate_number ?? 'N/A' }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Operator:</span>
                    <span class="info-value">{{ $booking->operator }}</span>
                </div>
            </div>

            <!-- Rental Details -->
            <div class="info-section">
                <h3>Rental Details</h3>
                <div class="info-row">
                    <span class="info-label">Start Date:</span>
                    <span class="info-value">{{ $booking->start_date->format('F d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">End Date:</span>
                    <span class="info-value">{{ $booking->end_date->format('F d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Total Days:</span>
                    <span class="info-value"><strong>{{ $booking->total_days }} day(s)</strong></span>
                </div>
                @if ($booking->rented_out_at)
                    <div class="info-row">
                        <span class="info-label">Rented Out:</span>
                        <span class="info-value">{{ $booking->rented_out_at->format('F d, Y') }}</span>
                    </div>
                @endif
                @if ($booking->returned_at)
                    <div class="info-row">
                        <span class="info-label">Returned:</span>
                        <span class="info-value">{{ $booking->returned_at->format('F d, Y') }}</span>
                    </div>
                @endif
            </div>

            <!-- Payment Information -->
            <div class="info-section">
                <h3>Payment Information</h3>
                <div class="info-row">
                    <span class="info-label">GCash Reference:</span>
                    <span class="info-value"><strong>{{ $booking->gcash_reference_number }}</strong></span>
                </div>
                <div class="info-row">
                    <span class="info-label">Daily Rate:</span>
                    <span
                        class="info-value">₱{{ number_format($booking->total_cost / $booking->total_days, 2) }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Booking Date:</span>
                    <span class="info-value">{{ $booking->created_at->format('F d, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Total Cost Summary -->
        <div class="summary-box">
            <h3>Total Rental Cost</h3>
            <div class="total">₱{{ number_format($booking->total_cost, 2) }}</div>
        </div>

        <!-- Documents Section -->
        {{-- <div class="documents-section">
            <h3>Supporting Documents</h3>
            <div class="documents-grid">
                <div class="document-item">
                    <img src="{{ asset('storage/' . $booking->gcash_receipt) }}" alt="GCash Receipt">
                    <div class="document-label">GCash Payment Receipt</div>
                </div>
                <div class="document-item">
                    <img src="{{ asset('storage/' . $booking->requirements_valid_id_photo) }}" alt="Valid ID">
                    <div class="document-label">Valid ID Photo</div>
                </div>
            </div>
        </div> --}}

        <!-- Footer -->
        <div class="footer">
            <p><strong>RJ Car Rental and Services</strong></p>
            <p>Villarica, Midsayap, Cotabato</p>
            <p>Printed on: {{ now()->format('F d, Y h:i A') }}</p>
            <p style="margin-top: 5px; font-style: italic;">Thank you for choosing our service!</p>
        </div>
    </div>
</body>

</html>
