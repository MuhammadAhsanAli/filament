<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    use HasFactory;

    protected $fillable = [
        'table_id',
        'seat_no'
    ];

    public function seatDetails()
    {
        return $this->hasMany(SeatDetail::class, 'seat_id');
    }
}
