<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Enums\RentalStatus;
use App\Filament\Resources\Rentals\RentalResource;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Builder;

class ViewRental extends ViewRecord
{
    protected static string $resource = RentalResource::class;

    protected function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['payments.bank', 'vehicle', 'driver.user']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Back to List'))
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => static::getResource()::getUrl('index')),
            EditAction::make(),
            Action::make('confirm')
                ->label('Confirm')
                ->color('warning')
                ->icon('heroicon-o-check')
                ->visible(fn () => $this->getRecord()->status === RentalStatus::Pending->value)
                ->requiresConfirmation()
                ->modalHeading('Confirm Rental')
                ->modalDescription('Are you sure you want to confirm this rental? This will move it from pending to confirmed status.')
                ->modalSubmitActionLabel('Yes, confirm')
                ->action(function () {
                    $this->getRecord()->update(['status' => RentalStatus::Confirmed->value]);
                    Notification::make()->title('Rental confirmed')->success()->send();
                }),
            Action::make('activate')
                ->label('Activate')
                ->color('info')
                ->icon('heroicon-o-play')
                ->visible(fn () => $this->getRecord()->status === RentalStatus::Confirmed->value)
                ->requiresConfirmation()
                ->modalHeading('Activate Rental')
                ->modalDescription('Are you sure you want to activate this rental? The rental period will begin.')
                ->modalSubmitActionLabel('Yes, activate')
                ->action(function () {
                    $this->getRecord()->update(['status' => RentalStatus::Active->value]);
                    Notification::make()->title('Rental activated')->success()->send();
                }),
            Action::make('complete')
                ->label('Complete')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->visible(fn () => $this->getRecord()->status === RentalStatus::Active->value)
                ->requiresConfirmation()
                ->modalHeading('Complete Rental')
                ->modalDescription('Are you sure you want to mark this rental as completed? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, complete')
                ->action(function () {
                    $hasProof = $this->getRecord()->payments()->whereNotNull('proof_file_path')->exists();

                    if (! $hasProof) {
                        Notification::make()
                            ->title('Cannot complete rental')
                            ->body('This rental has no payment proof uploaded. Please upload payment proof before completing.')
                            ->warning()
                            ->send();

                        return;
                    }

                    $this->getRecord()->update(['status' => RentalStatus::Completed->value]);
                    Notification::make()->title('Rental completed')->success()->send();
                }),
            Action::make('cancel')
                ->label('Cancel')
                ->color('danger')
                ->icon('heroicon-o-x-circle')
                ->visible(fn () => in_array($this->getRecord()->status, [
                    RentalStatus::Pending->value,
                    RentalStatus::Confirmed->value,
                ]))
                ->requiresConfirmation()
                ->modalHeading('Cancel Rental')
                ->modalDescription('Are you sure you want to cancel this rental?')
                ->modalSubmitActionLabel('Yes, cancel')
                ->action(function () {
                    $this->getRecord()->update(['status' => RentalStatus::Cancelled->value]);
                    Notification::make()->title('Rental cancelled')->danger()->send();
                }),
        ];
    }
}
