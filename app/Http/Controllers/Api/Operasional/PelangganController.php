<?php

namespace App\Http\Controllers\Api\Operasional;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::whereIn('role', ['customer', 'buyer'])
            ->withCount(['orders as total_orders' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }])
            ->withSum(['orders as total_spent' => function ($q) {
                $q->where('status', '!=', 'cancelled');
            }], 'total_amount');

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $allCustomers = (clone $query)->get();
        $startOfMonth = now()->startOfMonth();

        $vipCount = $allCustomers->filter(function ($cust) {
            $spent = (float) ($cust->total_spent ?? 0);
            return $spent >= 1000000;
        })->count();

        $metrics = [
            'total_customers' => $allCustomers->count(),
            'new_this_month' => $allCustomers->where('created_at', '>=', $startOfMonth)->count(),
            'vip_count' => $vipCount,
        ];

        $filterTier = $request->query('tier');
        if ($filterTier) {
            if ($filterTier === 'Platinum') {
                $query->having('total_spent', '>=', 2500000);
            } elseif ($filterTier === 'Gold') {
                $query->having('total_spent', '>=', 1000000)->having('total_spent', '<', 2500000);
            } elseif ($filterTier === 'Regular') {
                $query->havingRaw('COALESCE(total_spent, 0) < 1000000');
            }
        }

        $sortBy = $request->query('sort', 'latest');
        if ($sortBy === 'highest_spent') {
            $query->orderByDesc('total_spent');
        } elseif ($sortBy === 'most_orders') {
            $query->orderByDesc('total_orders');
        } elseif ($sortBy === 'name_asc') {
            $query->orderBy('full_name', 'asc');
        } else {
            $query->orderByDesc('created_at');
        }

        $perPage = (int) $request->query('per_page', 10);
        $paginated = $query->paginate($perPage);

        $transformed = $paginated->getCollection()->map(function ($cust) {
            $totalSpent = (float) ($cust->total_spent ?? 0);
            $totalOrders = (int) ($cust->total_orders ?? 0);
            $points = (int) floor($totalSpent / 10000);

            $tier = 'Regular';
            if ($totalSpent >= 2500000) {
                $tier = 'Platinum';
            } elseif ($totalSpent >= 1000000) {
                $tier = 'Gold';
            }

            $tierBadge = match ($tier) {
                'Platinum' => [
                    'label' => 'Platinum',
                    'color' => 'bg-purple-50 text-purple-700',
                ],
                'Gold' => [
                    'label' => 'Gold',
                    'color' => 'bg-amber-50 text-amber-700',
                ],
                default => [
                    'label' => 'Regular',
                    'color' => 'bg-slate-100 text-slate-600',
                ]
            };

            $nameParts = explode(' ', trim($cust->full_name));
            $initials = '';
            foreach ($nameParts as $p) {
                if (!empty($p)) {
                    $initials .= strtoupper(mb_substr($p, 0, 1));
                    if (mb_strlen($initials) >= 2) break;
                }
            }
            if (empty($initials)) {
                $initials = 'PL';
            }

            $lastOrder = $cust->orders()->latest('placed_at')->first();
            $address = $cust->addresses[0]['address'] ?? ($cust->addresses['street'] ?? ($cust->addresses['address'] ?? null));

            return [
                'id' => $cust->id,
                'full_name' => $cust->full_name,
                'email' => $cust->email,
                'phone' => $cust->phone ?? '-',
                'initials' => $initials,
                'avatar_url' => $cust->avatar_url,
                'status' => $cust->status,
                'total_orders' => $totalOrders,
                'total_orders_formatted' => $totalOrders . 'x',
                'total_spent' => $totalSpent,
                'total_spent_formatted' => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
                'points' => $points,
                'points_formatted' => number_format($points, 0, ',', '.') . ' Pts',
                'tier' => $tier,
                'tier_badge' => $tierBadge,
                'address' => $address,
                'last_order_at' => $lastOrder?->placed_at?->format('d M Y H:i') ?? '-',
                'joined_at' => $cust->created_at?->format('d M Y') ?? '-',
            ];
        });

        $paginated->setCollection($transformed);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pelanggan berhasil dimuat.',
            'data' => $paginated->items(),
            'metrics' => $metrics,
            'meta' => [
                'current_page' => $paginated->currentPage(),
                'from' => $paginated->firstItem() ?? 0,
                'to' => $paginated->lastItem() ?? 0,
                'per_page' => $paginated->perPage(),
                'total' => $paginated->total(),
                'last_page' => $paginated->lastPage(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $cust = User::whereIn('role', ['customer', 'buyer'])->where('id', $id)->first();

        if (! $cust) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan tidak ditemukan.',
            ], 404);
        }

        $orders = Order::where('buyer_user_id', $cust->id)
            ->latest('placed_at')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'order_number' => $order->order_number,
                    'status' => $order->status,
                    'total_amount' => (float) $order->total_amount,
                    'total_formatted' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                    'placed_at' => $order->placed_at?->format('d M Y H:i') ?? '-',
                ];
            });

        $totalSpent = (float) Order::where('buyer_user_id', $cust->id)->where('status', '!=', 'cancelled')->sum('total_amount');
        $totalOrders = Order::where('buyer_user_id', $cust->id)->where('status', '!=', 'cancelled')->count();
        $points = (int) floor($totalSpent / 10000);

        return response()->json([
            'success' => true,
            'message' => 'Detail pelanggan berhasil dimuat.',
            'data' => [
                'id' => $cust->id,
                'full_name' => $cust->full_name,
                'email' => $cust->email,
                'phone' => $cust->phone ?? '-',
                'status' => $cust->status,
                'total_orders' => $totalOrders,
                'total_spent' => $totalSpent,
                'total_spent_formatted' => 'Rp ' . number_format($totalSpent, 0, ',', '.'),
                'points' => $points,
                'address' => $cust->addresses[0]['address'] ?? ($cust->addresses['street'] ?? ($cust->addresses['address'] ?? null)),
                'joined_at' => $cust->created_at?->format('d M Y') ?? '-',
                'recent_orders' => $orders,
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255|unique:users,email',
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();
        $email = $validated['email'] ?? ('cust_' . time() . '_' . Str::random(5) . '@umkmgo.id');

        $addresses = null;
        if (!empty($validated['address'])) {
            $addresses = [['address' => $validated['address'], 'is_default' => true]];
        }

        $cust = User::create([
            'full_name' => $validated['full_name'],
            'email' => $email,
            'phone' => $validated['phone'] ?? null,
            'password_hash' => Hash::make(Str::random(16)),
            'role' => 'customer',
            'status' => $validated['status'] ?? 'active',
            'addresses' => $addresses,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pelanggan berhasil ditambahkan.',
            'data' => $cust,
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $cust = User::whereIn('role', ['customer', 'buyer'])->where('id', $id)->first();

        if (! $cust) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:120',
            'phone' => 'nullable|string|max:30',
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($cust->id),
            ],
            'address' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $addresses = $cust->addresses ?? [];
        if (!empty($validated['address'])) {
            $addresses = [['address' => $validated['address'], 'is_default' => true]];
        }

        $cust->update([
            'full_name' => $validated['full_name'],
            'phone' => $validated['phone'] ?? $cust->phone,
            'email' => $validated['email'] ?? $cust->email,
            'status' => $validated['status'] ?? $cust->status,
            'addresses' => $addresses,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil diperbarui.',
            'data' => $cust,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $cust = User::whereIn('role', ['customer', 'buyer'])->where('id', $id)->first();

        if (! $cust) {
            return response()->json([
                'success' => false,
                'message' => 'Pelanggan tidak ditemukan.',
            ], 404);
        }

        $cust->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil dihapus.',
        ]);
    }
}
