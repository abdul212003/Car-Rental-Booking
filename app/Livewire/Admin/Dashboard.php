<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\CarsModel;

class Dashboard extends Component
{
    public function render()
    {
        $carsCount = CarsModel::count();
        return view('livewire.admin.dashboard',compact('carsCount'))->layout('layouts.admin');
    }
}
