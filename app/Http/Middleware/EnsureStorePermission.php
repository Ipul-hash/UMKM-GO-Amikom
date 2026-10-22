<?php

namespace App\Http\Middleware;

use App\Models\Store;
use App\Models\User;
use App\Services\RbacService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureStorePermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (!$user && ($request->is('admin/*') || $request->is('admin'))) {
            return redirect()->guest(route('login'));
        }

        $storeId = $request->input('store_id') ?: $request->query('store_id');
        $storeId = RbacService::resolveStoreId($storeId);

        if (!$user) {
            $store = $storeId ? Store::find($storeId) : Store::first();
            $user = $store?->owner ?? User::where('role', 'seller_owner')->first() ?? User::first();
        }

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

        if (RbacService::isStoreOwner($user, $storeId)) {
            return $next($request);
        }

        $membership = RbacService::getUserMembership($user, $storeId);
        if (!$membership) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda bukan anggota toko ini.',
                ], 403);
            }

            return response()->view('errors.403', [
                'requiredPermissions' => $permissions,
                'user' => $user,
                'errorMessage' => 'Akun Anda bukan staf atau anggota toko ini.',
            ], 403);
        }

        if (empty($permissions)) {
            return $next($request);
        }

        $hasAccess = false;
        foreach ($permissions as $perm) {
            if (RbacService::hasPermission($user, $perm, $storeId)) {
                $hasAccess = true;
                break;
            }
        }

        if (!$hasAccess) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Anda tidak memiliki izin untuk fitur ini (' . implode(', ', $permissions) . ').',
                    'required_permissions' => $permissions,
                ], 403);
            }

            return response()->view('errors.403', [
                'requiredPermissions' => $permissions,
                'user' => $user,
            ], 403);
        }

        return $next($request);
    }
}
