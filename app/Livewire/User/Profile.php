<?php

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Profile extends Component
{
    public $name;
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $this->name = Auth::user()->name;
    }

    public function updateProfileInformation()
    {
        $user = Auth::user();

        $validatedData = $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user->forceFill([
            'name' => $validatedData['name'],
        ])->save();

        // Use session flash for success message instead of emit
        session()->flash('profile_status', 'Profile information updated successfully!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required', 'string', 'current_password:web'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->forceFill([
            'password' => Hash::make($this->password),
        ])->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        // Use session flash for success message instead of emit
        session()->flash('password_status', 'Password updated successfully!');
    }

    public function render()
    {
        return view('livewire.user.profile')->layout('layouts.admin');
    }
}