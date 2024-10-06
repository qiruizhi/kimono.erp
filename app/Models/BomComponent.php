<?php

namespace App\Models;

use App\Enums\ComponentType;
use App\Enums\DistanceUnit;
use App\Enums\VolumeUnit;
use App\Enums\WeightUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BomComponent extends Model
{
    protected $table = 'bom_components';

    protected $fillable = [
        'name',
        'type',
        'unit_of_measure',
        'quantity',
        'unit_cost',
        'compo_amount',
        'notes',
    ];

    public $incrementing = true;

    public $timestamps = true;

    protected $casts = [
        'type' => ComponentType::class,
        'unit_of_measure' => WeightUnit::class,DistanceUnit::class,VolumeUnit::class,
    ];

    public function bom(): BelongsTo
    {
        return $this->belongsTo(Bom::class, 'bom_id');
    }

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class,'component_id');
    }
}
