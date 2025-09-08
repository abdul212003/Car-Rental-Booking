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
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Brand & Model</th>
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
    <div class="modal fade show d-block" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Brand</label>
                                    <input type="text" class="form-control" wire:model="brand">
                                    @error('brand') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Model</label>
                                    <input type="text" class="form-control" wire:model="model">
                                    @error('model') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Year</label>
                                    <input type="number" class="form-control" wire:model="year" 
                                           min="1900" max="{{ date('Y') + 1 }}">
                                    @error('year') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Price per Day (₱)</label>
                                    <input type="number" step="0.01" class="form-control" 
                                           wire:model="price_per_day" min="0">
                                    @error('price_per_day') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label>Status</label>
                                    <select class="form-select" wire:model="status">
                                        <option value="available">Available</option>
                                        <option value="unavailable">Unavailable</option>
                                    </select>
                                    @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label>Car Image</label>
                            <input type="file" class="form-control" wire:model="image" accept="image/*">
                            @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                            
                            @if($image)
                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid mt-2" style="max-height: 150px;">
                            @elseif($existingImage)
                                <img src="{{ asset('storage/' . $existingImage) }}" class="img-fluid mt-2" style="max-height: 150px;">
                                <p class="text-muted small mt-1">Current image</p>
                            @endif
                        </div>

                        <div class="modal-footer">
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