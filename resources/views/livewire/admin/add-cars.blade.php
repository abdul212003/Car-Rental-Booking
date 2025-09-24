<div>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>Manage Cars</h5>
        <button class="btn btn-primary" wire:click="create">
            <i class="bi bi-plus-square"></i> Add New Car
        </button>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Trasmission</th>
                            <th>Setting Capacity</th>
                            <th>Fuel</th>
                            {{-- <th>Gasoline</th> --}}
                            <th>Brand</th>
                            <th>Year</th>
                            <th>Price/Day</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cars as $car)
                        <tr>
                            <td>
                                @if($car->image)
                                    <img src="{{ asset('storage/' . $car->image) }}" 
                                         alt="{{ $car->brand }} {{ $car->model }}" 
                                         style="width: 60px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary text-white d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 40px;">
                                        <i class="fas fa-car"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{$car->transmission}}</td>
                            <td>{{$car->setting_capacity}}</td>
                            <td>{{$car->fuel}}</td>
                            {{-- <td>{{ $car->gasoline_type }}</td> --}}
                            <td>{{ $car->brand }} {{ $car->model }}</td>

                            <td>{{ $car->year }}</td>
                            <td>₱{{ number_format($car->price_per_day, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $car->status == 'available' ? 'success' : 'danger' }}">
                                    {{ ucfirst($car->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button wire:click="edit({{ $car->id }})" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="toggleStatus({{ $car->id }})" 
                                            class="btn btn-sm btn-{{ $car->status == 'available' ? 'secondary' : 'success' }}">
                                        <i class="fas fa-{{ $car->status == 'available' ? 'times' : 'check' }}"></i>
                                    </button>
                                    <button wire:click="delete({{ $car->id }})" 
                                            class="btn btn-sm btn-danger" 
                                            onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No cars found.</td>
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

<!-- Modal -->
@if($showModal)
<div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title">{{ $modalTitle }}</h5>
                <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="brand" class="form-label">Brand</label>
                                <input type="text" class="form-control" id="brand" wire:model="brand">
                                @error('brand') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transmission" class="form-label">Transmission</label>
                                <select class="form-select" id="transmission" wire:model="transmission">
                                    <option value="">--Select--</option>
                                    <option value="Manual">Manual</option>
                                    <option value="Automatic">Automatic</option>
                                </select>
                                @error('transmission') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="setting_capacity" class="form-label">Seating Capacity</label>
                                <select class="form-select" id="setting_capacity" wire:model="setting_capacity">
                                    <option value="">--Select--</option>
                                    <option value="2">2</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="8">8</option>
                                    <option value="10">10</option>
                                    <option value="12">12</option>
                                    <option value="15">15</option>
                                </select>
                                @error('setting_capacity') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fuel" class="form-label">Fuel</label>
                                <select class="form-select" id="fuel" wire:model="fuel">
                                    <option value="">--Select--</option>
                                    <optgroup label="Gasoline">
                                        <option value="Unleaded">Unleaded</option>
                                        <option value="Premium">Premium</option>
                                    </optgroup>
                                    <option value="Diesel">Diesel</option>
                                </select>
                                @error('fuel') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="year" class="form-label">Year</label>
                                <input type="number" class="form-control" id="year" wire:model="year" 
                                       min="1900" max="{{ date('Y') + 1 }}">
                                @error('year') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="price_per_day" class="form-label">Price per Day (₱)</label>
                                <input type="number" step="0.01" class="form-control" id="price_per_day"
                                       wire:model="price_per_day" min="0">
                                @error('price_per_day') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" wire:model="status">
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                                @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="image" class="form-label">Car Image</label>
                                <input type="file" class="form-control" id="image" wire:model="image" accept="image/*">
                                @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                
                                <div class="mt-2">
                                    @if($image)
                                        <img src="{{ $image->temporaryUrl() }}" class="img-thumbnail" style="max-height: 150px;">
                                    @elseif($existingImage)
                                        <img src="{{ asset('storage/' . $existingImage) }}" class="img-thumbnail" style="max-height: 150px;">
                                        <p class="text-muted small mt-1">Current image</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer mt-4">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $editMode ? 'Update Car' : 'Add Car' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
</div>