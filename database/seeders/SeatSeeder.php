<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Seeder;

class SeatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = Table::all();
        foreach ($tables as $table) {

            // Loop from 1 to the number of seats in each table
            for ($seatNo = 1; $seatNo <= $table->seats; $seatNo++) {
                \DB::table('seats')->insert([
                    'table_id' => $table->id,
                    'seat_no' => $seatNo,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
