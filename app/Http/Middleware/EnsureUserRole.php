<?php

namespace App\Http\Middleware;

use App\Services\RbacService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

        $storeId = $request->input('store_id') ?: $request->query('store_id');
        $storeId = RbacService::resolveStoreId($storeId);

        if (RbacService::isStoreOwner($user, $storeId)) {
            return $next($request);
        }

        $membership = RbacService::getUserMembership($user, $storeId);
        $userRole = $membership?->role ?? $user->role;

        if (!in_array($userRole, $roles, true)) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak. Peran Anda (' . $userRole . ') tidak memiliki izin untuk fitur ini.',
                    'allowed_roles' => $roles,
                ], 403);
            }

            return response()->view('errors.403', [
                'requiredRoles' => $roles,
                'user' => $user,
            ], 403);
        }

        return $next($request);
    }
}
