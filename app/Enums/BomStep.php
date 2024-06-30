<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BomStep: string implements HasLabel
{
    case Step1 = 'step-1';

    case Step2 = 'step-2';

    case Step3 = 'step-3';

    case Step4 = 'step-4';

    case Step5 = 'step-5';

    case Step6 = 'step-6';

    case Step7 = 'step-7';

    case Step8 = 'step-8';

    public function getLabel(): string
    {
        return match ($this) {
            self::Step1 => __('Step 1'),
            self::Step2 => __('Step 2'),
            self::Step3 => __('Step 3'),
            self::Step4 => __('Step 4'),
            self::Step5 => __('Step 5'),
            self::Step6 => __('Step 6'),
            self::Step7 => __('Step 7'),
            self::Step8 => __('Step 8'),
        };
    }
}
