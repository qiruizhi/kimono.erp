<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProductCategory: string implements HasLabel
{
    case FinishedGoods = 'finished goods';

    case WorkInProgress = 'work in progress';

    case Clearance = 'clearance';

    public function getLabel(): string
    {
        return match ($this) {
            self::FinishedGoods => 'Finished Goods',
            self::WorkInProgress => 'Work In Progress',
            self::Clearance => 'Clearance',
        };
    }
}
