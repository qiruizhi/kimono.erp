<?php

namespace App\Models;

use App\Enums\ComponentType;
use App\Enums\InventoryStatus;
use App\Models\Price\ComponentPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 */
class Component extends Model
{
    use HasFactory;

    protected $table = 'components';

    protected $fillable = [
        'name',
        'code',
        'price',
        'currency',
        'supplier_product_name',
        'supplier_code',
        'notes',
    ];

    protected $casts = [
        'type' => ComponentType::class,
        'status' => InventoryStatus::class,
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);  // This sets up the inverse of the relationship
    }

    public function componentPrices(): HasMany
    {
        return $this->hasMany(ComponentPrice::class);
    }
}
