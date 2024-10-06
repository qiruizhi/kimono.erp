<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WeightUnit: string implements HasLabel
{
    case Milligrams = 'mg';

    case Grams = 'g';

    case Kilograms = 'kg';

    case Tonnes = 't';

    case Pounds = 'lb';

    case Ounces = 'oz';

    public function getLabel(): string
    {
        return match ($this) {
          self::Milligrams => __('Milligrams (mg)'),
          self::Grams => __('Grams (g)'),
          self::Kilograms => __('Kilograms (kg)'),
          self::Tonnes => __('Tonnes (t)'),
          self::Pounds => __('Pounds (lb)'),
          self::Ounces => __('Ounces (oz)'),
        };
    }
}
