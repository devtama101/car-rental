<?php

namespace App\Enums;

enum RentalType: string
{
    case SelfDrive = 'self-drive';
    case WithDriver = 'with-driver';
    case Both = 'both';
}
