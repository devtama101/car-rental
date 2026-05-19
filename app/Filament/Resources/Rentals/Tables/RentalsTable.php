<?php

namespace App\Filament\Resources\Rentals\Tables;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RentalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('user.name')
                    ->label(__('Customer'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->label(__('Total Amount'))
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'warning',
                        'active' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('delivery_method')
                    ->label(__('Delivery'))
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('confirm')
                    ->label('Confirm')
                    ->color('warning')
                    ->icon('heroicon-o-check')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() || auth()->user()?->isEmployee())
                    ->visible(fn ($record) => $record->status === RentalStatus::Pending->value)
                    ->requiresConfirmation()
                    ->modalHeading('Confirm Rental')
                    ->modalDescription('Are you sure you want to confirm this rental? This will move it from pending to confirmed status.')
                    ->modalSubmitActionLabel('Yes, confirm')
                    ->action(function ($record) {
                        $record->update(['status' => RentalStatus::Confirmed->value]);
                        Notification::make()->title('Rental confirmed')->success()->send();
                    }),
                Action::make('activate')
                    ->label('Activate')
                    ->color('info')
                    ->icon('heroicon-o-play')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() || auth()->user()?->isEmployee())
                    ->visible(fn ($record) => $record->status === RentalStatus::Confirmed->value)
                    ->requiresConfirmation()
                    ->modalHeading('Activate Rental')
                    ->modalDescription('Are you sure you want to activate this rental? The rental period will begin.')
                    ->modalSubmitActionLabel('Yes, activate')
                    ->action(function ($record) {
                        $record->update(['status' => RentalStatus::Active->value]);
                        Notification::make()->title('Rental activated')->success()->send();
                    }),
                Action::make('complete')
                    ->label('Complete')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() || auth()->user()?->isEmployee())
                    ->visible(fn ($record) => $record->status === RentalStatus::Active->value)
                    ->requiresConfirmation()
                    ->modalHeading('Complete Rental')
                    ->modalDescription('Are you sure you want to mark this rental as completed? This action cannot be undone.')
                    ->modalSubmitActionLabel('Yes, complete')
                    ->action(function ($record) {
                        $hasPaid = $record->payments()->where('status', PaymentStatus::Paid->value)->exists();

                        if (! $hasPaid) {
                            Notification::make()
                                ->title('Cannot complete rental')
                                ->body('This rental has no paid payment. Please verify payment before completing.')
                                ->warning()
                                ->send();

                            return;
                        }

                        $record->update(['status' => RentalStatus::Completed->value]);
                        Notification::make()->title('Rental completed')->success()->send();
                    }),
                Action::make('cancel')
                    ->label('Cancel')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record) => auth()->user()?->isAdmin() || auth()->user()?->isEmployee())
                    ->visible(fn ($record) => in_array($record->status, [
                        RentalStatus::Pending->value,
                        RentalStatus::Confirmed->value,
                    ]))
                    ->requiresConfirmation()
                    ->modalHeading('Cancel Rental')
                    ->modalDescription('Are you sure you want to cancel this rental?')
                    ->modalSubmitActionLabel('Yes, cancel')
                    ->action(function ($record) {
                        $record->update(['status' => RentalStatus::Cancelled->value]);
                        Notification::make()->title('Rental cancelled')->danger()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ]);
    }
}
