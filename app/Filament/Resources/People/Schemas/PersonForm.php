<?php

namespace App\Filament\Resources\People\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label(__('User Name'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('type')
                    ->label(__('Type'))
                    ->options([
                        'customer' => __('Customer'),
                        'employee' => __('Employee'),
                        'driver' => __('Driver'),
                        'admin' => __('Admin'),
                    ])
                    ->required(),
                TextInput::make('phone')
                    ->label(__('Phone'))
                    ->tel(),
                Textarea::make('address')
                    ->label(__('Address'))
                    ->columnSpanFull()
                    ->rows(2),
                Select::make('id_type')
                    ->label(__('Id Type'))
                    ->options([
                        'ktp' => 'KTP',
                        'sim' => 'SIM',
                        'paspor' => 'Paspor',
                        'kartu pelajar' => 'Kartu Pelajar',
                    ]),
                FileUpload::make('id_file_path')
                    ->label(__('ID Document'))
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'application/pdf'])
                    ->maxSize(2048)
                    ->directory('id-documents')
                    ->columnSpanFull(),
                TextInput::make('driver_fee_per_day')
                    ->label(__('Driver Fee Per Day'))
                    ->numeric()
                    ->prefix('Rp')
                    ->visible(fn ($get) => $get('type') === 'driver'),
            ]);
    }
}
