<?php

namespace App\Filament\Customer\Resources\MyRentalResource\Pages;

use App\Filament\Customer\Resources\MyRentalResource;
use App\Models\Bank;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Utilities\Get;

class ViewMyRental extends ViewRecord
{
    protected static string $resource = MyRentalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label(__('Back to List'))
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(fn () => static::getResource()::getUrl('index')),
            Action::make('uploadPayment')
                ->label(__('Upload Payment Proof'))
                ->icon('heroicon-o-arrow-up-on-square')
                ->visible(function (): bool {
                    $payment = $this->getRecord()->payments->first();

                    return $payment && ! $payment->proof_file_path;
                })
                ->form([
                    TextInput::make('amount')
                        ->label(__('Amount'))
                        ->numeric()
                        ->required()
                        ->default($this->getRecord()->total_amount),
                    Select::make('method')
                        ->label(__('Method'))
                        ->options([
                            'cash' => __('Cash'),
                            'transfer' => __('Bank Transfer'),
                        ])
                        ->required()
                        ->live()
                        ->default('transfer'),
                    Select::make('bank_id')
                        ->label(__('Bank'))
                        ->options(function () {
                            return Bank::all()->mapWithKeys(fn (Bank $bank) => [
                                $bank->id => "{$bank->name} ({$bank->code}) — {$bank->number} ({$bank->account_holder})",
                            ]);
                        })
                        ->searchable()
                        ->visible(fn (Get $get): bool => $get('method') === 'transfer')
                        ->nullable(),
                    FileUpload::make('proof_file_path')
                        ->label(__('Payment Proof'))
                        ->image()
                        ->required()
                        ->directory('payment-proofs'),
                ])
                ->action(function (array $data): void {
                    $payment = $this->getRecord()->payments->first();
                    $payment->update([
                        'amount' => $data['amount'],
                        'method' => $data['method'],
                        'bank_id' => $data['bank_id'] ?? null,
                        'proof_file_path' => $data['proof_file_path'],

                    ]);

                    Notification::make()
                        ->title(__('Payment proof uploaded successfully'))
                        ->success()
                        ->send();
                }),
        ];
    }
}
