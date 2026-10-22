<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('permission', function (string $permission) {
            $user = auth()->user();
            return $user ? $user->hasPermission($permission) : true;
        });

        Blade::if('hasrole', function (string|array $roles) {
            $user = auth()->user();
            if (!$user) {
                return true;
            }
            $roles = is_array($roles) ? $roles : explode(',', $roles);
            return in_array($user->role, $roles, true);
        });
    }
}
