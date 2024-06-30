<?php

namespace App\Models;

use App\Enums\ComponentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $created_at
 * @property mixed $updated_at
 */
class Bom extends Model
{
    protected $table = 'boms';

    protected $fillable = [
        'number',
        'operating_cost',
        'component_cost',
        'gross_cost',
        'margin',
        'total_cost',
        'notes',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bomComponents(): HasMany
    {
        return $this->hasMany(BomComponents::class);
    }

    public function bomOperations(): HasMany
    {
        return $this->hasMany(BomOperations::class);
    }
}
