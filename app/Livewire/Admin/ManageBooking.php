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
            $this->sendStatusUpdateSMS($status, $customerName, $customerPhone, $bookingId);

            session()->flash('message', 'Booking status updated successfully!');
        }
    }

    protected function sendStatusUpdateSMS($status, $customerName, $customerPhone, $bookingId)
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
            'confirmed' => 
                "Hello {$customerName},\n\n" .
                "Good news! Your booking (#{$bookingId}) is confirmed.\n" .
                "You can pick up your car at Villarica, Midsayap, Cotabato.\n\n" .
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

    // Add this method to your ManageBooking component for testing
    // public function testSMSFunctionality()
    // {
    //     // Create a test booking or use an existing one
    //     $testBooking = BookingModel::first();
        
    //     if (!$testBooking) {
    //         session()->flash('error', 'No bookings found for testing.');
    //         return;
    //     }

    //     $skyioService = new SkyioService();
        
    //     // Test phone number - replace with your actual phone number
    //     $testPhone = '09856056771'; // Replace with your real Philippine number
        
    //     // Test message
    //     $testMessage = "TEST SMS: Hello {$testBooking->guest_name}, this is a test message from your car rental system. Booking #{$testBooking->id}";
        
    //     // Format phone number
    //     $formattedPhone = $this->formatPhoneNumber($testPhone);
        
    //     // Check phone format
    //     if (!str_starts_with($formattedPhone, '+63') || strlen($formattedPhone) !== 13) {
    //         session()->flash('error', "Test failed: Phone number format invalid. Got: {$formattedPhone}");
    //         return;
    //     }
        
    //     session()->flash('info', "Attempting to send test SMS to: {$formattedPhone}");
        
    //     try {
    //         $response = $skyioService->sendSMS($formattedPhone, $testMessage);
            
    //         if (isset($response['success']) && $response['success']) {
    //             session()->flash('success', "✅ Test SMS sent successfully! Message ID: " . ($response['message_id'] ?? 'N/A'));
    //         } elseif (isset($response['error'])) {
    //             session()->flash('error', "❌ SMS failed: " . $response['error']);
    //         } else {
    //             session()->flash('warning', "⚠️ Unknown response: " . json_encode($response));
    //         }
    //     } catch (\Exception $e) {
    //         session()->flash('error', "❌ Exception: " . $e->getMessage());
    //     }
    // }

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
                          ->orWhere('gcash_reference_number', 'like', '%' . $this->search . '%')
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