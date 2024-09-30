<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeatDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'seat_id',
        'quantity'
    ];

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
