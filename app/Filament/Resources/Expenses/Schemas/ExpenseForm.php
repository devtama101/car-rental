<?php

namespace App\Filament\Resources\Expenses\Schemas;

use App\Enums\ExpenseCategory;
use App\Enums\PersonType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExpenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->label(__('Category'))
                    ->options(self::categoryOptions())
                    ->native(false)
                    ->required()
                    ->live(),

                TextInput::make('amount')
                    ->label(__('Amount'))
                    ->numeric()
                    ->required(),

                DatePicker::make('date')
                    ->label(__('Date'))
                    ->default(now())
                    ->required(),

                Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(2),

                Select::make('person_id')
                    ->label(__('Person'))
                    ->relationship(
                        name: 'person',
                        titleAttribute: 'id',
                        modifyQueryUsing: fn ($query) => $query->whereIn('type', [
                            PersonType::Admin->value,
                            PersonType::Employee->value,
                            PersonType::Driver->value,
                        ]),
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name)
                    ->preload()
                    ->visible(fn ($get) => in_array($get('category'), [
                        ExpenseCategory::EmployeeSalary->value,
                        ExpenseCategory::DriverWage->value,
                        ExpenseCategory::Overtime->value,
                        ExpenseCategory::Bonus->value,
                        ExpenseCategory::THR->value,
                    ])),

                Select::make('vehicle_id')
                    ->label(__('Vehicle'))
                    ->relationship('vehicle', 'name')
                    ->searchable()
                    ->preload()
                    ->visible(fn ($get) => in_array($get('category'), [
                        ExpenseCategory::Maintenance->value,
                        ExpenseCategory::VehicleTax->value,
                        ExpenseCategory::VehicleInsurance->value,
                        ExpenseCategory::Fuel->value,
                        ExpenseCategory::Cleaning->value,
                        ExpenseCategory::SpareParts->value,
                    ])),

                FileUpload::make('proof_file_path')
                    ->label(__('Proof'))
                    ->image()
                    ->directory('expense-proofs'),
            ]);
    }

    private static function categoryOptions(): array
    {
        return [
            __('Vehicle') => [
                ExpenseCategory::Maintenance->value => __('Maintenance & Repairs'),
                ExpenseCategory::VehicleTax->value => __('Vehicle Tax'),
                ExpenseCategory::VehicleInsurance->value => __('Vehicle Insurance'),
                ExpenseCategory::Fuel->value => __('Fuel'),
                ExpenseCategory::Cleaning->value => __('Cleaning & Detailing'),
                ExpenseCategory::SpareParts->value => __('Spare Parts'),
            ],
            __('Employee') => [
                ExpenseCategory::EmployeeSalary->value => __('Employee Salary'),
                ExpenseCategory::DriverWage->value => __('Driver Wage'),
                ExpenseCategory::Overtime->value => __('Overtime'),
                ExpenseCategory::Bonus->value => __('Bonus'),
                ExpenseCategory::THR->value => __('THR'),
            ],
            __('Operational') => [
                ExpenseCategory::OfficeRent->value => __('Office Rent'),
                ExpenseCategory::Utilities->value => __('Utilities'),
                ExpenseCategory::Marketing->value => __('Marketing'),
                ExpenseCategory::Software->value => __('Software & Tools'),
                ExpenseCategory::Miscellaneous->value => __('Miscellaneous'),
            ],
        ];
    }
}
