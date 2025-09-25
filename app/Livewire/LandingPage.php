<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CustomerFeedBackModel;
use App\Models\ContactUsModel;

class LandingPage extends Component
{
    public $name;
    public $email;
    public $rating;
    public $message;

    public $contact_name,$contact_email,$contact_subject,$contact_message;

    public function submitContact()
    {
        $this->validate([
            'contact_name' => 'required|max:255',
            'contact_email' => 'required|email',
            'contact_subject' => 'required|max:255',
            'contact_message' => 'required|min:5',
        ]);

        ContactUsModel::create([
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_subject' => $this->contact_subject,
            'contact_message' => $this->contact_message,
        ]);

        $this->reset(['contact_name','contact_email','contact_subject','contact_message']);

        session()->flash('contact_success','Thank you for your contact! It will be reviewed and posted soon.');
    }

    public function submitFeedback()
    {
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email',
            'rating' => 'required',
            'message' => 'required|min:5',
        ]);

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
