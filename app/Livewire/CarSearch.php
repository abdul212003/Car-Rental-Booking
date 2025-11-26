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
        $this->render();
    }

    /**
     * Check if ANY user has booked this car for the selected dates
     * (For guest users without login)
     */
    public function isCarBookedByAnyone($carId)
    {
        // If no search dates provided, check for any active bookings
        if (empty($this->searchStartDate) || empty($this->searchEndDate)) {
            return BookingModel::where('car_id', $carId)
                ->whereIn('status', ['confirmed', 'pending','in_progress'])
                ->exists();
        }

        // Check if there are any bookings that overlap with selected dates
        $hasBooking = BookingModel::where('car_id', $carId)
            ->where(function ($query) {
                $query->where(function ($q) {
                    // Booking starts during selected period
                    $q->where('start_date', '<=', $this->searchStartDate)
                      ->where('end_date', '>=', $this->searchStartDate);
                })->orWhere(function ($q) {
                    // Booking ends during selected period
                    $q->where('start_date', '<=', $this->searchEndDate)
                      ->where('end_date', '>=', $this->searchEndDate);
                })->orWhere(function ($q) {
                    // Booking is completely within selected period
                    $q->where('start_date', '>=', $this->searchStartDate)
                      ->where('end_date', '<=', $this->searchEndDate);
                });
            })
            ->whereIn('status', ['confirmed', 'pending','in_progress'])
            ->exists();

        return $hasBooking;
    }

    /**
     * Get button state and styling for a car
     */
    public function getCarButtonState($car)
    {
        $isAvailable = true;
        $isBooked = false;
        $buttonText = 'Book Now';
        $buttonClass = 'btn-primary';
        $buttonIcon = 'fas fa-calendar-check';
        $isDisabled = false;

        // First check if the car is marked as unavailable by admin
        if ($car->status === 'unavailable') {
            $buttonText = 'Unavailable';
            $buttonClass = 'btn-secondary';
            $buttonIcon = 'fas fa-ban';
            $isDisabled = true;
            
            return [
                'text' => $buttonText,
                'class' => $buttonClass,
                'icon' => $buttonIcon,
                'disabled' => $isDisabled,
                'available' => false,
                'is_booked' => false
            ];
        }

        // Check if car is already booked by anyone
        $isBooked = $this->isCarBookedByAnyone($car->id);

        // Check availability for selected dates if provided
        if (!empty($this->searchStartDate) && !empty($this->searchEndDate)) {
            $isAvailable = $car->isAvailable($this->searchStartDate, $this->searchEndDate);
        }

        // Determine button state
        if ($isBooked) {
            $buttonText = 'Already Booked';
            $buttonClass = 'btn-warning';
            $buttonIcon = 'fas fa-calendar-times';
            $isDisabled = true;
        } elseif (!$isAvailable && !empty($this->searchStartDate) && !empty($this->searchEndDate)) {
            $buttonText = 'Unavailable';
            $buttonClass = 'btn-secondary';
            $buttonIcon = 'fas fa-times-circle';
            $isDisabled = true;
        }

        return [
            'text' => $buttonText,
            'class' => $buttonClass,
            'icon' => $buttonIcon,
            'disabled' => $isDisabled,
            'available' => $isAvailable,
            'is_booked' => $isBooked
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

        $cars = $query->paginate(8);

        return view('livewire.car-search', [
            'cars' => $cars,
            'searchStartDate' => $this->searchStartDate,
            'searchEndDate' => $this->searchEndDate
        ])->layout('layouts.search-car');
    }

    public function resetFilters()
    {
        $this->reset(['searchBrand']);
        $this->resetPage();
    }
}