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
    public $gcashReferenceNumber;
    public $gcashReceipt;
    public $guestName;
    public $guestEmail;
    public $guestPhone;
    public $agreeTerms = false;
    public $destination;
    public $operator = 'self drive';
    public $operatorFee = 0;

    protected $listeners = ['openBookingModal' => 'openModal'];

    protected $rules = [
        'destination' => 'required|string|max:255',
        'operator' => 'required|in:self_drive,with_driver',
        'startDate' => 'required|date|after_or_equal:today',
        'endDate'   => 'required|date|after_or_equal:startDate',
        'gcashReferenceNumber' => 'required|string|digits:13',
        'requirements_valid_id_photo' => 'required|image|max:2048',
        'gcashReceipt' => 'required|image|max:2048', // 2MB Max
        'guestName' => 'required|string|max:255',
        'guestEmail' => 'required|email',
        'guestPhone' => 'required|regex:/^09\d{9}$/',
        'agreeTerms' => 'accepted',
    ];

    protected $messages = [
        'agreeTerms.accepted' => 'You must accept the terms and conditions to proceed.'
    ];

    public function openModal($carId)
    {
        $this->resetValidation();
        $this->reset(['destination','operator','startDate', 'endDate', 'totalDays', 'totalCost', 'gcashReferenceNumber', 'gcashReceipt','guestName','guestEmail','guestPhone','agreeTerms','requirements_valid_id_photo']);
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

    // public function calculateCost()
    // {
    //     if ($this->startDate && $this->endDate) {
    //         try {
    //             $start = Carbon::createFromFormat('Y-m-d', $this->startDate);
    //             $end = Carbon::createFromFormat('Y-m-d', $this->endDate);

    //             if ($end->greaterThanOrEqualTo($start)) {
    //                 $this->totalDays = $start->diffInDays($end) + 1;
                    
    //                 // Calculate base cost (car rental only)
    //                 $baseCost = $this->totalDays * $this->car->price_per_day;
                    
    //                 // Calculate operator fee
    //                 $this->calculateOperatorFee();
    //                 $operatorTotalFee = $this->operatorFee * $this->totalDays;
                    
    //                 // Total cost = base cost + operator fee
    //                 $this->totalCost = $baseCost + $operatorTotalFee;
    //             } else {
    //                 $this->totalDays = 0;
    //                 $this->totalCost = 0;
    //                 $this->operatorFee = 0;
    //             }
    //         } catch (\Exception $e) {
    //             $this->totalDays = 0;
    //             $this->totalCost = 0;
    //             $this->operatorFee = 0;
    //         }
    //     } else {
    //         $this->totalDays = 0;
    //         $this->totalCost = 0;
    //         $this->operatorFee = 0;
    //     }
    // }

    public function calculateCost()
    {
        if ($this->startDate && $this->endDate) {
            try {
                $start = Carbon::createFromFormat('Y-m-d', $this->startDate);
                $end = Carbon::createFromFormat('Y-m-d', $this->endDate);

                if ($end->greaterThanOrEqualTo($start)) {
                    // Calculate the actual difference in days (no +1)
                    $this->totalDays = $start->diffInDays($end);
                    
                    // Ensure minimum 1 day rental
                    if ($this->totalDays === 0) {
                        $this->totalDays = 1;
                    }
                    
                    // Calculate base cost (car rental only)
                    $baseCost = $this->totalDays * $this->car->price_per_day;
                    
                    // Calculate operator fee
                    $this->calculateOperatorFee();
                    $operatorTotalFee = $this->operatorFee * $this->totalDays;
                    
                    // Total cost = base cost + operator fee
                    $this->totalCost = $baseCost + $operatorTotalFee;
                } else {
                    $this->totalDays = 0;
                    $this->totalCost = 0;
                    $this->operatorFee = 0;
                }
            } catch (\Exception $e) {
                $this->totalDays = 0;
                $this->totalCost = 0;
                $this->operatorFee = 0;
            }
        } else {
            $this->totalDays = 0;
            $this->totalCost = 0;
            $this->operatorFee = 0;
        }
    }


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate','operator'])) {
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
                    // New booking overlaps with existing booking
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
            session()->flash('error', 'Sorry this car is no longer available for the  selected dates.');

            return;
        }

        // Store the receipt image
        $receiptPath = $this->gcashReceipt->store('gcash-receipts', 'public');
        $validIdPath = $this->requirements_valid_id_photo->store('requirements_valid_id_photo', 'public');

        //Create the booking
        $booking =  BookingModel::create([
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
            'requirements_valid_id_photo' => $validIdPath,
            'gcash_reference_number' => $this->gcashReferenceNumber,
            'gcash_receipt' => $receiptPath,
            'status' => 'pending',
        ]);

         // Store user email in session for future bookings
        session(['current_booking_email' => $this->guestEmail]);
        session(['last_booking_email' => $this->guestEmail]); // Backup session key

          // Send SMS to admin about new booking
        $this->sendNewBookingSMSToAdmin($booking);

        $this->showModal = false;
        session()->flash('success','Booking submitted successfully! Please wait for admin confirmation.');
        $this->dispatch('bookingCreated');

    }

    protected function sendNewBookingSMSToAdmin($booking)
    {
        $skyioService = new SkyioService();
        $adminPhone = env('ADMIN_PHONE_NUMBER');

        if (!$adminPhone) {
            \Log::warning('Admin phone number not set in environment variables.');
            return;
        }

        // Format admin phone number
        $formattedAdminPhone = $this->formatPhoneNumber($adminPhone);

        // Check if phone number is properly formatted
        if (!str_starts_with($formattedAdminPhone, '+63') || strlen($formattedAdminPhone) !== 13) {
            \Log::warning('Admin phone number format invalid. Required format: 09XXXXXXXXX');
            return;
        }

        $carDetails = $this->car->brand . ' ' . $this->car->plate_number;
        $message =
                "NEW BOOKING CONFIRMATION\n\n" .
                "Booking Reference : #{$booking->id}\n" .
                "Customer Name     : {$booking->guest_name}\n" .
                "Vehicle Booked    : {$carDetails}\n" .
                "Booking Dates     : " . Carbon::parse($booking->start_date)->format('M d, Y') .
                " to " . Carbon::parse($booking->end_date)->format('M d, Y') . "\n" .
                "Total Amount Due  : PHP " . number_format($booking->total_cost, 2) . "\n\n" .
                "Please review the booking details in the admin panel.";


        $response = $skyioService->sendSMS($formattedAdminPhone, $message);

        // Log SMS response for debugging
        if (isset($response['error'])) {
            \Log::error('Failed to send admin SMS: ' . $response['error']);
        } else {
            \Log::info('Admin notification SMS sent successfully');
        }
    }

    protected function formatPhoneNumber($phone)
    {
        // Remove all non-digit characters
        $phone = preg_replace('/\D/', '', $phone);
        
        // Validate if it matches 09 + 9 digits (11 digits total)
        if (strlen($phone) === 11 && str_starts_with($phone, '09')) {
            // Convert 09171234567 to +639171234567
            return '+63' . substr($phone, 1);
        }
        
        // If it doesn't match the required format, return as is (will likely fail SMS sending)
        return $phone;
    }

    public function render()
    {
        return view('livewire.booking')->layout('layouts.app');
    }
}
