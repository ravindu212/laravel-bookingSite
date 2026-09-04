<?php

namespace App\Models;

use Database\Factories\HotelInventoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelInventory extends Model
{
    /** @use HasFactory<HotelInventoryFactory> */
    use HasFactory;

    protected $fillable = [
        'category',
        'menu_type',
        'name',
        'description',
        'price',
        'people_count',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'people_count' => 'integer',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }
}
