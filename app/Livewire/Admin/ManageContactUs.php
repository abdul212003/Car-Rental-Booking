<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\ContactUsModel;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ManageContactUs extends Component
{
    use WithPagination;

    public $id;
    public $contact_name,$contact_email,$contact_subject,$contact_message;

    public function editContact($id)
    {
        $editContact = ContactUsModel::findOrFail($id);

        $this->id = $editContact->id;
        $this->contact_name = $editContact->contact_name;
        $this->contact_email = $editContact->contact_email;
        $this->contact_subject = $editContact->contact_subject;
        $this->contact_message = $editContact->contact_message;
    }

    public function updateContact()
    {
        $contact = ContactUsModel::findOrFail($this->id);

        $contact->update([
            'contact_name' => $this->contact_name,
            'contact_email' => $this->contact_email,
            'contact_subject' => $this->contact_subject,
            'contact_message' => $this->contact_message,
        ]);

        $this->reset(['contact_name','contact_email','contact_subject','contact_message']);

        session()->flash('success','Contact Update Successfully.');
    }

    public function deleteContact($id)
    {
        ContactUsModel::findOrFail($id)->delete();

        session()->flash('success','Contact Delete Successfully.');
    }

    public function render()
    {
        if(Auth::user()->role == 'admin')
        {   
             $contact = ContactUsModel::latest()->paginate(10);
             return view('livewire.admin.manage-contact-us',compact('contact'))->layout('layouts.admin');
        }
        else
        {
            abort(403);
        }
       
    }
}
