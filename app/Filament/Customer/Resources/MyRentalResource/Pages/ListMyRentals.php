<?php

namespace App\Filament\Customer\Resources\MyRentalResource\Pages;

use App\Enums\RentalStatus;
use App\Filament\Customer\Resources\MyRentalResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListMyRentals extends ListRecords
{
    protected static string $resource = MyRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('newBooking')
                ->label(__('New Booking'))
                ->icon('heroicon-o-plus')
                ->url('/'),
        ];
    }

    public function getTabs(): array
    {
        return [
            'active' => Tab::make(__('Active'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [
                    RentalStatus::Pending->value,
                    RentalStatus::Confirmed->value,
                    RentalStatus::Active->value,
                ]))
                ->badge(fn () => auth()->user()->rentals()
                    ->whereIn('status', [
                        RentalStatus::Pending->value,
                        RentalStatus::Confirmed->value,
                        RentalStatus::Active->value,
                    ])->count()),
            'past' => Tab::make(__('Past'))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [
                    RentalStatus::Completed->value,
                    RentalStatus::Cancelled->value,
                ]))
                ->badge(fn () => auth()->user()->rentals()
                    ->whereIn('status', [
                        RentalStatus::Completed->value,
                        RentalStatus::Cancelled->value,
                    ])->count()),
        ];
    }
}
