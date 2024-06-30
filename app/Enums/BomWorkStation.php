<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum BomWorkStation: string implements HasLabel
{
    case CuttingStation = 'cutting_station';

    case WeldingStation = 'welding_station';

    case AssemblyStation = 'assembly_station';

    case InspectionArea = 'inspection_area';

    case PaintingArea = 'painting_area';

    case PackagingArea = 'packaging_area';

    case MachiningArea = 'machining_area';

    case GrindingArea = 'grinding_area';

    case DrillingArea = 'drilling_area';

    case ShippingArea = 'shipping_area';

    public function getLabel(): string
    {
        return match ($this) {
            self::CuttingStation => __('Cutting Station'),
            self::WeldingStation => __('Welding Station'),
            self::AssemblyStation => __('Assembly Station'),
            self::InspectionArea => __('Inspection Area'),
            self::PaintingArea => __('Painting Area'),
            self::PackagingArea => __('Packaging Area'),
            self::MachiningArea => __('Machining Area'),
            self::GrindingArea => __('Grinding Area'),
            self::DrillingArea => __('Drilling Area'),
            self::ShippingArea => __('Shipping Area'),
        };
    }
}
