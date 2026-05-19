<?php

namespace App\Filament\Resources\Rentals\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RentalItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'rentalItems';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->label(__('Vehicle Name'))
                    ->relationship('vehicle', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DateTimePicker::make('start_date')
                    ->label(__('Start Date'))
                    ->required(),
                DateTimePicker::make('end_date')
                    ->label(__('End Date'))
                    ->required(),
                Select::make('driver_id')
                    ->relationship('driver', 'id')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name)
                    ->modifyQueryUsing(fn ($query) => $query->where('type', 'driver'))
                    ->searchable()
                    ->nullable(),
                TextInput::make('driver_fee_per_day')
                    ->label(__('Driver Fee Per Day'))
                    ->numeric()
                    ->nullable(),
                TextInput::make('rental_rate_per_day')
                    ->label(__('Rental Rate Per Day'))
                    ->numeric()
                    ->required(),
                Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'rented' => __('rented'),
                        'returned' => __('returned'),
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('vehicle_id')
            ->columns([
                TextColumn::make('vehicle.name')
                    ->label(__('Vehicle Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label(__('Start Date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('End Date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('rental_rate_per_day')
                    ->label(__('Rental Rate Per Day'))
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'rented' => 'info',
                        'returned' => 'success',
                        default => 'gray',
                    })
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
