<?php

namespace App\Filament\Resources\Rentals\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RentalInfolist
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
                TextEntry::make('user.name')
                    ->label(__('User Name'))
                    ->placeholder('-'),
                TextEntry::make('total_amount')
                    ->label(__('Total Amount'))
                    ->money('idr')
                    ->placeholder('-'),
                TextEntry::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->placeholder('-'),
                TextEntry::make('delivery_method')
                    ->label(__('Delivery Method'))
                    ->placeholder('-'),
                TextEntry::make('delivery_address')
                    ->label(__('Delivery Address'))
                    ->placeholder('-'),
            ]);
    }
}
