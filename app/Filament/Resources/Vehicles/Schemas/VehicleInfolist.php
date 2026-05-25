<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class VehicleInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('name')
                    ->label(__('Name')),
                TextEntry::make('year')
                    ->label(__('Year'))
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('transmission')
                    ->label(__('Transmission'))
                    ->placeholder('-'),
                TextEntry::make('license_plate')
                    ->label(__('License Plate'))
                    ->placeholder('-'),
                TextEntry::make('rental_type')
                    ->label(__('Rental Type'))
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->label(__('Description'))
                    ->placeholder('-')
                    ->columnSpanFull(),
                ImageEntry::make('image')
                    ->label(__('Image'))
                    ->placeholder('-'),
                TextEntry::make('rental_rate_per_day')
                    ->label(__('Rental Rate Per Day'))
                    ->numeric()
                    ->placeholder('-'),
            ]);
    }
}
