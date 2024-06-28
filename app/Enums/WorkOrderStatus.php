<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum WorkOrderStatus: string implements HasColor, HasIcon, HasLabel
{
    case Pending = 'pending';

    case Assigned = 'assigned';

    case InProgress = 'in-progress';

    case OnHold = 'on-hold';

    case Completed = 'completed';

    case Cancelled = 'cancelled';

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Assigned, self::Pending, self::InProgress => 'info',
            self::Completed => 'success',
            self::Cancelled => 'danger',
            self::OnHold => 'warning',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Completed => 'heroicon-m-x-circle',
            self::Assigned => 'heroicon-m-check-badge',
            self::Pending => 'heroicon-m-sparkles',
            self::InProgress => 'heroicon-m-arrow-path',
            self::Cancelled => 'heroicon-m-home-modern',
            self::OnHold => 'heroicon-m-truck',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Assigned => __('Assigned'),
            self::Completed => __('Completed'),
            self::Cancelled => __('Cancelled'),
            self::InProgress => __('In Progress'),
            self::OnHold => __('On Hold'),
            self::Pending => __('Pending'),
        };
    }
}
