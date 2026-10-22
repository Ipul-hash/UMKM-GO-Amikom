<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'owner_user_id',
        'name',
        'slug',
        'description',
        'logo_url',
        'banner_url',
        'phone',
        'email',
        'business_type',
        'status',
        'address_line',
        'province',
        'city',
        'district',
        'postal_code',
        'latitude',
        'longitude',
        'opening_hours',
        'settings',
        'verification_documents',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'opening_hours' => 'array',
            'settings' => 'array',
            'verification_documents' => 'array',
            'verified_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(StoreMember::class, 'store_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    public function posShifts(): HasMany
    {
        return $this->hasMany(PosShift::class, 'store_id');
    }

    public function posSales(): HasMany
    {
        return $this->hasMany(PosSale::class, 'store_id');
    }

    public function financialEntries(): HasMany
    {
        return $this->hasMany(FinancialEntry::class, 'store_id');
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'store_id');
    }
}
