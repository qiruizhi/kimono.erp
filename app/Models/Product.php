<?php

namespace App\Models;

use App\Enums\InventoryStatus;
use App\Enums\ProductCategory;
use App\Models\Price\ProductPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'code',
        'price',
        'cost',
        'currency',
        'lead_time',
        'notes',
    ];

    protected $casts = [
        'status' => InventoryStatus::class,
        'category' => ProductCategory::class,
    ];

    public function productPrices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }
}
