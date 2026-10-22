<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'checkout_number',
        'order_number',
        'buyer_user_id',
        'store_id',
        'status',
        'shipping_address',
        'subtotal_amount',
        'shipping_amount',
        'discount_amount',
        'service_fee',
        'total_amount',
        'buyer_note',
        'applied_promotions',
        'courier',
        'service_name',
        'tracking_number',
        'shipment_status',
        'shipped_at',
        'delivered_at',
        'tracking_events',
        'status_history',
        'placed_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'shipping_address' => 'array',
            'applied_promotions' => 'array',
            'tracking_events' => 'array',
            'status_history' => 'array',
            'subtotal_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
            'placed_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_user_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'order_id');
    }
}
