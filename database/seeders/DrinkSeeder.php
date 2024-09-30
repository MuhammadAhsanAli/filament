<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DrinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('drinks')->insert([
            'name' => 'Red Bull',
            'comments' => 'Cold Drink',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
