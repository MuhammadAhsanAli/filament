<?php

namespace App\Filament\Resources\DishResource\Widgets;

use App\Models\Dish;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DishStats extends BaseWidget
{
    /**
     * Get the statistics to display in the widget.
     *
     * @return array
     */
    protected function getStats(): array
    {
        return [
            Stat::make('Total Meals', Dish::count()),
        ];
    }

    /**
     * Get the number of columns for the widget layout.
     *
     * @return int
     */
    public function getColumns(): int
    {
        return 12;
    }
}
