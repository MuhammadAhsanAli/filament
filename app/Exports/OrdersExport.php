<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class OrdersExport implements FromCollection, WithHeadings
{
    protected Model $orders;

    /**
     * Create a new OrdersExport instance.
     *
     * @param Model $orders
     */
    public function __construct(Model $orders)
    {
        $this->orders = $orders;
    }

    /**
     * Retrieve the collection of orders for export.
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return collect($this->mapOrdersToExportData());
    }

    /**
     * Map the orders data to the export format.
     *
     * @return array
     */
    protected function mapOrdersToExportData(): array
    {
        return $this->orders->seat->map(function ($seat) {
            return [
                'Seat No.' => (int) $seat->id,
                'Name' => (string) $this->getOrderName($seat),
                'Email' => (string) $this->getOrderEmail($seat),
                'Meals' => (string) $this->getOrderMeal($seat),
                'Drinks' => (string) $this->getOrderDrink($seat),
                'Comments' => (string) $this->getOrderComments($seat),
            ];
        })->toArray();
    }

    /**
     * Get the name of the order from seat details.
     *
     * @param $seat
     * @return string|null
     */
    protected function getOrderName($seat): ?string
    {
        return $seat->seatDetails?->first()?->order?->name ?? '';
    }

    /**
     * Get the email of the order from seat details.
     *
     * @param $seat
     * @return string|null
     */
    protected function getOrderEmail($seat): ?string
    {
        return $seat->seatDetails?->first()?->order?->email ?? '';
    }

    /**
     * Get the meal from the order.
     *
     * @param $seat
     * @return string|null
     */
    protected function getOrderMeal($seat): ?string
    {
        return $seat->seatDetails?->first()?->order?->dishDetails->first()?->dish?->meal ?? '';
    }

    /**
     * Get the drink from the order.
     *
     * @param $seat
     * @return string|null
     */
    protected function getOrderDrink($seat): ?string
    {
        return $seat->seatDetails?->first()?->order?->drinkDetails->first()?->drink?->meal ?? '';
    }

    /**
     * Get comments from the order.
     *
     * @param $seat
     * @return string
     */
    protected function getOrderComments($seat): string
    {
        return $seat->seatDetails?->first()?->order?->comments ?? 'Unreserved';
    }

    /**
     * Retrieve the headings for the exported data.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'Seat No.',
            'Name',
            'Email',
            'Meals',
            'Drinks',
            'Comments',
        ];
    }
}
