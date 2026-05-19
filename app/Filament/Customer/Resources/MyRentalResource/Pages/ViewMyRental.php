<?php

namespace App\Filament\Customer\Resources\MyRentalResource\Pages;

use App\Enums\PaymentStatus;
use App\Filament\Customer\Resources\MyRentalResource;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewMyRental extends ViewRecord
{
    protected static string $resource = MyRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('uploadPayment')
                ->label(__('Upload Payment Proof'))
                ->icon('heroicon-o-arrow-up-on-square')
                ->visible(fn () => $this->getRecord()->payment_status === 'unpaid')
                ->form([
                    TextInput::make('amount')
                        ->numeric()
                        ->required()
                        ->default($this->getRecord()->total_amount),
                    Select::make('method')
                        ->options([
                            'cash' => __('cash'),
                            'transfer' => __('transfer'),
                        ])
                        ->required()
                        ->default('transfer'),
                    FileUpload::make('proof_file_path')
                        ->label(__('Payment Proof'))
                        ->image()
                        ->required()
                        ->directory('payment-proofs'),
                ])
                ->action(function (array $data): void {
                    $this->getRecord()->payments()->create([
                        'amount' => $data['amount'],
                        'method' => $data['method'],
                        'proof_file_path' => $data['proof_file_path'],
                        'status' => PaymentStatus::Pending->value,
                        'date' => now()->toDateString(),
                    ]);

                    Notification::make()
                        ->title(__('Payment proof uploaded successfully'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
