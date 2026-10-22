<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'payment_number',
        'kind',
        'checkout_number',
        'order_id',
        'parent_payment_id',
        'method',
        'provider',
        'provider_reference',
        'amount',
        'status',
        'reason',
        'requested_by',
        'reviewed_by',
        'reviewed_at',
        'paid_at',
        'expires_at',
        'provider_payload',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'reviewed_at' => 'datetime',
            'paid_at' => 'datetime',
            'expires_at' => 'datetime',
            'provider_payload' => 'array',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function parentPayment(): BelongsTo
    {
        return $this->belongsTo(Payment::class, 'parent_payment_id');
    }

    public function refunds(): HasMany
    {
        return $this->hasMany(Payment::class, 'parent_payment_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
