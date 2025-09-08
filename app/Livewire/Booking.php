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
        'gcashReceipt' => 'required|image|max:2048', // 2MB Max
        'guestName' => 'required|string|max:255',
        'guestEmail' => 'required|email',
        'guestPhone' => 'required|string|max:20',
        'agreeTerms' => 'accepted',
    ];

    protected $messages = [
        'agreeTerms.accepted' => 'You must accept the terms and conditions to proceed.'
    ];

    public function openModal($carId)
    {
        $this->resetValidation();
        $this->reset(['startDate', 'endDate', 'totalDays', 'totalCost', 'gcashReferenceNumber', 'gcashReceipt','guestName','guestEmail','guestPhone','agreeTerms']);
        $this->carId = $carId;
        $this->car = CarsModel::findOrFail($carId);
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

        // Check availability again before booking
        if (!$this->car->isAvailable($this->startDate, $this->endDate)) {
            session()->flash('error', 'Sorry this car is no longer available for the  selected dates.');

            return;
        }

        // Store the receipt image
        $receiptPath = $this->gcashReceipt->store('gcash-receipts', 'public');

        //Create the booking
        BookingModel::create([
            'user_id' => null,
            'guest_name' => $this->guestName,
            'guest_email' => $this->guestEmail,
            'guest_phone_number' => $this->guestPhone,
            'car_id' => $this->carId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'total_days' => $this->totalDays,
            'total_cost' => $this->totalCost,
            'gcash_reference_number' => $this->gcashReferenceNumber,
            'gcash_receipt' => $receiptPath,
            'status' => 'pending',
        ]);

        $this->showModal = false;
        session()->flash('message','Booking submitted successfully! Please wait for admin confirmation.');
        $this->dispatch('bookingCreated');

    }
    public function render()
    {
        return view('livewire.booking')->layout('layouts.app');
    }
}
