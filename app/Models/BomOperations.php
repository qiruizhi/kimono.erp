<?php

namespace App\Models;

use App\Enums\BomOperation;
use App\Enums\BomStep;
use App\Enums\BomWorkStation;
use App\Enums\TimeUnit;
use Illuminate\Database\Eloquent\Model;

class BomOperations extends Model
{
    protected $table = 'bom_operations';

    protected $fillable = [
        'step',
        'operation',
        'workstation',
        'op_time',
        'unit_time',
        'fixed_time',
        'op_unit_cost',
        'op_amount',
        'notes',
    ];

    public $incrementing = true;

    public $timestamps = true;

    protected $casts = [
        'step' => BomStep::class,
        'operation' => BomOperation::class,
        'workstation' => BomWorkStation::class,
        'fixed_time' => 'boolean',
        'unit_time' => TimeUnit::class,
    ];
}
