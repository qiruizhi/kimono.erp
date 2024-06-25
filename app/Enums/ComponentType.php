<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ComponentType: string implements HasLabel
{
    case Rubber = 'rubber';

    case Spring = 'spring';

    case Chemical = 'chemical';

    case Mold = 'mold';

    public function getLabel(): string
    {
        return match ($this) {
            self::Rubber => __('Rubber'),
            self::Spring => __('Spring'),
            self::Chemical => __('Chemical'),
            self::Mold => __('Mold'),
        };
    }
}
