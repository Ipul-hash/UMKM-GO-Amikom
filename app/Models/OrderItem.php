<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'product_name_snapshot',
        'variant_snapshot',
        'sku_snapshot',
        'unit_price',
        'quantity',
        'discount_amount',
        'line_total',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'variant_snapshot' => 'array',
            'unit_price' => 'decimal:2',
            'quantity' => 'integer',
            'discount_amount' => 'decimal:2',
            'line_total' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
}
