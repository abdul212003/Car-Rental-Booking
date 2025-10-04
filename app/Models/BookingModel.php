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
        // 'user_id',
        'guest_name',
        'guest_email',
        'guest_phone_number',
        'operator',
        'destination',
        'car_id',
        'start_date',
        'end_date',
        'total_days',
        'total_cost',
        'requirements_valid_id_photo',
        'gcash_reference_number',
        'gcash_receipt',
        'status',
        'rented_out_at',
        'returned_at',
    ];

    protected $casts = [
        'rented_out_at' => 'datetime',
        'returned_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
    

    public function car()
    {
        return $this->belongsTo(CarsModel::class,'car_id');
    }
}
