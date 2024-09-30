<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DishResource\Pages;
use App\Models\Dish;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * Class DishResource
 *
 * This class represents the resource for managing dishes in the admin panel.
 */
class DishResource extends Resource
{
    /** @var string|null The model associated with the resource. */
    protected static ?string $model = Dish::class;

    /** @var string|null The icon for the navigation item. */
    protected static ?string $navigationIcon = '';

    /** @var string|null The attribute used as the title for records. */
    protected static ?string $recordTitleAttribute = 'meal';

    /**
     * Get the navigation badge showing the count of dishes.
     *
     * @return string|null The badge representing the count of dishes.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    /**
     * Define the form used for creating or editing records.
     *
     * @param Form $form The form instance.
     * @return Form The configured form instance.
     */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('meal')->required(),
                Forms\Components\Textarea::make('comments')->required(),
            ]);
    }

    /**
     * Define the table view for the resource.
     *
     * @param Table $table The table instance.
     * @return Table The configured table instance.
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('meal')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('comments')
                    ->words(1, '...')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->alignment(Alignment::End)
                    ->visibleFrom('md'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->toggleColumnsTriggerAction(
                fn (Action $action) => $action
                    ->link()
                    ->label(false)
                    ->icon('heroicon-o-view-columns')
                    ->color('warning')
            )
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get the pages for the resource.
     *
     * @return array An array of page classes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDishes::route('/'),
            'create' => Pages\CreateDish::route('/create'),
            'edit' => Pages\EditDish::route('/{record}/edit'),
        ];
    }
}
