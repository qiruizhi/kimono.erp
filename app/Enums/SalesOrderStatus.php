<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum SalesOrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case Cancelled = 'cancelled';

    case Delivered = 'delivered';

    case New = 'new';

    case Processing = 'processing';

    case Shipped = 'shipped';

    case Returned = 'returned';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Cancelled => 'danger',
            self::Delivered, self::Shipped => 'success',
            self::New => 'info',
            self::Processing => 'warning',
            self::Returned => 'primary',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Cancelled => 'heroicon-m-x-circle',
            self::Delivered => 'heroicon-m-check-badge',
            self::New => 'heroicon-m-sparkles',
            self::Processing => 'heroicon-m-arrow-path',
            self::Returned => 'heroicon-m-home-modern',
            self::Shipped => 'heroicon-m-truck',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Cancelled => __('Cancelled'),
            self::Delivered => __('Delivered'),
            self::New => __('New'),
            self::Processing => __('Processing'),
            self::Shipped => __('Shipped'),
            self::Returned => __('Returned'),
        };
    }
}
