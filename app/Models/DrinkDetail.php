<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrinkDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'drink_id',
        'quantity'
    ];

    public function drink()
    {
        return $this->belongsTo(Drink::class, 'drink_id');
    }
}
