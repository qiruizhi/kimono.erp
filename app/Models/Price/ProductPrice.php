<?php

namespace App\Models\Price;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $table = 'product_prices';

    protected $fillable = [
        'product_id',
        'price',
        'cost',
        'currency',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
