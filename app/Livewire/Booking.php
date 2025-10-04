<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\BookingModel;
use App\Models\CarsModel;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

    protected $listeners = ['openBookingModal' => 'openModal'];

    protected $rules = [
        'startDate' => 'required|date|after_or_equal:today',
        'endDate'   => 'required|date|after_or_equal:startDate',
        'gcashReferenceNumber' => 'required|string|size:13',
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
        $this->reset(['startDate', 'endDate', 'totalDays', 'totalCost', 'gcashReferenceNumber', 'gcashReceipt','guestName','guestEmail','guestPhone','agreeTerms','requirements_valid_id_photo']);
        $this->carId = $carId;
        $this->car = CarsModel::findOrFail($carId);

        // Pre-fill email if it exists in session
        if (session()->has('current_booking_email')) {
            $this->guestEmail = session('current_booking_email');
        }

        $this->showModal = true;

    }

    public function calculateCost()
    {
        if ($this->startDate && $this->endDate) {
            try{
                $start = Carbon::createFromFormat('Y-m-d', $this->startDate);
                $end = Carbon::createFromFormat('Y-m-d', $this->endDate);

            if ($end->greaterThanOrEqualTo($start)) 
                {
                    $this->totalDays = $start->diffInDays($end) + 1;
                    $this->totalCost = $this->totalDays * $this->car->price_per_day;
                } else {
                    $this->totalDays = 0;
                    $this->totalCost = 0;
                }
            } catch (\Exception $e) {
                $this->totalDays = 0;
                $this->totalCost = 0;
            }
            
        }
        else 
        {
            $this->totalDays = 0;
            $this->totalCost = 0;
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate'])) {
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
        BookingModel::create([
            // 'user_id' => null,
            'guest_name' => $this->guestName,
            'guest_email' => $this->guestEmail,
            'guest_phone_number' => $this->guestPhone,
            'car_id' => $this->carId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'total_days' => $this->totalDays,
            'total_cost' => $this->totalCost,
            'requirements_valid_id_photo' => $validIdPath,
            'gcash_reference_number' => $this->gcashReferenceNumber,
            'gcash_receipt' => $receiptPath,
            'status' => 'pending',
        ]);

         // Store user email in session for future bookings
        session(['current_booking_email' => $this->guestEmail]);
        session(['last_booking_email' => $this->guestEmail]); // Backup session key
        

        $this->showModal = false;
        session()->flash('message','Booking submitted successfully! Please wait for admin confirmation.');
        $this->dispatch('bookingCreated');

    }
    public function render()
    {
        return view('livewire.booking')->layout('layouts.app');
    }
}
