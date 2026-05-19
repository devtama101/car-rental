<?php

namespace App\Enums;

enum RentalItemStatus: string
{
    case Rented = 'rented';
    case Returned = 'returned';
}
