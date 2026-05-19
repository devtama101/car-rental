<?php

namespace App\Filament\Customer\Resources;

use App\Enums\PaymentStatus;
use App\Enums\RentalStatus;
use App\Filament\Customer\Resources\MyRentalResource\Pages\ListMyRentals;
use App\Filament\Customer\Resources\MyRentalResource\Pages\ViewMyRental;
use App\Models\Rental;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
        return parent::getEloquentQuery()->where('user_id', auth()->id())->with('payments');
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
                Section::make(__('Payment Status'))
                    ->schema([
                        TextEntry::make('payment_status')
                            ->label(__('Payment Status'))
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'unpaid' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'paid' => __('Paid'),
                                'pending' => __('Verifying'),
                                'unpaid' => __('Unpaid'),
                                default => $state,
                            }),
                        RepeatableEntry::make('payments')
                            ->label(__('Payment History'))
                            ->schema([
                                TextEntry::make('method')
                                    ->label(__('Method'))
                                    ->formatStateUsing(fn (string $state): string => __($state)),
                                TextEntry::make('amount')
                                    ->label(__('Amount'))
                                    ->money('idr'),
                                TextEntry::make('status')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'paid' => 'success',
                                        'pending' => 'warning',
                                        'refunded' => 'danger',
                                        default => 'gray',
                                    })
                                    ->formatStateUsing(fn (string $state): string => __($state)),
                                TextEntry::make('date')
                                    ->label(__('Payment Date'))
                                    ->date(),
                                ImageEntry::make('proof_file_path')
                                    ->label(__('Payment Proof'))
                                    ->visible(fn ($record): bool => filled($record->proof_file_path)),
                            ])
                            ->columns(3),
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
                TextColumn::make('total_amount')
                    ->money('idr')
                    ->sortable(),
                TextColumn::make('payment_status')
                    ->label(__('Payment'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'unpaid' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => __('Paid'),
                        'pending' => __('Verifying'),
                        'unpaid' => __('Unpaid'),
                        default => $state,
                    })
                    ->sortable(false),
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
                    ->label(__('Upload Payment'))
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->visible(fn (Rental $record) => $record->payment_status === 'unpaid')
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
                            ->default('transfer'),
                        FileUpload::make('proof_file_path')
                            ->label(__('Payment Proof'))
                            ->image()
                            ->required()
                            ->directory('payment-proofs'),
                    ])
                    ->action(function (array $data, Rental $record): void {
                        $record->payments()->create([
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
