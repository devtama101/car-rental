<?php

namespace App\Filament\Resources\Rentals\Schemas;

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
