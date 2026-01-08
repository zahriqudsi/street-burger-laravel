<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'phone_number',
        'guest_name',
        'guest_count',
        'reservation_date',
        'reservation_time',
        'special_requests',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
