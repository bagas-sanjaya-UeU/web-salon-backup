<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{

    protected $table = 'services';

    protected $fillable = [
        'image',
        'service_name',
        'description',
        'price',
        'is_home_service',
    ];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_service')
            ->withPivot('price')
            ->withTimestamps();
    }
}
