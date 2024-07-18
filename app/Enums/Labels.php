<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum Labels: string implements HasLabel
{
    case Fragile = 'fragile';

    case Stackable = 'stackable';

    case Hazardous = 'hazardous';

    public function getLabel(): string
    {
        return match ($this) {
            self::Fragile => __('fragile'),
            self::Stackable => __('stackable'),
            self::Hazardous => __('hazardous'),
        };
    }
}
