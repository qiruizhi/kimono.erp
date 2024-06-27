<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ShippingMethod: string implements HasLabel
{
    case Air = 'air';

    case Land = 'land';

    case Sea = 'sea';

    public function getLabel(): string
    {
        return match ($this) {
            self::Air => 'Air',
            self::Land => 'Land',
            self::Sea => 'Sea',
        };
    }
}
