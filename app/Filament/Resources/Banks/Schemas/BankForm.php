<?php

namespace App\Filament\Resources\Banks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('Bank Name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('code')
                    ->label(__('Bank Code'))
                    ->required()
                    ->maxLength(255)
                    ->helperText(__('Indonesian bank code (e.g. 014 for BCA, 008 for Mandiri)')),
                TextInput::make('number')
                    ->label(__('Account Number'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('account_holder')
                    ->label(__('Account Holder'))
                    ->required()
                    ->maxLength(255),
            ]);
    }
}
