<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $fillable = [
        'booking_id', 'payment_date', 'payment_amount',
        'payment_method', 'payment_status', 'midtrans_order_id', 'payment_token'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
