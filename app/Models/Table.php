<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'seats',
        'status'
    ];

    public function seat()
    {
        return $this->hasMany(Seat::class, 'table_id');
    }
}
