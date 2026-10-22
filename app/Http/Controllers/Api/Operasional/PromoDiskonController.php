<?php

namespace App\Http\Controllers\Api\Operasional;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PromoDiskonController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Promotion::query();

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->query('store_id'));
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $allPromotions = (clone $query)->get();
        $now = now();

        $counts = [
            'all' => $allPromotions->count(),
            'active' => $allPromotions->filter(function ($item) use ($now) {
                return $item->status === 'active' && (!$item->ends_at || $item->ends_at >= $now);
            })->count(),
            'expired' => $allPromotions->filter(function ($item) use ($now) {
                return $item->status === 'expired' || ($item->ends_at && $item->ends_at < $now);
            })->count(),
            'disabled' => $allPromotions->where('status', 'disabled')->count(),
        ];

        $filterStatus = $request->query('status', 'all');
        if ($filterStatus === 'active') {
            $query->where('status', 'active')
                  ->where(function ($q) use ($now) {
                      $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
                  });
        } elseif ($filterStatus === 'expired') {
            $query->where(function ($q) use ($now) {
                $q->where('status', 'expired')
                  ->orWhere(function ($sub) use ($now) {
                      $sub->whereNotNull('ends_at')->where('ends_at', '<', $now);
                  });
            });
        } elseif ($filterStatus === 'disabled') {
            $query->where('status', 'disabled');
        }

        $perPage = (int) $request->query('per_page', 9);
        $paginated = $query->orderBy('created_at', 'desc')->paginate($perPage);

        $transformed = $paginated->getCollection()->map(function ($promo) use ($now) {
            $isExpired = $promo->status === 'expired' || ($promo->ends_at && $promo->ends_at < $now);
            $effectiveStatus = $isExpired ? 'expired' : $promo->status;

            $statusBadge = match ($effectiveStatus) {
                'active' => [
                    'label' => 'Aktif',
                    'color' => 'bg-emerald-50 text-emerald-600',
                ],
                'expired' => [
                    'label' => 'Berakhir',
                    'color' => 'bg-rose-50 text-rose-500',
                ],
                'disabled' => [
                    'label' => 'Nonaktif',
                    'color' => 'bg-slate-100 text-slate-500',
                ],
                default => [
                    'label' => ucfirst($effectiveStatus),
                    'color' => 'bg-slate-100 text-slate-600',
                ]
            };

            $discountFormatted = $promo->discount_type === 'percentage'
                ? ((float) $promo->discount_value) . '%'
                : 'Rp ' . number_format($promo->discount_value, 0, ',', '.');

            $periodFormatted = 'Selamanya';
            if ($promo->starts_at && $promo->ends_at) {
                $periodFormatted = Carbon::parse($promo->starts_at)->format('d M') . ' - ' . Carbon::parse($promo->ends_at)->format('d M Y');
            } elseif ($promo->starts_at) {
                $periodFormatted = 'Mulai ' . Carbon::parse($promo->starts_at)->format('d M Y');
            } elseif ($promo->ends_at) {
                $periodFormatted = 'Sampai ' . Carbon::parse($promo->ends_at)->format('d M Y');
            }

            $usageLimit = $promo->usage_limit;
            $usedCount = (int) $promo->used_count;
            $quotaFormatted = $usageLimit ? "{$usedCount}/{$usageLimit}" : "{$usedCount} / ∞";
            $quotaPercentage = ($usageLimit && $usageLimit > 0) ? min(100, round(($usedCount / $usageLimit) * 100)) : 0;

            return [
                'id' => $promo->id,
                'store_id' => $promo->store_id,
                'name' => $promo->name,
                'description' => $promo->description,
                'code' => $promo->code,
                'discount_type' => $promo->discount_type,
                'discount_value' => (float) $promo->discount_value,
                'discount_formatted' => $discountFormatted,
                'max_discount_amount' => $promo->max_discount_amount ? (float) $promo->max_discount_amount : null,
                'max_discount_formatted' => $promo->max_discount_amount ? 'Rp ' . number_format($promo->max_discount_amount, 0, ',', '.') : null,
                'minimum_purchase' => (float) $promo->minimum_purchase,
                'minimum_purchase_formatted' => 'Rp ' . number_format($promo->minimum_purchase, 0, ',', '.'),
                'usage_limit' => $promo->usage_limit,
                'used_count' => $usedCount,
                'quota_formatted' => $quotaFormatted,
                'quota_percentage' => $quotaPercentage,
                'per_user_limit' => $promo->per_user_limit,
                'starts_at' => $promo->starts_at ? Carbon::parse($promo->starts_at)->format('Y-m-d\TH:i') : null,
                'ends_at' => $promo->ends_at ? Carbon::parse($promo->ends_at)->format('Y-m-d\TH:i') : null,
                'period_formatted' => $periodFormatted,
                'status' => $effectiveStatus,
                'is_active' => $effectiveStatus === 'active',
                'is_expired' => $isExpired,
                'status_badge' => $statusBadge,
                'created_at' => Carbon::parse($promo->created_at)->format('d M Y H:i'),
            ];
        });

        $paginated->setCollection($transformed);

        return response()->json([
            'success' => true,
            'message' => 'Daftar promo dan diskon berhasil dimuat.',
            'data' => $paginated->items(),
            'counts' => $counts,
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
        $promo = Promotion::where('id', $id)->orWhere('code', $id)->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail promo berhasil dimuat.',
            'data' => $promo,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:60|unique:promotions,code',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,disabled,expired',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $storeId = $request->input('store_id');
        if (! $storeId) {
            $store = Store::first();
            $storeId = $store?->id;
        }

        $code = strtoupper(trim($validated['code']));

        $startsAt = !empty($validated['starts_at']) ? Carbon::parse($validated['starts_at']) : null;
        $endsAt = !empty($validated['ends_at']) ? Carbon::parse($validated['ends_at']) : null;

        $status = $validated['status'] ?? 'active';
        if ($endsAt && $endsAt->isPast()) {
            $status = 'expired';
        }

        $promo = Promotion::create([
            'store_id' => $storeId,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'code' => $code,
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'max_discount_amount' => $validated['max_discount_amount'] ?? null,
            'minimum_purchase' => $validated['minimum_purchase'] ?? 0,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'per_user_limit' => $validated['per_user_limit'] ?? 1,
            'used_count' => 0,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $status,
            'created_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Promo baru berhasil dibuat.',
            'data' => $promo,
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $promo = Promotion::where('id', $id)->orWhere('code', $id)->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'code' => [
                'required',
                'string',
                'max:60',
                Rule::unique('promotions', 'code')->ignore($promo->id),
            ],
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:0.01',
            'max_discount_amount' => 'nullable|numeric|min:0',
            'minimum_purchase' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'per_user_limit' => 'nullable|integer|min:1',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,disabled,expired',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $validated = $validator->validated();

        $startsAt = !empty($validated['starts_at']) ? Carbon::parse($validated['starts_at']) : null;
        $endsAt = !empty($validated['ends_at']) ? Carbon::parse($validated['ends_at']) : null;

        $status = $validated['status'] ?? $promo->status;
        if ($endsAt && $endsAt->isPast()) {
            $status = 'expired';
        }

        $promo->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? $promo->description,
            'code' => strtoupper(trim($validated['code'])),
            'discount_type' => $validated['discount_type'],
            'discount_value' => $validated['discount_value'],
            'max_discount_amount' => $validated['max_discount_amount'] ?? null,
            'minimum_purchase' => $validated['minimum_purchase'] ?? 0,
            'usage_limit' => $validated['usage_limit'] ?? null,
            'per_user_limit' => $validated['per_user_limit'] ?? $promo->per_user_limit,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil diperbarui.',
            'data' => $promo,
        ]);
    }

    public function toggleStatus(Request $request, string $id): JsonResponse
    {
        $promo = Promotion::where('id', $id)->orWhere('code', $id)->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan.',
            ], 404);
        }

        $newStatus = $promo->status === 'active' ? 'disabled' : 'active';
        if ($newStatus === 'active' && $promo->ends_at && Carbon::parse($promo->ends_at)->isPast()) {
            $promo->ends_at = now()->addDays(7);
        }

        $promo->status = $newStatus;
        $promo->save();

        return response()->json([
            'success' => true,
            'message' => 'Status promo berhasil diubah.',
            'data' => [
                'id' => $promo->id,
                'status' => $promo->status,
                'is_active' => $promo->status === 'active',
            ],
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $promo = Promotion::where('id', $id)->orWhere('code', $id)->first();

        if (! $promo) {
            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan.',
            ], 404);
        }

        $promo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Promo berhasil dihapus.',
        ]);
    }
}
