<?php

namespace App\Filament\Resources\TableResource\Widgets;

use App\Filament\Resources\TableResource\Traits\TableTrait;
use App\Models\Table;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Class TableStats
 *
 * This class represents the statistics widget for table resources in the admin panel.
 */
class TableStats extends BaseWidget
{
    use TableTrait;

    /**
     * Retrieve the statistics to be displayed in the widget.
     *
     * @return array An array of Stat objects representing the statistics.
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Seats', $this->getTotalSeats()),
            Stat::make('Total Occupied', self::getOccupiedSeats())
        ];
    }

    /**
     * Get the total number of seats for the table with ID 1.
     *
     * @return int The total number of seats, or 0 if not found.
     */
    protected function getTotalSeats(): int
    {
        return Table::where('id', 1)->value('seats') ?? 0;
    }

    /**
     * Get the number of columns to be displayed in the widget.
     *
     * @return int The number of columns.
     */
    public function getColumns(): int
    {
        return 5/2;
    }
}
