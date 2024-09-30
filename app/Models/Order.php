<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'comments'
    ];

    public function dishDetails()
    {
        return $this->hasMany(DishDetail::class, 'order_id');
    }

    public function drinkDetails()
    {
        return $this->hasMany(DrinkDetail::class, 'order_id');
    }

    public function seatDetails()
    {
        return $this->hasMany(SeatDetail::class, 'order_id');
    }
}
