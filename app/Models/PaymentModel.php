<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    protected $table = 'payment';

    protected $fillable = [
        'gcash_reference_number',
        'gcash_receipt',
        'car_id',
    ];
}
