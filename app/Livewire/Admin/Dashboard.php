<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\CarsModel;
use App\Models\BookingModel;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Component
{
    public function render()
    {
        if(Auth::user()->role == 'admin')
        {
            $carsCount = CarsModel::count();

            $rentedOutCount = BookingModel::where('status', 'rented_out')->count();
            $pendingCount = BookingModel::where('status', 'pending')->count();
            $returnedCount = BookingModel::where('status', 'returned')->count();
            $paymentCount = PaymentModel::where('status', 'pending')->count();

            // For recent reservations table (latest 5 bookings)
            $recentBookings = BookingModel::with('car')
            ->latest()
            ->take(5)
            ->get();

            return view('livewire.admin.dashboard',compact('carsCount','rentedOutCount','pendingCount','returnedCount','recentBookings','paymentCount'))->layout('layouts.admin');
        }
        else
        {
            abort(403);
        }
       
    }
}
