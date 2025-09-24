<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerFeedBackModel;

class LandingPage extends Component
{
    public $name;
    public $email;
    public $rating;
    public $message;

    protected $rules = [
        'name' => 'required|max:255',
        'email' => 'required|email',
        'rating' => 'required',
        'message' => 'required|min:5',
    ];

    // protected $messages = [
    //     'name.required' => 'Please enter your name.',
    //     'email.required' => 'Please enter your email address.',
    //     'email.email' => 'Please enter a valid email address.',
    //     'rating.required' => 'Please select a rating.',
    //     'message.required' => 'Please enter your feedback message.',
    //     'message.min' => 'Feedback must be at least 10 characters.',
    // ];

    public function submitFeedback()
    {
        $this->validate();

        CustomerFeedBackModel::create([
            'name' => $this->name,
            'email' => $this->email,
            'rating' => $this->rating,
            'message' => $this->message,
        ]);

        // Reset form fields
        $this->reset(['name', 'email', 'rating', 'message']);

        // Show success message
        session()->flash('feedback_success', 'Thank you for your feedback! It will be reviewed and posted soon.');
    }

    public function render()
    {
        $Feedbacks = CustomerFeedBackModel::latest()->limit(3)->get();

        return view('livewire.landing-page',['Feedbacks' => $Feedbacks])->layout('layouts.welcome');
    }
}
