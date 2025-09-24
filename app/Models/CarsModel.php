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
        'transmission',
        'setting_capacity',
        'fuel',
        'year',
        'price_per_day',
        'image',
        'status',
    ];

    public function bookings()
    {
        return $this->hasMany(BookingModel::class, 'car_id');
    }

    public function isAvailable($startDate, $endDate)
    {
        if (!$startDate || !$endDate) 
        {
            return $this->status === 'available';
        }

        $hasConflict = $this->bookings()
            ->where('status', 'confirmed')
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                        ->orWhereBetween('end_date',[$startDate,$endDate])
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_date', '<=' , $startDate)
                                ->where('end_date', '>=' , $endDate);
                        });
            } )->exists();

        return !$hasConflict && $this->status === 'available';
    }

    public function scopeAvailableBetween($query, $startDate, $endDate)
    {
        return $query->where('status', 'available')
            ->whereDoesntHave('bookings', function ($q) use ($startDate, $endDate) {
                $q->where('status', 'confirmed')
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('start_date', [$startDate, $endDate])
                            ->orWhereBetween('end_date', [$startDate, $endDate])
                            ->orWhere(function ($query) use ($startDate, $endDate) {
                                $query->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                            });
                });
            });
    }
}
