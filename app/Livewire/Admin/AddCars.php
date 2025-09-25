<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use App\Models\CarsModel;

class AddCars extends Component
{
    use WithPagination, WithFileUploads;

    public $carId, $brand,$transmission,$setting_capacity,$fuel, $year, $price_per_day, $status = 'available', $image, $existingImage;
    public $showModal = false, $modalTitle = 'Add New Car', $editMode = false;

    protected $paginationTheme = 'bootstrap';

    public function rules()
    {
        return [
            'brand'  => 'required|string|max:255',
            'transmission'  => 'required|string|max:255',
            'setting_capacity'  => 'required|string|max:255',
            'fuel'  => 'required|string|max:255',
            'year'   => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'price_per_day' => 'required|numeric|min:0',
            'status' => 'required|in:available,unavailable',
            'image'  => 'nullable|image|max:2048',
        ];
    }


    public function create()
    {
        $this->resetForm();
        $this->modalTitle = 'Add New Car';
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $car = CarsModel::findOrFail($id);
        $this->carId = $car->id;
        $this->brand = $car->brand;
        $this->transmission = $car->transmission;
        $this->setting_capacity = $car->setting_capacity;
        $this->fuel = $car->fuel;
        $this->year = $car->year;
        $this->price_per_day = $car->price_per_day;
        $this->status = $car->status;
        $this->existingImage = $car->image;
        $this->modalTitle = 'Edit Car';
        $this->editMode = true;
        $this->showModal = true;
    }

     public function save()
    {
        $this->validate();
        $data = $this->getCarData();
        
        if ($this->editMode) {
            CarsModel::findOrFail($this->carId)->update($data);
            $message = 'Car updated successfully!';
        } else {
            CarsModel::create($data);
            $message = 'Car added successfully!';
        }

        $this->showModal = false;
        session()->flash('success', $message);
    }

    public function delete($id)
    {
        $car = CarsModel::findOrFail($id);
        if ($car->image) Storage::disk('public')->delete($car->image);
        $car->delete();
        session()->flash('success', 'Car deleted successfully!');
    }

     public function toggleStatus($id)
    {
        $car = CarsModel::findOrFail($id);
        $car->update(['status' => $car->status == 'available' ? 'unavailable' : 'available']);
        session()->flash('success', 'Car status updated!');
    }

    private function getCarData()
    {
        $data = [
            'brand' => $this->brand,
            'transmission' => $this->transmission,
            'setting_capacity' => $this->setting_capacity,
            'fuel' => $this->fuel,
            'year' => $this->year,
            'price_per_day' => $this->price_per_day,
            'status' => $this->status,
        ];

        if ($this->image) {
            if ($this->editMode && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $data['image'] = $this->image->store('car-images', 'public');
        }

        return $data;
    }

    private function resetForm()
    {
        $this->reset();
        $this->resetErrorBag();
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $cars = CarsModel::latest()->paginate(10);
        return view('livewire.admin.add-cars',compact('cars'))->layout('layouts.admin');
    }
}
