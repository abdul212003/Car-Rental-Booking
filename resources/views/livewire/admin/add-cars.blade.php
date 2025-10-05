<div>

    <div>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Manage Cars</h5>
            <button class="btn btn-primary" wire:click="create">
                <i class="bi bi-plus-square"></i> Add New Car
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif


        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Image</th>
                                <th>Brand</th>
                                <th>Transmission</th>
                                <th>Seats</th>
                                <th>Fuel</th>
                                <th>Color</th>
                                <th>Plate Number</th>
                                <th>Year</th>
                                <th>Price/Day</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cars as $car)
                                <tr>
                                    <td>{{ $car->id }}</td>
                                    <td>
                                        @if ($car->image)
                                            <img src="{{ asset('storage/' . $car->image) }}"
                                                alt="{{ $car->brand }} {{ $car->model }}"
                                                style="width: 60px; height: 40px; object-fit: cover;" class="rounded">
                                        @else
                                            <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded"
                                                style="width: 60px; height: 40px;">
                                                <i class="fas fa-car"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><strong>{{ $car->brand }}</strong></td>
                                    <td>{{ $car->transmission }}</td>
                                    <td>{{ $car->setting_capacity }} Seats</td>
                                    <td>{{ $car->fuel }}</td>
                                    <td>{{ $car->color }}</td>
                                    <td>{{ $car->plate_number }}</td>
                                    <td>{{ $car->year }}</td>
                                    <td>₱{{ number_format($car->price_per_day, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $car->status == 'available' ? 'success' : ($car->status == 'unavailable' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($car->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button wire:click="edit({{ $car->id }})"
                                                class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button wire:click="toggleStatus({{ $car->id }})"
                                                class="btn btn-sm btn-{{ $car->status == 'available' ? 'secondary' : 'success' }}"
                                                title="{{ $car->status == 'available' ? 'Mark Unavailable' : 'Mark Available' }}">
                                                <i
                                                    class="fas fa-{{ $car->status == 'available' ? 'times' : 'check' }}"></i>
                                            </button>
                                            <button wire:click="delete({{ $car->id }})"
                                                class="btn btn-sm btn-danger"
                                                wire:confirm="Are you sure you want to delete this car?" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="text-center py-4"> <!-- Updated colspan to 13 -->
                                        <i class="fas fa-car fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">No cars found. Add your first car to get started.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $cars->links() }}
                </div>
            </div>
        </div>

    </div>
    <!-- Modal -->
    <div class="modal fade {{ $showModal ? 'show d-block' : '' }}" tabindex="-1"
        style="{{ $showModal ? 'background-color: rgba(0,0,0,0.5);' : '' }}">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>

                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Brand <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="brand" wire:model="brand"
                                    placeholder="e.g., Toyota">
                                @error('brand')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Color<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="color" wire:model="color"
                                    placeholder="e.g., Toyota">
                                @error('color')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Plate Number<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="plate-number" wire:model="plate_number"
                                    placeholder="e.g., Toyota">
                                @error('plate_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="transmission" class="form-label">Transmission <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="transmission" wire:model="transmission">
                                    <option value="">-- Select Transmission --</option>
                                    <option value="Manual">Manual</option>
                                    <option value="Automatic">Automatic</option>
                                </select>
                                @error('transmission')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="setting_capacity" class="form-label">Seating Capacity <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="setting_capacity" wire:model="setting_capacity">
                                    <option value="">-- Select Capacity --</option>
                                    <option value="2">2 Seats</option>
                                    <option value="4">4 Seats</option>
                                    <option value="5">5 Seats</option>
                                    <option value="8">8 Seats</option>
                                    <option value="10">10 Seats</option>
                                    <option value="12">12 Seats</option>
                                    <option value="15">15 Seats</option>
                                </select>
                                @error('setting_capacity')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fuel" class="form-label">Fuel Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="fuel" wire:model="fuel">
                                    <option value="">-- Select Fuel Type --</option>
                                    <optgroup label="Gasoline">
                                        <option value="Unleaded">Unleaded</option>
                                        <option value="Premium">Premium</option>
                                    </optgroup>
                                    <option value="Diesel">Diesel</option>
                                </select>
                                @error('fuel')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="year" class="form-label">Year <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="year" wire:model="year"
                                    min="1900" max="{{ date('Y') + 1 }}" placeholder="e.g., 2023">
                                @error('year')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="price_per_day" class="form-label">Price per Day (₱) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="price_per_day"
                                    wire:model="price_per_day" min="0" placeholder="e.g., 1500.00">
                                @error('price_per_day')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="status" wire:model="status">
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Car Images Section -->
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3"><i class="fas fa-images me-2"></i>Car Images</h6>

                                <div class="row g-3">
                                    <!-- Main Image -->
                                    <div class="col-md-4">
                                        <label class="form-label">Main Image</label>
                                        <input type="file" class="form-control" wire:model="image"
                                            accept="image/*">
                                        @error('image')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-2">
                                            @if ($image)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail"
                                                        style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="$set('image', null)">×</button>
                                                </div>
                                            @elseif($existingImage)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset('storage/' . $existingImage) }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="removeImage('main')">×</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Interior Image -->
                                    <div class="col-md-4">
                                        <label class="form-label">Interior Image</label>
                                        <input type="file" class="form-control" wire:model="interior_image"
                                            accept="image/*">
                                        @error('interior_image')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-2">
                                            @if ($interior_image)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ $interior_image->temporaryUrl() }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="$set('interior_image', null)">×</button>
                                                </div>
                                            @elseif($existingInteriorImage)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset('storage/' . $existingInteriorImage) }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="removeImage('interior')">×</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Additional Image -->
                                    <div class="col-md-4">
                                        <label class="form-label">Additional Image</label>
                                        <input type="file" class="form-control" wire:model="additional_image"
                                            accept="image/*">
                                        @error('additional_image')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-2">
                                            @if ($additional_image)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ $additional_image->temporaryUrl() }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="$set('additional_image', null)">×</button>
                                                </div>
                                            @elseif($existingAdditionalImage)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset('storage/' . $existingAdditionalImage) }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="removeImage('additional')">×</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> {{ $editMode ? 'Update Car' : 'Add Car' }}
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
{{-- @if ($showModal)
    <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close btn-close-white" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            <!-- Basic Information -->
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Brand <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="brand" wire:model="brand"
                                    placeholder="e.g., Toyota">
                                @error('brand')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Color<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="color" wire:model="color"
                                    placeholder="e.g., Toyota">
                                @error('color')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="brand" class="form-label">Plate Number<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="plate-number" wire:model="plate_number"
                                    placeholder="e.g., Toyota">
                                @error('plate_number')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="transmission" class="form-label">Transmission <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="transmission" wire:model="transmission">
                                    <option value="">-- Select Transmission --</option>
                                    <option value="Manual">Manual</option>
                                    <option value="Automatic">Automatic</option>
                                </select>
                                @error('transmission')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="setting_capacity" class="form-label">Seating Capacity <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="setting_capacity" wire:model="setting_capacity">
                                    <option value="">-- Select Capacity --</option>
                                    <option value="2">2 Seats</option>
                                    <option value="4">4 Seats</option>
                                    <option value="5">5 Seats</option>
                                    <option value="8">8 Seats</option>
                                    <option value="10">10 Seats</option>
                                    <option value="12">12 Seats</option>
                                    <option value="15">15 Seats</option>
                                </select>
                                @error('setting_capacity')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="fuel" class="form-label">Fuel Type <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="fuel" wire:model="fuel">
                                    <option value="">-- Select Fuel Type --</option>
                                    <optgroup label="Gasoline">
                                        <option value="Unleaded">Unleaded</option>
                                        <option value="Premium">Premium</option>
                                    </optgroup>
                                    <option value="Diesel">Diesel</option>
                                </select>
                                @error('fuel')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="year" class="form-label">Year <span
                                        class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="year" wire:model="year"
                                    min="1900" max="{{ date('Y') + 1 }}" placeholder="e.g., 2023">
                                @error('year')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="price_per_day" class="form-label">Price per Day (₱) <span
                                        class="text-danger">*</span></label>
                                <input type="number" step="0.01" class="form-control" id="price_per_day"
                                    wire:model="price_per_day" min="0" placeholder="e.g., 1500.00">
                                @error('price_per_day')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status <span
                                        class="text-danger">*</span></label>
                                <select class="form-select" id="status" wire:model="status">
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Car Images Section -->
                            <div class="col-12">
                                <hr>
                                <h6 class="mb-3"><i class="fas fa-images me-2"></i>Car Images</h6>

                                <div class="row g-3">
                                    <!-- Main Image -->
                                    <div class="col-md-4">
                                        <label class="form-label">Main Image</label>
                                        <input type="file" class="form-control" wire:model="image"
                                            accept="image/*">
                                        @error('image')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-2">
                                            @if ($image)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail"
                                                        style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="$set('image', null)">×</button>
                                                </div>
                                            @elseif($existingImage)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset('storage/' . $existingImage) }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="removeImage('main')">×</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Interior Image -->
                                    <div class="col-md-4">
                                        <label class="form-label">Interior Image</label>
                                        <input type="file" class="form-control" wire:model="interior_image"
                                            accept="image/*">
                                        @error('interior_image')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-2">
                                            @if ($interior_image)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ $interior_image->temporaryUrl() }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="$set('interior_image', null)">×</button>
                                                </div>
                                            @elseif($existingInteriorImage)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset('storage/' . $existingInteriorImage) }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="removeImage('interior')">×</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Additional Image -->
                                    <div class="col-md-4">
                                        <label class="form-label">Additional Image</label>
                                        <input type="file" class="form-control" wire:model="additional_image"
                                            accept="image/*">
                                        @error('additional_image')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror

                                        <div class="mt-2">
                                            @if ($additional_image)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ $additional_image->temporaryUrl() }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="$set('additional_image', null)">×</button>
                                                </div>
                                            @elseif($existingAdditionalImage)
                                                <div class="position-relative d-inline-block">
                                                    <img src="{{ asset('storage/' . $existingAdditionalImage) }}"
                                                        class="img-thumbnail" style="max-height: 150px;">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger position-absolute top-0 end-0"
                                                        wire:click="removeImage('additional')">×</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> {{ $editMode ? 'Update Car' : 'Add Car' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif --}}
