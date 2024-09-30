<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishDetail extends Model
{
    use HasFactory;

    protected $fillable = [
      'order_id',
      'dish_id',
      'quantity'
    ];

    public function dish()
    {
        return $this->belongsTo(Dish::class, 'dish_id');
    }
}
