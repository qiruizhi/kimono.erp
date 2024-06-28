<?php

namespace App\Models;

use App\Enums\WorkOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrder extends Model
{
    protected $table = 'work_orders';

    protected $fillable = [
        'number',
        'status',
        'start_date',
        'end_date',
        'produced_quantity',
        'defect_quantity',
        'verified_quantity',
        'notes',
    ];

    protected $casts = [
        'status' => WorkOrderStatus::class
    ];

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
