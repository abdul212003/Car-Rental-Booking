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
    
    // public function updateStatus($id, $status)
    // {
    //     $booking = BookingModel::find($id);
    //     if ($booking)
    //     {
    //         $booking->status = $status;
    //         $booking->save();

    //          session()->flash('message', 'Booking status updated successfully!');
    //     }
    // }

    public function updateStatus($id, $status)
    {
        $booking = BookingModel::find($id);
        if ($booking) {
            $oldStatus = $booking->status;
            $booking->status = $status;
            
            // Set timestamps based on status
            if ($status === 'rented_out') {
                $booking->rented_out_at = now();
            } elseif ($status === 'returned') {
                $booking->returned_at = now();
            }
            
            $booking->save();

            session()->flash('message', 'Booking status updated successfully!');
        }
    }

    public function render()
    {
        $bookings = BookingModel::latest()->paginate(10);
        return view('livewire.admin.manage-booking',compact('bookings'))->layout('layouts.admin');
    }
}
