<?php

declare(strict_types=1);

namespace App\Enum;

enum CapacityType: string
{
    case Standing = 'standing';
    case Seat = 'seat';
    case VIPSeat = 'vip_seat';
    case Premium = 'premium';
    case Exclusive = 'exclusive';
}
