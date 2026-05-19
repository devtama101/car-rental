<?php

namespace App\Filament\Widgets;

use App\Enums\RentalStatus;
use App\Filament\Resources\Rentals\RentalResource;
use App\Models\Rental;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class ActiveRentalsTable extends TableWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Active Rentals';

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Active Rentals');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Rental::with(['user', 'rentalItems.vehicle'])
                ->whereIn('status', [
                    RentalStatus::Confirmed->value,
                    RentalStatus::Active->value,
                ])
                ->latest())
            ->columns([
                ImageColumn::make('vehicle_image')
                    ->label('')
                    ->getStateUsing(fn (Rental $record): ?string => $record->rentalItems->first()?->vehicle?->image)
                    ->circular()
                    ->size(40),
                TextColumn::make('vehicle_name')
                    ->label(__('Vehicle'))
                    ->getStateUsing(fn (Rental $record): string => $record->rentalItems->first()?->vehicle?->name ?? '-')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('period')
                    ->label(__('Period'))
                    ->getStateUsing(function (Rental $record): string {
                        $item = $record->rentalItems->first();
                        if (! $item || ! $item->start_date || ! $item->end_date) {
                            return '-';
                        }

                        return $item->start_date->format('d M').' — '.$item->end_date->format('d M');
                    }),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'confirmed' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->recordUrl(fn (Rental $record): string => RentalResource::getUrl('view', ['record' => $record->id]))
            ->defaultSort('created_at', 'desc')
            ->paginated(false);
    }
}
