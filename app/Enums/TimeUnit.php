<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TimeUnit: string implements HasLabel
{
    case Seconds = 's';

    case Minutes = 'min';

    case Hours = 'h';

    case Days = 'd';

    case Weeks = 'wk';

    case Months = 'mo';

    case Years = 'yr';

    public function getLabel(): string
    {
        return match ($this) {
            self::Seconds => __('Seconds'),
            self::Minutes => __('Minutes'),
            self::Hours => __('Hours'),
            self::Days => __('Days'),
            self::Weeks => __('Weeks'),
            self::Months => __('Months'),
            self::Years => __('Years'),
        };
    }
}
