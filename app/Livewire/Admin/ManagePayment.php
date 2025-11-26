<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\PaymentModel;
use App\Services\SkyioService;
use Illuminate\Support\Facades\Auth;

class ManagePayment extends Component
{
    public $search;
    public $filterStatus;

    public function updateStatus($id, $status)
    {
        $payment = PaymentModel::findOrFail($id);
        $payment->status = $status;
        $payment->save();

        // Send SMS notification for status updates
        // $this->sendStatusUpdateSMS($status);

        session()->flash('success', "Payment #{$payment->id} marked as {$status}.");
    }

    // protected function sendStatusUpdateSMS($status)
    // {
    //     $skyioService = new SkyioService();

    //     $messages = [
    //         'pending' =>
    //             "Hello,\n\n" .
    //             "Your payment for booking has been successfully confirmed.\n" .
    //             "Thank you for choosing RJ Car Rental and Services!"

    //     ];

    //     if (isset($messages[$status])) {
    //         $response = $skyioService->sendSMS( $messages[$status]);

    //         // Log SMS response for debugging
    //         if (isset($response['error'])) {
    //             session()->flash('error', 'Status updated but SMS failed: ' . $response['error']);
    //         } else {
    //             session()->flash('message', 'Status updated and SMS sent successfully!');
    //         }
    //     }
    // }

    public function deletePayment($id)
    {
        PaymentModel::findOrFail($id)->delete();

        session()->flash('success','Delete Payment successfully.');
    }
    public function render()
    {
        if(Auth::user()->role == 'admin')
        {
            // Build the query with search and filters
            $paymentQuery = PaymentModel::query();

            // Apply search filter
            if (!empty($this->search)) {
                $paymentQuery->where(function($query) {
                    $query->where('car_id', 'like', '%' . $this->search . '%')
                        ->orWhere('gcash_reference_number', 'like', '%' . $this->search . '%');
                });
            }

            if(!empty($this->filterStatus)) {
                $paymentQuery->where('status', $this->filterStatus);
            }

            $payment = $paymentQuery->latest()->paginate(10);

            return view('livewire.admin.manage-payment',compact('payment'))
            ->layout('layouts.admin');
        }
        else
        {
            abort(403);
        }
    }
}
