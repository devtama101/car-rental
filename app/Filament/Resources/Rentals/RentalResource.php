<?php

namespace App\Filament\Resources\Rentals;

use App\Filament\Resources\Rentals\Pages\CreateRental;
use App\Filament\Resources\Rentals\Pages\EditRental;
use App\Filament\Resources\Rentals\Pages\ListRentals;
use App\Filament\Resources\Rentals\Pages\ViewRental;
use App\Filament\Resources\Rentals\RelationManagers\RentalItemsRelationManager;
use App\Filament\Resources\Rentals\Schemas\RentalForm;
use App\Filament\Resources\Rentals\Schemas\RentalInfolist;
use App\Filament\Resources\Rentals\Tables\RentalsTable;
use App\Models\Rental;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $recordTitleAttribute = 'rental';

    protected static string|UnitEnum|null $navigationGroup = null;

    public static function getModelLabel(): string
    {
        return __('Rental');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Rentals');
    }

    public static function form(Schema $schema): Schema
    {
        return RentalForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RentalInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RentalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            RentalItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRentals::route('/'),
            'create' => CreateRental::route('/create'),
            'view' => ViewRental::route('/{record}'),
            'edit' => EditRental::route('/{record}/edit'),
        ];
    }
}
