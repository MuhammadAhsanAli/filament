<?php

namespace App\Filament\Resources\TableResource\Traits;

use App\Models\Table;

/**
 * Trait TableTrait
 *
 * This trait provides functionality to retrieve the number of occupied seats for a table.
 */
trait TableTrait
{
    /**
     * Get the number of occupied seats for the table with ID 1.
     *
     * @return int The number of occupied seats, or 0 if not found.
     */
    protected static function getOccupiedSeats(): int
    {
        $table = Table::with('seat.seatDetails')->find(1);

        if ($table) {
            return $table->seat->sum(function ($seat) {
                return $seat->seatDetails->count();
            });
        }

        return 0; // Return 0 if the table is not found
    }
}
