<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFeedback extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'product_feedback';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'product_id',
        'user_id',
        'type',
        'order_item_id',
        'rating',
        'reason',
        'content',
        'reply',
        'replied_by',
        'replied_at',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'replied_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    public function replier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'replied_by');
    }
}
