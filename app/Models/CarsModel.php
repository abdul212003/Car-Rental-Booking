<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\BookingModel;

class CarsModel extends Model
{
    use HasFactory;
    
    protected $table = 'cars';

    protected $primaryKey = 'id';

    protected $fillable = [
        'brand',
        'color',
        'plate_number',
        'transmission',
        'setting_capacity',
        'fuel',
        'year',
        'price_per_day',
        'image',
        'interior_image',
        'additional_image',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(BookingModel::class, 'car_id');
    }

    public function isAvailable($startDate, $endDate)
    {
        if (!$startDate || !$endDate) {
            return $this->status === 'available';
        }

        $hasConflict = $this->bookings()
            ->whereIn('status', ['confirmed', 'pending']) // block both confirmed & pending
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            })
            ->exists();

        return !$hasConflict && $this->status === 'available';
    }


    public function scopeAvailableBetween($query, $startDate, $endDate)
    {
        return $query->where('status', 'available')
            ->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->whereIn('status', ['confirmed', 'pending']) // keep consistent
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where('start_date', '<=', $endDate)
                            ->where('end_date', '>=', $startDate);
                });
            });
    }

}
