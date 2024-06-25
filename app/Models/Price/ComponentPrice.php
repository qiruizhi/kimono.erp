<?php

namespace App\Models\Price;

use App\Models\Component;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComponentPrice extends Model
{
    use HasFactory;

    protected $table = 'component_prices';

    protected $fillable = [
        'component_id',
        'price',
        'currency',
        'notes',
    ];

    public function component(): BelongsTo
    {
        return $this->belongsTo(Component::class);
    }
}
