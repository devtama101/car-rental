<?php

namespace App\Enums;

enum IdType: string
{
    case Ktp = 'ktp';
    case Sim = 'sim';
    case Paspor = 'paspor';
    case KartuPelajar = 'kartu pelajar';
}
