<?php

namespace App\Filament\Customer\Resources;

use App\Enums\RentalStatus;
use App\Filament\Customer\Resources\MyRentalResource\Pages\ListMyRentals;
use App\Filament\Customer\Resources\MyRentalResource\Pages\ViewMyRental;
use App\Models\Bank;
use App\Models\Rental;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Utilities\Get;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MyRentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'My Rentals';

    public static function getNavigationLabel(): string
    {
        return __('My Rentals');
    }

    protected static ?int $navigationSort = 10;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id())->with(['payments', 'vehicle', 'driver.user', 'user.person']);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Rental Details'))
                    ->schema([
                        TextEntry::make('created_at')
                            ->label(__('Booking Date'))
                            ->dateTime(),
                        TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'gray',
                                'confirmed' => 'warning',
                                'active' => 'info',
                                'completed' => 'success',
                                'cancelled' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => __($state)),
                        TextEntry::make('vehicle.name')
                            ->label(__('Vehicle'))
                            ->placeholder('-'),
                        TextEntry::make('start_date')
                            ->label(__('Start Date'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('end_date')
                            ->label(__('End Date'))
                            ->dateTime()
                            ->placeholder('-'),
                        TextEntry::make('total_amount')
                            ->money('idr'),
                        TextEntry::make('delivery_method')
                            ->label(__('Delivery Method'))
                            ->placeholder('-'),
                        TextEntry::make('delivery_address')
                            ->label(__('Delivery Address'))
                            ->placeholder('-'),
                    ])
                    ->columns(2),
                Section::make(__('Payment'))
                    ->schema([
                        RepeatableEntry::make('payments')
                            ->hiddenLabel()
                            ->schema([
                                TextEntry::make('method')
                                    ->label(__('Method'))
                                    ->formatStateUsing(fn (string $state): string => __($state)),
                                TextEntry::make('bank.name')
                                    ->label(__('Bank'))
                                    ->placeholder('-'),
                                TextEntry::make('amount')
                                    ->label(__('Amount'))
                                    ->money('idr'),
                                TextEntry::make('date')
                                    ->label(__('Payment Date'))
                                    ->date(),
                                ImageEntry::make('proof_file_path')
                                    ->label(__('Payment Proof'))
                                    ->height(120)
                                    ->url(function (?string $state): ?string {
                                        if (! $state) {
                                            return null;
                                        }

                                        return route('file.view', ['path' => $state]);
                                    }, shouldOpenInNewTab: true)
                                    ->visible(fn ($record): bool => filled($record->proof_file_path)),
                            ])
                            ->columns(3),
                    ]),
                Section::make(__('Your Documents'))
                    ->schema([
                        ImageEntry::make('user.person.id_file_path')
                            ->label(__('ID Document'))
                            ->height(200)
                            ->url(function (?string $state): ?string {
                                if (! $state) {
                                    return null;
                                }

                                return route('file.view', ['path' => $state]);
                            }, shouldOpenInNewTab: true)
                            ->visible(fn ($record): bool => filled($record->user?->person?->id_file_path)),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label(__('Booking Date'))
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('vehicle.name')
                    ->label(__('Vehicle'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total_amount')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'confirmed' => 'warning',
                        'active' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => __($state))
                    ->sortable(),
            ])
            ->recordActions([
                Action::make('cancel')
                    ->label(__('Cancel Booking'))
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Rental $record) => $record->status === RentalStatus::Pending->value)
                    ->requiresConfirmation()
                    ->modalHeading(__('Cancel Booking'))
                    ->modalDescription(__('Are you sure you want to cancel this booking? This action cannot be undone.'))
                    ->modalSubmitActionLabel(__('Yes, cancel'))
                    ->action(function (Rental $record): void {
                        $record->update(['status' => RentalStatus::Cancelled->value]);

                        Notification::make()
                            ->title(__('Booking cancelled'))
                            ->danger()
                            ->send();
                    }),
                Action::make('uploadPayment')
                    ->label(__('Upload Payment Proof'))
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->visible(function (Rental $record): bool {
                        $payment = $record->payments->first();

                        return $payment && ! $payment->proof_file_path;
                    })
                    ->form([
                        TextInput::make('amount')
                            ->label(__('Amount'))
                            ->numeric()
                            ->required()
                            ->default(fn (Rental $record) => $record->total_amount),
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
                    ->action(function (array $data, Rental $record): void {
                        $payment = $record->payments->first();
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
            ])
            ->filters([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMyRentals::route('/'),
            'view' => ViewMyRental::route('/{record}'),
        ];
    }
}
