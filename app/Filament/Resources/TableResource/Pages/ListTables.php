<?php

namespace App\Filament\Resources\TableResource\Pages;

use App\Filament\Resources\TableResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListTables extends ListRecords
{
    protected static string $resource = TableResource::class;

    /**
     * Get the actions to display in the header.
     *
     * @return array
     */
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    /**
     * Get the widgets to display in the header.
     *
     * @return array
     */
    protected function getHeaderWidgets(): array
    {
        return [
            TableResource\Widgets\TableStats::class,
        ];
    }

    /**
     * Get the header view with breadcrumbs.
     *
     * @return View|null
     */
    public function getHeader(): ?View
    {
        return view('filament.pages.list-tables-header', [
            'breadcrumbs' => $this->getBreadcrumbs(),
        ]);
    }
}
