<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\CarsModel;
use App\Models\BookingModel;

class Dashboard extends Component
{
    public function render()
    {
        $carsCount = CarsModel::count();
        // $booksCount = BookingModel::count();

        $rentedOutCount = BookingModel::where('status', 'rented_out')->count();
        // $confirmedCount = BookingModel::where('status', 'confirmed')->count();
        $pendingCount = BookingModel::where('status', 'pending')->count();
        $returnedCount = BookingModel::where('status', 'returned')->count();

         // For recent reservations table (latest 5 bookings)
        $recentBookings = BookingModel::with('car')
        ->latest()
        ->take(5)
        ->get();

        return view('livewire.admin.dashboard',compact('carsCount','rentedOutCount','pendingCount','returnedCount','recentBookings'))->layout('layouts.admin');
    }
}
