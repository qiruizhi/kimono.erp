<?php

namespace App\Models;

use App\Enums\InventoryStatus;
use App\Enums\ProductCategory;
use App\Models\Price\ProductPrice;
//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $id
 * @property mixed $name
 * @property mixed $price
 * @method static get()
 * @method static find($state)
 */
class Product extends Model
{
//    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'code',
        'category',
        'price',
        'cost',
        'currency',
        'status',
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

    public function salesOrderProducts(): HasMany
    {
        return $this->hasMany(SalesOrderProducts::class);
    }
}
