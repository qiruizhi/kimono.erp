<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BomOperation: string implements HasLabel
{
    case Cutting = 'cutting';

    case Welding = 'welding';

    case Assembling = 'assembling';

    case Painting = 'painting';

    case Inspecting = 'inspecting';

    case Drilling = 'drilling';

    case Machining = 'machining';

    case Grinding = 'grinding';

    case Testing = 'testing';

    public function getLabel(): string
    {
        return match ($this) {
            self::Cutting => __('Cutting'),
            self::Welding => __('Welding'),
            self::Assembling => __('Assembling'),
            self::Painting => __('Painting'),
            self::Inspecting => __('Inspecting'),
            self::Drilling => __('Drilling'),
            self::Machining => __('Machining'),
            self::Grinding => __('Grinding'),
            self::Testing => __('Testing'),
        };
    }
}
