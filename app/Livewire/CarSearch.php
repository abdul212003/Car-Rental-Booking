<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CarsModel;
use App\Models\BookingModel;
use Livewire\WithPagination;

class CarSearch extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $searchBrand = '';
    public $searchPrice = '';
    public $searchStartDate = '';
    public $searchEndDate = '';

    protected $listeners = ['bookingCreated' => 'refreshComponent'];

    /**
     * Refresh component after booking is created
     */
    public function refreshComponent()
    {
        // This will trigger a re-render and update button states
        $this->render();
    }


     /**
     * Check if the current user has already booked this car for the selected dates
     */
    public function hasUserBookedCar($carId)
    {

        // Get current user - adjust this based on your authentication system
        $userIdentifier = $this->getCurrentUserIdentifier();

        // If no user identifier is available, we can't check
        if(!$userIdentifier)
        {
            return false;
        }

         // If no search dates are provided, check for any active bookings by this user
        if (empty($this->searchStartDate) || empty($this->searchEndDate)) {
            return BookingModel::where('car_id', $carId)
                ->where($this->getUserIdentifierColumn(), $userIdentifier)
                ->whereIn('status', ['confirmed', 'pending'])
                ->exists();
        }

        // Check if user has any bookings for this car that overlap with selected dates
        $existingBooking = BookingModel::where('car_id', $carId)
            ->where($this->getUserIdentifierColumn() ,$userIdentifier)
            ->where(function ($query) 
            {
                $query->where(function ($q)
                {
                    // New booking starts during existing booking
                    $q->where('start_date', '<=', $this->searchStartDate)
                        ->where('end_date', '>=', $this->searchStartDate);
                })->orWhere(function ($q)
                {
                    // New booking ends during existing booking
                    $q->where('start_date', '<=',  $this->searchEndDate)
                        ->where('end_date', '>=',  $this->searchEndDate);
                })->orWhere(function ($q)
                {
                    // New booking ends during existing booking
                    $q->where('start_date', '>=', $this->searchStartDate)
                        ->where('end_date', '<=', $this->searchEndDate);
                });
            }) ->whereIn('status', ['confirmed', 'pending']) // Only check active bookings
            ->exists();
        return $existingBooking;
    }

    /**
     * Get current user identifier for guest users (no login required)
     */
    private function getCurrentUserIdentifier()
    {
        // For guest booking system, try to get email from session
        // This assumes you store the user's email when they start booking
        if (session()->has('current_booking_email')) {
            return session('current_booking_email');
        }

        // Alternative: get from cookie if you store it there
        if (request()->hasCookie('booking_user_email')) {
            return request()->cookie('booking_user_email');
        }

        // Check if there's a recently completed booking in this session
        if (session()->has('last_booking_email')) {
            return session('last_booking_email');
        }

        return null;
    }

    /**
     * Get the column name for user identification in bookings table
     */
    private function getUserIdentifierColumn()
    {
        // Since no login required, we use guest_email
        return 'guest_email';
    }

        /**
     * Get button state and styling for a car
     */
    public function getCarButtonState($car)
    {
        $isAvailable = true;
        $hasUserBooked = false;
        $buttonText = 'Book Now';
        $buttonClass = 'btn-primary';
        $buttonIcon = 'fas fa-calendar-check';
        $isDisabled = false;

        // First check if the car is marked as unavailable by admin
        if ($car->status === 'unavailable') {
            $buttonText = 'Unavailable';
            $buttonClass = 'btn-secondary';
            $buttonIcon = 'fas fa-calendar-times';
            $isDisabled = true;
            
            return [
                'text' => $buttonText,
                'class' => $buttonClass,
                'icon' => $buttonIcon,
                'disabled' => $isDisabled,
                'available' => false,
                'user_booked' => false
            ];
        }

        // Check if user has already booked this car (regardless of dates initially)
        $hasUserBooked = $this->hasUserBookedCar($car->id);

        // Check availability for selected dates if provided
        if (!empty($this->searchStartDate) && !empty($this->searchEndDate)) {
            $isAvailable = $car->isAvailable($this->searchStartDate, $this->searchEndDate);
        }

        // Determine button state based on conditions
        if ($hasUserBooked) {
            $buttonText = 'Already Booked';
            $buttonClass = 'btn-warning';
            $buttonIcon = 'fas fa-check-circle';
            $isDisabled = true;
        } elseif (!$isAvailable && !empty($this->searchStartDate) && !empty($this->searchEndDate)) {
            $buttonText = 'Unavailable for Selected Dates';
            $buttonClass = 'btn-secondary';
            $buttonIcon = 'fas fa-calendar-times';
            $isDisabled = true;
        }

        return [
            'text' => $buttonText,
            'class' => $buttonClass,
            'icon' => $buttonIcon,
            'disabled' => $isDisabled,
            'available' => $isAvailable,
            'user_booked' => $hasUserBooked
        ];
    }
    
    public function render()
    {
        $query = CarsModel::query();

        // Apply brand filter
        if (!empty($this->searchBrand)) {
            $query->where(function ($q) {
                $q->where('brand', 'like', '%' . $this->searchBrand . '%');
            });
        }

        // Apply price filter
        if (!empty($this->searchPrice)) {
            $query->where('price_per_day', '<=', $this->searchPrice);
        }
         else 
        {
            // Always show available cars by default
            $query->whereIn('status' , ['available','unavailable']);
        }

        // // Apply availability filter based on dates
        // if (!empty($this->searchStartDate) && !empty($this->searchEndDate)) {
        //      $query->availableBetween($this->searchStartDate, $this->searchEndDate);
        // }
       

        $cars = $query->paginate(8);

        return view('livewire.car-search',['cars' => $cars,'searchStartDate' => $this->searchStartDate,'searchEndDate' => $this->searchEndDate])->layout('layouts.search-car');
    }

    public function resetFilters()
    {
        $this->reset(['searchBrand','searchPrice','searchStartDate','searchEndDate']);
        $this->resetPage();
    }

    // public function updatingSearchStartDate()
    // {
    //     $this->resetPage();
    // }

    // public function updatingSearchEndDate()
    // {
    //     $this->resetPage();
    // }
}
