<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $table = 'transaction_histories';

    protected $fillable = [
        'booking_id', 'worker_id', 'status', 'confirmation_status',
    ];

    public function booking() {
        return $this->belongsTo(Booking::class);
    }

    public function worker() {
        return $this->belongsTo(Worker::class);
    }
}
