<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactUsModel extends Model
{
    protected $table = 'contact_us';

    protected $primaryKey = 'id';

    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_subject',
        'contact_message',
    ];
}
