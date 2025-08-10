<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'user_id', 'booking_date', 'booking_time',
        'status', 'total_price', 'home_service', 'distance',
        'shipping_fee', 'payment_status', 'cancel_status', 'name',
        'address', 'phone', 'rating',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function services() {
        return $this->belongsToMany(Service::class, 'booking_service')
                    ->withPivot('price')
                    ->withTimestamps();
    }

    public function payment() {
        return $this->hasOne(Payment::class);
    }

    public function transactionHistories() {
        return $this->hasMany(TransactionHistory::class);
    }
}
