<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialEntry extends Model
{
    use HasFactory, HasUuids;

    public $timestamps = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'store_id',
        'entry_date',
        'direction',
        'entry_type',
        'category',
        'amount',
        'description',
        'attachment_url',
        'source_table',
        'source_id',
        'created_by',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'datetime',
            'amount' => 'decimal:2',
            'created_at' => 'datetime',
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
