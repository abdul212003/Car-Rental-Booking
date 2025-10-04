<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\CustomerFeedBackModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class ManageCustomerFeedback extends Component
{
    use WithPagination;

    public $id;
    public $name,$email,$rating,$message;

    public function editFeedback($id)
    {
        $editFeedback = CustomerFeedBackModel::find($id);

        $this->id  =$editFeedback->id;
        $this->name = $editFeedback->name;
        $this->email = $editFeedback->email;
        $this->rating = $editFeedback->rating;
        $this->message = $editFeedback->message;
    }

    public function updateFeedback()
    {
        $feedback = CustomerFeedBackModel::findOrFail($this->id);

        $feedback->update([
            'name' => $this->name,
            'email' => $this->email,
            'rating' => $this->rating,
            'message' => $this->message,
        ]);

        $this->reset(['name','email','rating','message']);

        session()->flash('success','Feedback Update Successfully.');
    }

    public function deleteFeedback($id)
    {
        CustomerFeedBackModel::findOrFail($id)->delete();

        session()->flash('success','Feedback Delete Successfully.');
    }

    public function render()
    {
        if(Auth::user()->role == 'admin')
        {
             $feedback = CustomerFeedBackModel::latest()->paginate(10);

             return view('livewire.admin.manage-customer-feedback',compact('feedback'))->layout('layouts.admin');
        }
        else
        {
            abort(403);
        }
       
    }
}
