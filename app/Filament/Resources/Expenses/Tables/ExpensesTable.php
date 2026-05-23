<?php

namespace App\Filament\Resources\Expenses\Tables;

use App\Enums\ExpenseCategory;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;

class ExpensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date()
                    ->sortable(),

                TextColumn::make('category')
                    ->label(__('Category'))
                    ->badge()
                    ->color(fn (ExpenseCategory $state): string => match ($state) {
                        ExpenseCategory::Maintenance,
                        ExpenseCategory::VehicleTax,
                        ExpenseCategory::VehicleInsurance,
                        ExpenseCategory::Fuel,
                        ExpenseCategory::Cleaning,
                        ExpenseCategory::SpareParts => 'info',
                        ExpenseCategory::EmployeeSalary,
                        ExpenseCategory::DriverWage,
                        ExpenseCategory::Overtime,
                        ExpenseCategory::Bonus,
                        ExpenseCategory::THR => 'warning',
                        ExpenseCategory::OfficeRent,
                        ExpenseCategory::Utilities,
                        ExpenseCategory::Marketing,
                        ExpenseCategory::Software,
                        ExpenseCategory::Miscellaneous => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('amount')
                    ->label(__('Amount'))
                    ->money('idr')
                    ->sortable(),

                TextColumn::make('description')
                    ->label(__('Description'))
                    ->limit(40)
                    ->toggleable(),

                TextColumn::make('person.user.name')
                    ->label(__('Person'))
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('vehicle.name')
                    ->label(__('Vehicle'))
                    ->sortable()
                    ->toggleable(),

                ImageColumn::make('proof_file_path')
                    ->label(__('Proof'))
                    ->size(60)
                    ->url(function (?string $state): ?string {
                        if (! $state) {
                            return null;
                        }

                        return Storage::disk(config('filament.default_filesystem_disk'))->url($state);
                    }, shouldOpenInNewTab: true)
                    ->toggleable(),

                TextColumn::make('creator.name')
                    ->label(__('Recorded By'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label(__('Category'))
                    ->options(self::filterOptions()),
                SelectFilter::make('person_id')
                    ->label(__('Person'))
                    ->relationship('person.user', 'name'),
                SelectFilter::make('vehicle_id')
                    ->label(__('Vehicle'))
                    ->relationship('vehicle', 'name'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->requiresConfirmation(),
                ]),
            ])
            ->defaultSort('date', 'desc');
    }

    private static function filterOptions(): array
    {
        return [
            __('Vehicle') => [
                ExpenseCategory::Maintenance->value => __('Maintenance'),
                ExpenseCategory::VehicleTax->value => __('Vehicle Tax'),
                ExpenseCategory::VehicleInsurance->value => __('Insurance'),
                ExpenseCategory::Fuel->value => __('Fuel'),
                ExpenseCategory::Cleaning->value => __('Cleaning'),
                ExpenseCategory::SpareParts->value => __('Spare Parts'),
            ],
            __('Employee') => [
                ExpenseCategory::EmployeeSalary->value => __('Salary'),
                ExpenseCategory::DriverWage->value => __('Driver Wage'),
                ExpenseCategory::Overtime->value => __('Overtime'),
                ExpenseCategory::Bonus->value => __('Bonus'),
                ExpenseCategory::THR->value => __('THR'),
            ],
            __('Operational') => [
                ExpenseCategory::OfficeRent->value => __('Office Rent'),
                ExpenseCategory::Utilities->value => __('Utilities'),
                ExpenseCategory::Marketing->value => __('Marketing'),
                ExpenseCategory::Software->value => __('Software'),
                ExpenseCategory::Miscellaneous->value => __('Miscellaneous'),
            ],
        ];
    }
}
