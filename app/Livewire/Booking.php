<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookingModel;
use App\Models\CarsModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\SkyioService;

class Booking extends Component
{
    use WithFileUploads;

    public $showModal = false;
    public $carId;
    public $car;
    public $startDate;
    public $endDate;
    public $totalDays = 0;
    public $totalCost = 0;
    public $requirements_valid_id_photo;
    public $guestName;
    public $guestEmail;
    public $guestPhone;
    public $agreeTerms = false;
    public $destination;
    public $operator = 'self_drive';
    public $operatorFee = 0;
    public $paymentPlan = ''; 
    public $downpaymentAmount = 0;
    public $remainingBalance = 0;

    protected $listeners = ['openBookingModal' => 'openModal'];

    protected $rules = [
        'destination' => 'required|string|max:255',
        'operator' => 'required|in:self_drive,with_driver',
        'paymentPlan' => 'required|in:downpayment,full_payment',
        'startDate' => 'required|date|after_or_equal:today',
        'endDate'   => 'required|date|after_or_equal:startDate',
        'requirements_valid_id_photo' => 'required|image|max:5048',
        'guestName' => 'required|string|max:255',
        'guestEmail' => 'required|email',
        'guestPhone' => 'required|regex:/^09\d{9}$/',
        'agreeTerms' => 'accepted',
    ];

    protected $messages = [
        'agreeTerms.accepted' => 'You must accept the terms and conditions to proceed.',
        'paymentPlan.required' => 'Please select a payment plan.'
    ];

    public function openModal($carId)
    {
        $this->resetValidation();
        $this->reset([
            'destination', 'operator', 'startDate', 'endDate', 'totalDays', 
            'totalCost', 'guestName', 'guestEmail', 'guestPhone', 'agreeTerms',
            'requirements_valid_id_photo', 'paymentPlan', 'downpaymentAmount', 'remainingBalance'
        ]);
        
        $this->carId = $carId;
        $this->car = CarsModel::findOrFail($carId);
        $this->operator = 'self_drive';
        $this->calculateOperatorFee();

        // Pre-fill email if it exists in session
        if (session()->has('current_booking_email')) {
            $this->guestEmail = session('current_booking_email');
        }

        $this->showModal = true;
    }

    public function calculateOperatorFee()
    {
        // Set operator fee based on selection
        if ($this->operator === 'with_driver') {
            $this->operatorFee = 500;
        } else {
            $this->operatorFee = 0;
        }
    }

    public function calculateCost()
    {
        if ($this->startDate && $this->endDate) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $this->startDate);
                $end = Carbon::createFromFormat('Y-m-d', $this->endDate);

                if ($end->greaterThanOrEqualTo($start)) {
                    // Calculate the actual difference in days
                    $this->totalDays = $start->diffInDays($end);
                    
                    // Ensure minimum 1 day rental
                    if ($this->totalDays === 0) {
                        $this->totalDays = 1;
                    }
                    
                    // Calculate operator fee
                    $this->calculateOperatorFee();
                    $operatorTotalFee = $this->operatorFee * $this->totalDays;
                    
                    // Calculate cost based on payment plan
                    if ($this->paymentPlan === 'downpayment') {
                        // For downpayment: only charge downpayment + operator fee
                        $this->downpaymentAmount = $this->car->downpayment;
                        $this->totalCost = $this->downpaymentAmount + $operatorTotalFee;
                        
                        // Calculate remaining balance (full rental cost - downpayment)
                        $fullRentalCost = $this->totalDays * $this->car->price_per_day;
                        $this->remainingBalance = $fullRentalCost - $this->downpaymentAmount;
                    } else {
                        // For full payment: charge per day rate + operator fee
                        $baseCost = $this->totalDays * $this->car->price_per_day;
                        $this->totalCost = $baseCost + $operatorTotalFee;
                        $this->downpaymentAmount = 0;
                        $this->remainingBalance = 0;
                    }
                } else {
                    $this->resetCostCalculations();
                }
            } catch (\Exception $e) {
                $this->resetCostCalculations();
            }
        } else {
            $this->resetCostCalculations();
        }
    }

    protected function resetCostCalculations()
    {
        $this->totalDays = 0;
        $this->totalCost = 0;
        $this->operatorFee = 0;
        $this->downpaymentAmount = 0;
        $this->remainingBalance = 0;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate', 'operator', 'paymentPlan'])) {
            $this->calculateCost();
        }

        if (isset($this->$propertyName)) {
            $this->validateOnly($propertyName);
        }
    }

    public function bookCar()
    {
        $this->validate();

        $existingBooking = BookingModel::where('car_id', $this->carId)
            ->where('guest_email', $this->guestEmail)
            ->whereIn('status', ['confirmed', 'pending'])
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('start_date', '<=', $this->endDate)
                        ->where('end_date', '>=', $this->startDate);
                });
            })
            ->exists();

        if ($existingBooking) {
            $this->addError('guestEmail', 'You already have an existing booking for this car during the selected dates.');
            return;
        }

        // Check availability again before booking
        if (!$this->car->isAvailable($this->startDate, $this->endDate)) {
            session()->flash('error', 'Sorry this car is no longer available for the selected dates.');
            return;
        }

        // Store the valid ID image
        $validIdPath = $this->requirements_valid_id_photo->store('requirements_valid_id_photo', 'public');

        // Create the booking
        $booking = BookingModel::create([
            'guest_name' => $this->guestName,
            'guest_email' => $this->guestEmail,
            'guest_phone_number' => $this->guestPhone,
            'car_id' => $this->carId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'total_days' => $this->totalDays,
            'total_cost' => $this->totalCost,
            'operator' => $this->operator,
            'operator_fee' => $this->operatorFee,
            'operator_total_fee' => $this->operatorFee * $this->totalDays,
            'destination' => $this->destination,
            'payment_plan' => $this->paymentPlan,
            'downpayment_amount' => $this->downpaymentAmount,
            'remaining_balance' => $this->remainingBalance,
            'requirements_valid_id_photo' => $validIdPath,
            'status' => 'pending',
        ]);

        // Store user email in session for future bookings
        session(['current_booking_email' => $this->guestEmail]);
        session(['last_booking_email' => $this->guestEmail]);

        // Send SMS to admin about new booking
        $this->sendNewBookingSMSToAdmin($booking);

        $this->showModal = false;
        session()->flash('success', 'Booking submitted successfully! Please wait for admin confirmation.');
        $this->dispatch('bookingCreated');
    }

    // protected function sendNewBookingSMSToAdmin($booking)
    // {
    //     $skyioService = new SkyioService();
    //     $adminPhone = env('ADMIN_PHONE_NUMBER');

    //     if (!$adminPhone) {
    //         \Log::warning('Admin phone number not set in environment variables.');
    //         return;
    //     }

    //     $formattedAdminPhone = $this->formatPhoneNumber($adminPhone);

    //     if (!str_starts_with($formattedAdminPhone, '+63') || strlen($formattedAdminPhone) !== 13) {
    //         \Log::warning('Admin phone number format invalid. Required format: 09XXXXXXXXX');
    //         return;
    //     }

    //     $carDetails = $this->car->brand . ' ' . $this->car->plate_number;
    //     $paymentPlanText = $booking->payment_plan === 'downpayment' ? 'Downpayment' : 'Full Payment';
        
    //     $message =
    //         "NEW BOOKING CONFIRMATION\n\n" .
    //         "Booking Reference : #{$booking->id}\n" .
    //         "Customer Name     : {$booking->guest_name}\n" .
    //         "Vehicle Booked    : {$carDetails}\n" .
    //         "Booking Dates     : " . Carbon::parse($booking->start_date)->format('M d, Y') .
    //         " to " . Carbon::parse($booking->end_date)->format('M d, Y') . "\n" .
    //         "Payment Plan      : {$paymentPlanText}\n" .
    //         "Total Amount Due  : PHP " . number_format($booking->total_cost, 2) . "\n\n" .
    //         // "Please review the booking details in the admin panel.";

    //     $response = $skyioService->sendSMS($formattedAdminPhone, $message);

    //     if (isset($response['error'])) {
    //         \Log::error('Failed to send admin SMS: ' . $response['error']);
    //     } else {
    //         \Log::info('Admin notification SMS sent successfully');
    //     }
    // }

      protected function sendNewBookingSMSToAdmin($booking)
    {
        $skyioService = new SkyioService();
        $adminPhone = env('ADMIN_PHONE_NUMBER');

        if (!$adminPhone) {
            \Log::warning('Admin phone number not set in environment variables.');
            return;
        }

        $formattedAdminPhone = $this->formatPhoneNumber($adminPhone);

        if (!str_starts_with($formattedAdminPhone, '+63') || strlen($formattedAdminPhone) !== 13) {
            \Log::warning('Admin phone number format invalid. Required format: 09XXXXXXXXX');
            return;
        }

        $carDetails = $this->car->brand . ' ' . $this->car->plate_number;
        $paymentPlanText = $booking->payment_plan === 'downpayment' ? 'Downpayment' : 'Full Payment';
        
        // Build the message based on payment plan
        $message = "NEW BOOKING CONFIRMATION\n\n" .
            "Booking Reference : #{$booking->id}\n" .
            "Customer Name     : {$booking->guest_name}\n" .
            "Customer Phone    : {$booking->guest_phone_number}\n" .
            "Vehicle Booked    : {$carDetails}\n" .
            "Booking Dates     : " . Carbon::parse($booking->start_date)->format('M d, Y') .
            " to " . Carbon::parse($booking->end_date)->format('M d, Y') . "\n" .
            "Total Days        : {$booking->total_days} day(s)\n" .
            "Payment Plan      : {$paymentPlanText}\n\n";

        // Add payment details based on plan
        if ($booking->payment_plan === 'downpayment') {
            $message .= "PAYMENT BREAKDOWN:\n" .
                "Downpayment       : PHP " . number_format($booking->downpayment_amount, 2) . "\n";
            
            if ($booking->operator_total_fee > 0) {
                $message .= "Driver Fee        : PHP " . number_format($booking->operator_total_fee, 2) . "\n";
            }
            
            $message .= "Amount Paid Now   : PHP " . number_format($booking->total_cost, 2) . "\n" .
                "Remaining Balance : PHP " . number_format($booking->remaining_balance, 2) . "\n" .
                "(To be paid on pickup)\n";
        } else {
            $message .= "PAYMENT BREAKDOWN:\n";
            
            $rentalCost = $booking->total_days * $this->car->price_per_day;
            $message .= "Rental Cost       : PHP " . number_format($rentalCost, 2) . "\n";
            
            if ($booking->operator_total_fee > 0) {
                $message .= "Driver Fee        : PHP " . number_format($booking->operator_total_fee, 2) . "\n";
            }
            
            $message .= "Total Amount      : PHP " . number_format($booking->total_cost, 2) . "\n";
        }

        $message .= "\nPlease review the booking in the admin panel.";

        $response = $skyioService->sendSMS($formattedAdminPhone, $message);

        if (isset($response['error'])) {
            \Log::error('Failed to send admin SMS: ' . $response['error']);
        } else {
            \Log::info('Admin notification SMS sent successfully');
        }
    }

    protected function formatPhoneNumber($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        
        if (strlen($phone) === 11 && str_starts_with($phone, '09')) {
            return '+63' . substr($phone, 1);
        }
        
        return $phone;
    }

    public function render()
    {
        return view('livewire.booking')->layout('layouts.app');
    }
}