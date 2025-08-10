<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingService extends Model
{
    protected $table = 'booking_service';

    protected $fillable = ['booking_id', 'service_id', 'price'];
}
