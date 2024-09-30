<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Drink extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'comments'
    ];

    public function drinkDetails()
    {
        return $this->hasMany(DrinkDetail::class, 'drink_id');
    }
}
