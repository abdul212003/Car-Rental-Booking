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

    public $carId, $brand,$transmission,$setting_capacity,$fuel, $year, $price_per_day, $status = 'available', $image,$interior_image,$additional_image, $existingImage,$existingInteriorImage,$existingAdditionalImage;
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
            'interior_image' => 'nullable|image|max:2048',
            'additional_image' => 'nullable|image|max:2048',
        ];
    }

    // public function createCars()
    // {
    //     $this->validate();

    //     $pathImage = $this->image->store('car-images', 'public');
    //     $pathInteriorImage = $this->interior_image->store('car-images/interior', 'public');
    //     $pathAdditionalImage = $this->additional_image->store('car-images/additional', 'public');

    //     CarsModel::updateOrCreate(
    //         ['id' => $this->carId], 
    //         [
    //             'brand' => $this->brand,
    //             'model' => $this->model,
    //             'transmission' => $this->transmission,
    //             'setting_capacity' => $this->setting_capacity,
    //             'fuel' => $this->fuel,
    //             'color' => $this->color,
    //             'year' => $this->year,
    //             'price_per_day' => $this->price_per_day,
    //             'status' => $this->status,
    //             'image' => $pathImage,
    //             'interior_image' => $pathInteriorImage,
    //             'additional_image' => $pathAdditionalImage,
    //         ]
    //     );

    //     $this->reset(['brand','transmission','setting_capacity','fuel','year','price_per_day','status','image','interior_image','additional_image']);
    // }

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
        $this->model = $car->model;
        $this->transmission = $car->transmission;
        $this->setting_capacity = $car->setting_capacity;
        $this->fuel = $car->fuel;
        $this->color = $car->color;
        $this->year = $car->year;
        $this->price_per_day = $car->price_per_day;
        $this->status = $car->status;
        
        // Set existing images
        $this->existingImage = $car->image;
        $this->existingInteriorImage = $car->interior_image;
        $this->existingAdditionalImage = $car->additional_image;
        
        $this->modalTitle = 'Edit Car';
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();
        $data = $this->getCarData();
        
        if ($this->editMode) {
            $car = CarsModel::findOrFail($this->carId);
            $car->update($data);
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

        // Delete all associated images
        if ($car->image) Storage::disk('public')->delete($car->image);
        if ($car->interior_image) Storage::disk('public')->delete($car->interior_image);
        if ($car->additional_image) Storage::disk('public')->delete($car->additional_image);

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

           // Handle main image upload
        if ($this->image) {
            if ($this->editMode && $this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $data['image'] = $this->image->store('car-images', 'public');
        }

        // Handle interior image upload
        if ($this->interior_image) {
            if ($this->editMode && $this->existingInteriorImage) {
                Storage::disk('public')->delete($this->existingInteriorImage);
            }
            $data['interior_image'] = $this->interior_image->store('car-images/interior', 'public');
        }

        // Handle additional image upload
        if ($this->additional_image) {
            if ($this->editMode && $this->existingAdditionalImage) {
                Storage::disk('public')->delete($this->existingAdditionalImage);
            }
            $data['additional_image'] = $this->additional_image->store('car-images/additional', 'public');
        }

        return $data;
    }

     private function resetForm()
    {
        $this->reset([
            'carId', 'brand','transmission', 'setting_capacity', 
            'fuel','year', 'price_per_day', 'status',
            'image', 'interior_image', 'additional_image',
            'existingImage', 'existingInteriorImage', 'existingAdditionalImage'
        ]);
        $this->resetErrorBag();
        $this->status = 'available';
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    // Remove individual images
    public function removeImage($type)
    {
        if ($this->editMode) {
            $car = CarsModel::find($this->carId);
            if ($car) {
                switch ($type) {
                    case 'main':
                        if ($car->image) {
                            Storage::disk('public')->delete($car->image);
                            $car->update(['image' => null]);
                            $this->existingImage = null;
                        }
                        break;
                    case 'interior':
                        if ($car->interior_image) {
                            Storage::disk('public')->delete($car->interior_image);
                            $car->update(['interior_image' => null]);
                            $this->existingInteriorImage = null;
                        }
                        break;
                    case 'additional':
                        if ($car->additional_image) {
                            Storage::disk('public')->delete($car->additional_image);
                            $car->update(['additional_image' => null]);
                            $this->existingAdditionalImage = null;
                        }
                        break;
                }
            }
        }
        
        // Reset the file input
        $this->{$type . '_image'} = null;
    }

    public function render()
    {
        $cars = CarsModel::latest()->paginate(10);
        return view('livewire.admin.add-cars',compact('cars'))->layout('layouts.admin');
    }
}
