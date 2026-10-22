<?php

namespace App\Http\Controllers\Api\Operasional;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Supplier::query();

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->query('store_id'));
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('supply_category', 'like', "%{$search}%")
                  ->orWhere('pic_name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('supply_category', $request->query('category'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->query('status'));
        }

        $allSuppliers = (clone $query)->get();

        $categories = Supplier::whereNotNull('supply_category')
            ->distinct()
            ->pluck('supply_category');

        $perPage = (int) $request->query('per_page', 9);
        $paginated = $query->orderBy('name', 'asc')->paginate($perPage);

        $transformed = $paginated->getCollection()->map(function ($sup) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $sup->phone);
            if (str_starts_with($cleanPhone, '0')) {
                $cleanPhone = '62' . substr($cleanPhone, 1);
            }
            $whatsappUrl = 'https://wa.me/' . $cleanPhone;

            $catLower = strtolower($sup->supply_category);
            $iconType = 'general';
            if (str_contains($catLower, 'kopi') || str_contains($catLower, 'coffee') || str_contains($catLower, 'minuman')) {
                $iconType = 'coffee';
            } elseif (str_contains($catLower, 'kemas') || str_contains($catLower, 'cup') || str_contains($catLower, 'pack') || str_contains($catLower, 'box')) {
                $iconType = 'package';
            } elseif (str_contains($catLower, 'pakaian') || str_contains($catLower, 'baju') || str_contains($catLower, 'fashion') || str_contains($catLower, 'tas')) {
                $iconType = 'apparel';
            }

            return [
                'id' => $sup->id,
                'store_id' => $sup->store_id,
                'name' => $sup->name,
                'supply_category' => $sup->supply_category,
                'icon_type' => $iconType,
                'pic_name' => $sup->pic_name,
                'phone' => $sup->phone,
                'whatsapp_url' => $whatsappUrl,
                'email' => $sup->email ?? '-',
                'city' => $sup->city ?? 'Indonesia',
                'address' => $sup->address,
                'notes' => $sup->notes,
                'status' => $sup->status,
                'is_active' => $sup->status === 'active',
                'status_badge' => [
                    'label' => $sup->status === 'active' ? 'Aktif' : 'Nonaktif',
                    'color' => $sup->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500',
                ],
                'created_at' => $sup->created_at?->format('d M Y') ?? '-',
            ];
        });

        $paginated->setCollection($transformed);

        return response()->json([
            'success' => true,
            'message' => 'Daftar supplier berhasil dimuat.',
            'data' => $paginated->items(),
            'categories' => $categories,
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
        $sup = Supplier::find($id);

        if (! $sup) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail supplier berhasil dimuat.',
            'data' => $sup,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'supply_category' => 'required|string|max:100',
            'pic_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
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

        $storeId = $request->input('store_id');
        if (! $storeId) {
            $store = Store::first();
            $storeId = $store?->id;
        }

        $sup = Supplier::create([
            'store_id' => $storeId,
            'name' => $validated['name'],
            'supply_category' => $validated['supply_category'],
            'pic_name' => $validated['pic_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'city' => $validated['city'] ?? null,
            'address' => $validated['address'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => $validated['status'] ?? 'active',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil ditambahkan.',
            'data' => $sup,
        ], 201);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $sup = Supplier::find($id);

        if (! $sup) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:150',
            'supply_category' => 'required|string|max:100',
            'pic_name' => 'required|string|max:120',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:150',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
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

        $sup->update([
            'name' => $validated['name'],
            'supply_category' => $validated['supply_category'],
            'pic_name' => $validated['pic_name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? $sup->email,
            'city' => $validated['city'] ?? $sup->city,
            'address' => $validated['address'] ?? $sup->address,
            'notes' => $validated['notes'] ?? $sup->notes,
            'status' => $validated['status'] ?? $sup->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data supplier berhasil diperbarui.',
            'data' => $sup,
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $sup = Supplier::find($id);

        if (! $sup) {
            return response()->json([
                'success' => false,
                'message' => 'Supplier tidak ditemukan.',
            ], 404);
        }

        $sup->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier berhasil dihapus.',
        ]);
    }

    public function categories(): JsonResponse
    {
        $categories = Supplier::whereNotNull('supply_category')
            ->distinct()
            ->pluck('supply_category');

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
