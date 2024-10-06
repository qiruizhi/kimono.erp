<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum VolumeUnit: string implements HasLabel
{
    case Milliliters = 'ml';

    case Liters = 'l';

    case Gallons = 'gal';

    case FluidOunces = 'fl oz';

    public function getLabel(): string
    {
        return match ($this) {
          self::Milliliters => 'Milliliters (ml)',
          self::Liters => 'Liters (l)',
          self::Gallons => 'Gallons (gal)',
          self::FluidOunces => 'Fluid Ounces (fl oz)',
        };
    }
}
