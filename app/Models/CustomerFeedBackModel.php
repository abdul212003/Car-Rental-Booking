<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerFeedBackModel extends Model
{
    protected $table = 'customer_feedback';

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'email',
        'rating',
        'message',
    ];
    
}
