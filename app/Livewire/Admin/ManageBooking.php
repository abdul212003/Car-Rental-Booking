<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BookingModel;
use Livewire\WithPagination;

class ManageBooking extends Component
{
    use WithPagination;

    public $status;
    public $id;
    
    public function updateStatus($id, $status)
    {
        $booking = BookingModel::find($id);
        if ($booking)
        {
            $booking->status = $status;
            $booking->save();

        }
    }
    public function render()
    {
        $bookings = BookingModel::latest()->paginate(10);
        return view('livewire.admin.manage-booking',compact('bookings'))->layout('layouts.app');
    }
}
