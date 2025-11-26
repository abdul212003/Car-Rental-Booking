<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\BookingModel;
use App\Models\CarsModel;
use Livewire\WithPagination;
use App\Services\SkyioService;
use Illuminate\Support\Facades\Auth;

class ManageBooking extends Component
{
    use WithPagination;

    public $status;
    public $id;

    public $car_brand, $car_plate_number;
    public $car_id, $guest_name, $guest_email, $guest_phone_number;
    public $operator, $destination, $start_date, $end_date;
    public $total_days, $total_cost, $requirements_valid_id_photo;
    public $rented_out_at, $returned_at, $payment_plan;
    public $downpayment_amount, $remaining_balance;


    // Search and Filter properties
    public $search = '';
    public $filterStatus = '';
    public $filterCarBrand = '';
    public $filterStartDate = '';
    public $filterEndDate = '';

    public function updateStatus($id, $status)
    {
        $booking = BookingModel::find($id);
        if ($booking) {
            $oldStatus = $booking->status;
            
            // Store booking info for SMS before any changes
            $customerPhone = $booking->guest_phone_number;
            $customerName = $booking->guest_name;
            $bookingId = $booking->id;
            
            // If status is 'cancelled', delete the booking
            if ($status === 'cancelled') {
                // Make car available again if it was marked as unavailable
                if ($booking->car_id) {
                    $car = CarsModel::find($booking->car_id);
                    if ($car && $car->status === 'unavailable') {
                        $car->update(['status' => 'available']);
                    }
                }
                
                // Send cancellation SMS BEFORE deletion
                $this->sendStatusUpdateSMS($status, $customerName, $customerPhone, $bookingId);
                
                // Delete the booking
                $booking->delete();
                
                session()->flash('failed', 'Booking cancelled and deleted successfully!');
                return;
            }

            // For other status updates, proceed as normal
            $booking->status = $status;
            
            // Set timestamps based on status
            if ($status === 'rented_out') {
                $booking->rented_out_at = now();
                
                // ✅ Automatically mark the car as unavailable
                if ($booking->car_id) {
                    $car = CarsModel::find($booking->car_id);
                    if ($car) {
                        $car->update(['status' => 'unavailable']);
                    }
                }
            } elseif ($status === 'returned') {
                $booking->returned_at = now();
                
                // ✅ Automatically mark the car as available again
                if ($booking->car_id) {
                    $car = CarsModel::find($booking->car_id);
                    if ($car) {
                        $car->update(['status' => 'available']);
                    }
                }
            } elseif ($status === 'confirmed') {
                // ✅ If you want to mark as unavailable when confirmed (not yet rented out)
                if ($booking->car_id) {
                    $car = CarsModel::find($booking->car_id);
                    if ($car) {
                        $car->update(['status' => 'unavailable']);
                    }
                }
            } elseif ($status === 'pending') {
                // ✅ If booking is back to pending, make car available again
                if ($booking->car_id) {
                    $car = CarsModel::find($booking->car_id);
                    if ($car && $car->status === 'unavailable') {
                        $car->update(['status' => 'available']);
                    }
                }
            }
            
            $booking->save();

            // Send SMS notification for status updates (except cancelled which is handled above)
            $this->sendStatusUpdateSMS($status, $customerName, $customerPhone, $bookingId, $booking->car_id,$booking->total_cost,$booking->remaining_balance);

            session()->flash('message', 'Booking status updated successfully!');
        }
    }

     public function editBooking($id)
    {
        $booking = BookingModel::with('car')->findOrFail($id);

        // Set all properties
        $this->id = $booking->id;
        $this->car_id = $booking->car_id;
        $this->car_brand = $booking->car->brand ?? 'N/A';
        $this->car_plate_number = $booking->car->plate_number ?? 'N/A';
        $this->guest_name = $booking->guest_name;
        $this->guest_email = $booking->guest_email;
        $this->guest_phone_number = $booking->guest_phone_number;
        $this->operator = $booking->operator;
        $this->destination = $booking->destination;
        $this->start_date = $booking->start_date instanceof Carbon 
            ? $booking->start_date->format('Y-m-d') 
            : $booking->start_date;
        $this->end_date = $booking->end_date instanceof Carbon 
            ? $booking->end_date->format('Y-m-d') 
            : $booking->end_date;
        $this->total_days = $booking->total_days;
        $this->total_cost = $booking->total_cost;
        $this->requirements_valid_id_photo = $booking->requirements_valid_id_photo;
        $this->status = $booking->status;
        $this->rented_out_at = $booking->rented_out_at;
        $this->returned_at = $booking->returned_at;
        $this->payment_plan = $booking->payment_plan;
        $this->downpayment_amount = $booking->downpayment_amount ?? 0;
        $this->remaining_balance = $booking->remaining_balance ?? 0;

        // Show modal
        $this->dispatch('openEditModal');
    }
    
    
    public function updatedPaymentPlan()
    {
        // Automatically calculate remaining balance when payment plan changes
        if ($this->payment_plan === 'full_payment') {
            $this->downpayment_amount = $this->total_cost;
            $this->remaining_balance = 0;
        } elseif ($this->payment_plan === 'downpayment') {
            $this->remaining_balance = $this->total_cost - ($this->downpayment_amount ?? 0);
        }
    }

    public function updatedDownpaymentAmount()
    {
        // Recalculate remaining balance when downpayment changes
        if ($this->payment_plan === 'downpayment') {
            $this->remaining_balance = $this->total_cost - ($this->downpayment_amount ?? 0);
        }
    }

    public function updateBooking()
    {
        $this->validate([
            'payment_plan' => 'required|in:downpayment,full_payment',
            'downpayment_amount' => 'required|numeric|min:0',
            'remaining_balance' => 'nullable|numeric|min:0',
        ]);

        $booking = BookingModel::findOrFail($this->id);

        // Calculate remaining balance based on payment plan
        if ($this->payment_plan === 'full_payment') {
            $this->downpayment_amount = $this->total_cost;
            $this->remaining_balance = 0;
        } else {
            $this->remaining_balance = $this->total_cost - $this->downpayment_amount;
        }

        // Update booking with payment information
        $booking->update([
            'payment_plan' => $this->payment_plan,
            'downpayment_amount' => $this->downpayment_amount,
            'remaining_balance' => $this->remaining_balance,
        ]);

        session()->flash('success', 'Booking updated successfully!');

        // Reset properties
        $this->reset([
            'id', 'car_id', 'car_brand', 'car_plate_number',
            'guest_name', 'guest_email', 'guest_phone_number',
            'operator', 'destination', 'start_date', 'end_date',
            'total_days', 'total_cost', 'requirements_valid_id_photo',
            'status', 'rented_out_at', 'returned_at',
            'payment_plan', 'downpayment_amount', 'remaining_balance'
        ]);

        // Close modal
        $this->dispatch('closeEditModal');
    }

    
    public function deleteBooking($id)
    {
       BookingModel::findOrFail($id)->delete();  
    }

    protected function sendStatusUpdateSMS($status, $customerName, $customerPhone, $bookingId, $carId , $total_cost, $remaining_balance)
    {
        $skyioService = new SkyioService();

        if (!$customerPhone) {
            session()->flash('warning', 'Status updated but no phone number found for SMS.');
            return;
        }

        // Format phone number
        $formattedPhone = $this->formatPhoneNumber($customerPhone);

        // Check if phone number is properly formatted
        if (!str_starts_with($formattedPhone, '+63') || strlen($formattedPhone) !== 13) {
            session()->flash('warning', "Status updated but phone number format invalid. Required format: 09XXXXXXXXX");
            return;
        }

        $messages = [
            'in_progress' =>
                "Hello {$customerName},\n\n" .
                "And this is your Car ID (#{$carId}).\n\n" .
                "Your booking (#{$bookingId}) is currently awaiting confirmation.\n" .
                "Please complete your payment via GCash using the details below:\n" .
                "GCash Account Name: (AN***E JA**S V.)\n" .
                "GCash Number: 09952184322\n\n" .
                "You may also visit the website and go to the payment section.\n\n" .
                "We will notify you once it has been reviewed.\n\n" .
                "Thank you for your patience!",

            'confirmed' => 
                "Hello {$customerName},\n\n" .
                "Good news! Your booking (#{$bookingId}) is confirmed.\n" .
                // "And this is your Car ID (#{$carId}).\n\n" .
                "Total cost: PHP " . number_format($total_cost, 2) . "\n\n" .
                "Remaining balance: PHP " . number_format($remaining_balance, 2) . "\n\n" .
                "You can pick up your car at Villarica, Midsayap, Cotabato.\n\n" .
                // "Please complete your payment via GCash using the details below:\n" .
                // "GCash Account Name: (AN***E JA**S V.)\n" .
                // "GCash Number: 09952184322\n\n" .
                // "Check the website and go to payment.\n\n" .
                "We look forward to serving you!",

            'cancelled' => 
                "Hello {$customerName},\n\n" .
                "We’re sorry, but your booking (#{$bookingId}) couldn’t be confirmed.\n" .
                "Please reach out to us if you’d like more information.",

            'returned' => 
                "Hello {$customerName},\n\n" .
                "Your booking (#{$bookingId}) is now complete. The vehicle has been returned.\n" .
                "Thank you for choosing RJ Car Rental and Services.",

            'rented_out' => 
                "Hello {$customerName},\n\n" .
                "Your booking (#{$bookingId}) is now in progress.\n" .
                "Have a safe and enjoyable trip!",
        ];


        if (isset($messages[$status])) {
            $response = $skyioService->sendSMS($formattedPhone, $messages[$status]);

            // Log SMS response for debugging
            if (isset($response['error'])) {
                session()->flash('error', 'Status updated but SMS failed: ' . $response['error']);
            } else {
                session()->flash('success', 'Status updated and SMS sent successfully!');
            }
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

     // Reset filters
    public function resetFilters()
    {
        $this->search = '';
        $this->filterStatus = '';
        $this->filterCarBrand = '';
        $this->filterStartDate = '';
        $this->filterEndDate = '';
        $this->resetPage();
    }

    public function render()
    {
        if (Auth::user()->role == 'admin') {

            // Build the query with search and filters
            $bookingsQuery = BookingModel::with('car');
            
            // Apply search filter
            if (!empty($this->search)) {
                $bookingsQuery->where(function($query) {
                    $query->where('guest_name', 'like', '%' . $this->search . '%')
                          ->orWhere('guest_email', 'like', '%' . $this->search . '%')
                          ->orWhere('guest_phone_number', 'like', '%' . $this->search . '%')
                          ->orWhereHas('car', function($carQuery) {
                              $carQuery->where('brand', 'like', '%' . $this->search . '%')
                                      ->orWhere('plate_number', 'like', '%' . $this->search . '%');
                          });
                });
            }
            
            // Apply status filter
            if (!empty($this->filterStatus)) {
                $bookingsQuery->where('status', $this->filterStatus);
            }
            
            // Apply car brand filter
            if (!empty($this->filterCarBrand)) {
                $bookingsQuery->whereHas('car', function($query) {
                    $query->where('brand', $this->filterCarBrand);
                });
            }
            
            // Apply start date filter
            if (!empty($this->filterStartDate)) {
                $bookingsQuery->whereDate('start_date', '>=', $this->filterStartDate);
            }
            
            // Apply end date filter
            if (!empty($this->filterEndDate)) {
                $bookingsQuery->whereDate('end_date', '<=', $this->filterEndDate);
            }
            
            // Get unique car brands for filter dropdown
            $carBrands = CarsModel::distinct()->pluck('brand')->filter()->values();
            
            $bookings = $bookingsQuery->latest()->paginate(10);

            return view('livewire.admin.manage-booking', compact('bookings','carBrands'))->layout('layouts.admin');
        } else {
            abort(403);
        }
    }
}