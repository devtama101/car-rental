<?php

namespace App\Enums;

enum ExpenseCategory: string
{
    case Maintenance = 'maintenance';
    case VehicleTax = 'vehicle_tax';
    case VehicleInsurance = 'vehicle_insurance';
    case Fuel = 'fuel';
    case Cleaning = 'cleaning';
    case SpareParts = 'spare_parts';

    case EmployeeSalary = 'employee_salary';
    case DriverWage = 'driver_wage';
    case Overtime = 'overtime';
    case Bonus = 'bonus';
    case THR = 'thr';

    case OfficeRent = 'office_rent';
    case Utilities = 'utilities';
    case Marketing = 'marketing';
    case Software = 'software';
    case Miscellaneous = 'miscellaneous';
}
