<?php

namespace App\Filament\Resources\Rentals\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RentalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('Customer'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('vehicle_id')
                    ->label(__('Vehicle'))
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
                TextInput::make('rental_rate_per_day')
                    ->label(__('Rental Rate Per Day'))
                    ->numeric()
                    ->required(),
                Select::make('driver_id')
                    ->label(__('Driver'))
                    ->relationship(
                        name: 'driver',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn ($query) => $query->where('type', 'driver'),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name)
                    ->searchable()
                    ->nullable(),
                TextInput::make('driver_fee_per_day')
                    ->label(__('Driver Fee Per Day'))
                    ->numeric()
                    ->nullable(),
                TextInput::make('total_amount')
                    ->label(__('Total Amount'))
                    ->numeric()
                    ->required()
                    ->helperText(__('Calculate from rental item rates and duration. Set manually if needed.')),
                Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('pending'),
                        'confirmed' => __('confirmed'),
                        'active' => __('active'),
                        'completed' => __('completed'),
                        'cancelled' => __('cancelled'),
                    ])
                    ->required(),
                Select::make('delivery_method')
                    ->label(__('Delivery Method'))
                    ->options([
                        'pickup' => __('Pickup'),
                        'delivery' => __('Delivery'),
                    ])
                    ->native(false),
                Textarea::make('delivery_address')
                    ->label(__('Delivery Address'))
                    ->visible(fn ($get) => $get('delivery_method') === 'delivery')
                    ->required(fn ($get) => $get('delivery_method') === 'delivery'),
            ]);
    }
}
