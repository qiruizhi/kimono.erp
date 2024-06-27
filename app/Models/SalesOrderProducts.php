<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesOrderProducts extends Model
{
    protected $table = 'sales_order_products';

    protected $fillable = [
        'required_quantity',
        'price',
    ];

    public $incrementing = true;

    public $timestamps = true;

    public function order(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class, 'sales_order_id');
    }
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
