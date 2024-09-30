<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'meal',
        'comments'
    ];

    public function dishDetails()
    {
        return $this->hasMany(DishDetail::class, 'dish_id');
    }
}
