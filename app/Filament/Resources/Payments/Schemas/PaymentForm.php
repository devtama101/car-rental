<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rental_id')
                    ->label(__('Rental'))
                    ->relationship('rental', 'id')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->required(),
                Select::make('method')
                    ->label(__('Method'))
                    ->options([
                        'cash' => __('cash'),
                        'transfer' => __('transfer'),
                    ])
                    ->required(),
                FileUpload::make('proof_file_path')
                    ->label(__('Payment Proof'))
                    ->image()
                    ->directory('payment-proofs'),
                Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'pending' => __('pending'),
                        'paid' => __('paid'),
                        'refunded' => __('refunded'),
                    ])
                    ->required(),
                DatePicker::make('date')
                    ->label(__('Date'))
                    ->required(),
            ]);
    }
}
