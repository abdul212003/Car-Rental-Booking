<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\CarsModel;
use App\Models\BookingModel;

class BookingModel extends Model
{
    protected $table = 'bookings';

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone_number',
        'car_id',
        'start_date',
        'end_date',
        'total_days',
        'total_cost',
        'gcash_reference_number',
        'gcash_receipt',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function car()
    {
        return $this->belongsTo(CarsModel::class,'car_id');
    }
}
