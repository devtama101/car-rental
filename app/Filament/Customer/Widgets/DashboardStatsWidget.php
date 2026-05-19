<?php

namespace App\Filament\Customer\Widgets;

use App\Enums\RentalStatus;
use App\Filament\Customer\Resources\MyRentalResource;
use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $rentalIds = auth()->user()->rentals()->pluck('id');

        $activeCount = auth()->user()->rentals()
            ->whereIn('status', [
                RentalStatus::Pending->value,
                RentalStatus::Confirmed->value,
                RentalStatus::Active->value,
            ])
            ->count();

        $pendingPaymentCount = Payment::whereIn('rental_id', $rentalIds)
            ->where('status', 'pending')
            ->count();

        $unpaidCount = auth()->user()->rentals()
            ->whereDoesntHave('payments', fn ($q) => $q->where('status', 'paid'))
            ->count();

        return [
            Stat::make(__('Active Rentals'), $activeCount)
                ->description(__('Pending, confirmed & active bookings'))
                ->color('success')
                ->url(MyRentalResource::getUrl('index', ['tab' => 'active'])),
            Stat::make(__('Pending Payments'), $pendingPaymentCount)
                ->description(__('Payments awaiting verification'))
                ->color('warning'),
            Stat::make(__('Unpaid Rentals'), $unpaidCount)
                ->description(__('Rentals needing payment'))
                ->color('danger'),
        ];
    }
}
