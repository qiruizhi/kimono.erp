<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum InventoryStatus: string implements HasColor, HasIcon, HasLabel
{
    case Active = 'active';

    case Archived = 'archived';

    case Discontinued = 'discontinued';

    case OutOfStock = 'out of stock';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active => __('Active'),
            self::Archived => __('Archived'),
            self::Discontinued => __('Discontinued'),
            self::OutOfStock => __('Out of Stock'),
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active => 'success',
            self::Archived => 'info',
            self::Discontinued => 'danger',
            self::OutOfStock => 'warning',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Active => 'heroicon-m-pencil',
            self::Archived => 'heroicon-m-x-mark',
            self::Discontinued => 'heroicon-m-check-badge',
            self::OutOfStock => 'heroicon-m-x-circle',
        };
    }
}
