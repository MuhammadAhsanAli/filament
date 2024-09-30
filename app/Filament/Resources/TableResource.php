<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TableResource\Pages;
use App\Filament\Resources\TableResource\RelationManagers;
use App\Filament\Resources\TableResource\Traits\TableTrait;
use App\Models\Order;
use App\Models\Table as TableModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class TableResource
 *
 * This class represents the resource for managing tables in the admin panel.
 */
class TableResource extends Resource
{
    use TableTrait;

    /** @var string|null The model associated with the resource. */
    protected static ?string $model = TableModel::class;

    /** @var string|null The label for the navigation item. */
    protected static ?string $navigationLabel = 'Table 1';

    /** @var string|null The icon for the navigation item. */
    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    /** @var string|null The attribute used as the title for records. */
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * Get the navigation badge showing occupied seats.
     *
     * @return string|null The badge representing the number of occupied seats.
     */
    public static function getNavigationBadge(): ?string
    {
        return self::getOccupiedSeats();
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
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('seats')->required(),
                Forms\Components\Toggle::make('status'),
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
                TextColumn::make('seatDetails.order.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->visibleFrom('md'),
                TextColumn::make('seatDetails.order.dishDetails.dish.meal')
                    ->label('Meal')
                    ->sortable()
                    ->searchable()
                    ->alignment(Alignment::End),
                TextColumn::make('seatDetails.order.email')
                    ->label('Email')
                    ->sortable()
                    ->searchable()
                    ->alignment(Alignment::End),
                TextColumn::make('seatDetails.order.comments')
                    ->label('Comments')
                    ->words(2, '')
                    ->sortable()
                    ->searchable()
                    ->toggleable()
                    ->alignment(Alignment::End),
            ])
            ->query(function (Builder $query): Builder {
                return Order::with(['dishDetails.dish', 'seatDetails.seat'])
                    ->whereHas('seatDetails.seat', function (Builder $query) {
                        $query->where('table_id', 1);
                    });
            })
            ->toggleColumnsTriggerAction(
                fn (Action $action) => $action
                    ->link()
                    ->label(false)
                    ->icon('heroicon-o-view-columns')
                    ->color('warning')
            )
            ->filters([
                // Define filters if needed
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get the relation managers for the resource.
     *
     * @return array An array of relation manager classes.
     */
    public static function getRelations(): array
    {
        return [
            RelationManagers\SeatsRelationManager::class,
        ];
    }

    /**
     * Get the pages for the resource.
     *
     * @return array An array of page classes.
     */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTables::route('/'),
            'create' => Pages\CreateTable::route('/create'),
            'edit' => Pages\EditTable::route('/{record}/edit'),
        ];
    }

    /**
     * Determine if the resource can be created.
     *
     * @return bool False to hide the Create button.
     */
    public static function canCreate(): bool
    {
        return false; // Hide the Create button
    }
}
