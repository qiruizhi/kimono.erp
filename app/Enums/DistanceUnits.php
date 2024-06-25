<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum DistanceUnits: string implements HasLabel
{
    case Millimeters = 'mm';

    case Centimeters = 'cm';

    case Meters = 'm';

    case Inches = 'in';

    case Feet = 'ft';

    case Yards = 'yd';

    public function getLabel(): string
    {
        return match ($this) {
            self::Millimeters => __('Millimeters (mm)'),
            self::Centimeters => __('Centimeters (cm)'),
            self::Meters => __('Meters (m)'),
            self::Inches => __('Inches (in)'),
            self::Feet => __('Feet (ft)'),
            self::Yards => __('Yards (yd)'),
        };
    }
}
