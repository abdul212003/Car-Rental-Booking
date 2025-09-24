<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CarsModel;
use Livewire\WithPagination;

class CarSearch extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $searchBrand = '';
    public $searchPrice = '';
    public $searchStartDate = '';
    public $searchEndDate = '';
    
    public function render()
    {
        $query = CarsModel::query();

        // Apply brand filter
        if (!empty($this->searchBrand)) {
            $query->where(function ($q) {
                $q->where('brand', 'like', '%' . $this->searchBrand . '%');
            });
        }

        // Apply price filter
        if (!empty($this->searchPrice)) {
            $query->where('price_per_day', '<=', $this->searchPrice);
        }

        // Apply availability filter based on dates
        if (!empty($this->searchStartDate) && !empty($this->searchEndDate)) {
             $query->availableBetween($this->searchStartDate, $this->searchEndDate);
        }
        else 
        {
            // Always show available cars by default
            $query->where('status' , 'available');
        }

        $cars = $query->paginate(8);

        return view('livewire.car-search',['cars' => $cars,'searchStartDate' => $this->searchStartDate,'searchEndDate' => $this->searchEndDate])->layout('layouts.app');
    }

    public function resetFilters()
    {
        $this->reset(['searchBrand','searchPrice','searchStartDate','searchEndDate']);
        $this->resetPage();
    }

    public function updatingSearchStartDate()
    {
        $this->resetPage();
    }

    public function updatingSearchEndDate()
    {
        $this->resetPage();
    }
}
