<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BookingModel;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function printBooking($id)
    {
        $booking = BookingModel::with('car')->findOrFail($id);
        
        return view('print.booking-print', compact('booking'));
    }
}
