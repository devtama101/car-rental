<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Enums\RentalType;
use App\Enums\TransmissionType;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('year')
                    ->label(__('Year'))
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue((int) date('Y') + 1),
                Select::make('transmission')
                    ->label(__('Transmission'))
                    ->options(TransmissionType::class)
                    ->required(),
                TextInput::make('license_plate')
                    ->label(__('License Plate'))
                    ->unique(ignoreRecord: true),
                Select::make('rental_type')
                    ->label(__('Rental Type'))
                    ->options(RentalType::class)
                    ->required(),
                Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label(__('Image'))
                    ->image(),
                TextInput::make('rental_rate_per_day')
                    ->label(__('Rental Rate Per Day'))
                    ->numeric()
                    ->minValue(0),
            ]);
    }
}
