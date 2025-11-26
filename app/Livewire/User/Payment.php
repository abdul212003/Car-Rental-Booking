<?php

namespace App\Livewire\User;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PaymentModel;

class Payment extends Component
{
    use WithFileUploads;

    public $gcash_reference_number = '';
    public $gcash_receipt = null;
    public $car_id = '';

    public function rules()
    {
        return [
            'gcash_reference_number' => 'required|string|max:13',
            'gcash_receipt' => 'required|image|mimes:jpg,jpeg,png|max:5120',
            'car_id' => 'required|integer|exists:cars,id', // Make sure table name is correct
        ];
    }

    public function submitPayment()
    {
        $this->validate();

        $receiptPath = $this->gcash_receipt->store('gcash_receipt', 'public');

        PaymentModel::create([
            'gcash_reference_number' => $this->gcash_reference_number,
            'gcash_receipt' => $receiptPath,
            'car_id' => $this->car_id,
        ]);

        $this->reset(['gcash_reference_number', 'gcash_receipt', 'car_id']);

        session()->flash('message', 'Payment submitted successfully!'); // FIXED
    }

    public function render()
    {
        return view('livewire.user.payment')->layout('layouts.welcome');
    }
}
