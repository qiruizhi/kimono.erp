<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum InvTransactionType: string implements HasLabel
{
    case Add = 'add';

    case Subtract = 'subtract';

    public function getLabel(): string
    {
        return match ($this) {
            self::Add => __('Add'),
            self::Subtract => __('Subtract'),
        };
    }
}
