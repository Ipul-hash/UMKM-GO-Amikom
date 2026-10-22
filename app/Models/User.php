<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, Notifiable;

    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password_hash',
        'avatar_url',
        'role',
        'status',
        'oauth_accounts',
        'addresses',
        'notification_prefs',
        'reset_token_hash',
        'reset_token_expires_at',
        'email_verified_at',
        'phone_verified_at',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password_hash',
        'remember_token',
        'reset_token_hash',
    ];

    /**
     * Get the password column name for authentication.
     */
    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'reset_token_expires_at' => 'datetime',
            'oauth_accounts' => 'array',
            'addresses' => 'array',
            'notification_prefs' => 'array',
        ];
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'owner_user_id');
    }

    public function storeMemberships(): HasMany
    {
        return $this->hasMany(StoreMember::class, 'user_id');
    }

    public function devices(): HasMany
    {
        return $this->hasMany(UserDevice::class, 'user_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_user_id');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class, 'user_id');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function productFeedbacks(): HasMany
    {
        return $this->hasMany(ProductFeedback::class, 'user_id');
    }

    public function isStoreOwner(?string $storeId = null): bool
    {
        return \App\Services\RbacService::isStoreOwner($this, $storeId);
    }

    public function hasPermission(string $permission, ?string $storeId = null): bool
    {
        return \App\Services\RbacService::hasPermission($this, $permission, $storeId);
    }

    public function hasAnyPermission(array $permissions, ?string $storeId = null): bool
    {
        return \App\Services\RbacService::hasAnyPermission($this, $permissions, $storeId);
    }

    public function getStorePermissions(?string $storeId = null): array
    {
        return \App\Services\RbacService::getPermissions($this, $storeId);
    }
}
