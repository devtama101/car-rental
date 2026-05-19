<?php

namespace App\Enums;

enum PersonType: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Employee = 'employee';
    case Customer = 'customer';
    case Driver = 'driver';
}
