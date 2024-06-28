<?php

namespace App\Models;

use App\Enums\SalesOrderStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static find(mixed $param)
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class SalesOrder extends Model
{
    protected $table = 'sales_orders';

    protected $fillable = [
        'number',
        'delivery_date',
        'status',
        'subtotal',
        'shipping_method',
        'shipping_price',
        'margin',
        'tax',
        'total_amount',
        'currency',
        'notes',
    ];

    protected $casts = [
        'status' => SalesOrderStatus::class,
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesOrderProducts(): HasMany
    {
        return $this->hasMany(SalesOrderProducts::class);
    }
}
