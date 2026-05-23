<?php

namespace App\Filament\Resources\Rentals\Schemas;

use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RentalInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Rental Details'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('Created At'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('updated_at')
                            ->label(__('Updated At'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('user.name')
                            ->label(__('Customer'))
                            ->placeholder('-'),
                        TextEntry::make('vehicle.name')
                            ->label(__('Vehicle'))
                            ->placeholder('-'),
                        TextEntry::make('start_date')
                            ->label(__('Start Date'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('end_date')
                            ->label(__('End Date'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('rental_rate_per_day')
                            ->label(__('Rental Rate Per Day'))
                            ->money('idr')
                            ->placeholder('-'),
                        TextEntry::make('driver.user.name')
                            ->label(__('Driver'))
                            ->placeholder('-'),
                        TextEntry::make('driver_fee_per_day')
                            ->label(__('Driver Fee Per Day'))
                            ->money('idr')
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
                    ])
                    ->columns(2),
                Section::make(__('Payment Details'))
                    ->schema([
                        RepeatableEntry::make('payments')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('method')
                                    ->label(__('Method'))
                                    ->formatStateUsing(fn (string $state): string => __($state)),
                                TextEntry::make('bank.name')
                                    ->label(__('Bank'))
                                    ->placeholder('-'),
                                TextEntry::make('amount')
                                    ->label(__('Amount'))
                                    ->money('idr'),
                                TextEntry::make('date')
                                    ->label(__('Payment Date'))
                                    ->date(),
                                ImageEntry::make('proof_file_path')
                                    ->label(__('Payment Proof'))
                                    ->height(120)
                                    ->url(function (?string $state): ?string {
                                        if (! $state) {
                                            return null;
                                        }

                                        return route('file.view', ['path' => $state]);
                                    }, shouldOpenInNewTab: true)
                                    ->visible(fn ($record): bool => filled($record->proof_file_path)),
                            ])
                            ->columns(3),
                    ]),
                Section::make(__('Customer Documents'))
                    ->schema([
                        ImageEntry::make('user.person.id_file_path')
                            ->label(__('ID Document'))
                            ->height(200)
                            ->url(function (?string $state): ?string {
                                if (! $state) {
                                    return null;
                                }

                                return route('file.view', ['path' => $state]);
                            }, shouldOpenInNewTab: true)
                            ->visible(fn ($record): bool => filled($record->user?->person?->id_file_path)),
                    ]),
            ]);
    }
}
