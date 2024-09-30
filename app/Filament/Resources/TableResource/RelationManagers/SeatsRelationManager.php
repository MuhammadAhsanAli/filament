<?php

namespace App\Filament\Resources\TableResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Table;

class SeatsRelationManager extends RelationManager
{
    protected static string $relationship = 'seat';

    /**
     * Define the form schema for creating/editing a seat.
     *
     * @param Form $form
     * @return Form
     */
    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('seat_no')
                ->required()
                ->maxLength(255),
        ]);
    }

    /**
     * Define the table schema for displaying seats.
     *
     * @param Table $table
     * @return Table
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('seat_no')
            ->columns([
                Tables\Columns\TextColumn::make('seat_no')
                    ->label('Seat Number'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
