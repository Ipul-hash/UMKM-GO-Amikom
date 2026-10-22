<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\Store;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class StokController extends Controller
{
    use ApiResponse;

    /**
     * Get list of inventory items (variants) with stock status, search, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = ProductVariant::with(['product' => function ($q) {
            $q->select('id', 'store_id', 'category_id', 'name', 'slug', 'images')
              ->with('category:id,name,slug');
        }])->whereHas('product', function ($q) {
            $q->where('status', '!=', 'archived');
        });

        // Filter: Store
        if ($request->filled('store_id')) {
            $storeId = $request->query('store_id');
            $query->whereHas('product', fn($q) => $q->where('store_id', $storeId));
        }

        // Filter: Category
        if ($request->filled('category_id')) {
            $catId = $request->query('category_id');
            $query->whereHas('product', fn($q) => $q->where('category_id', $catId));
        }

        // Filter: Stock Status (aman, menipis, habis)
        if ($request->filled('stock_status')) {
            $status = $request->query('stock_status');
            if ($status === 'aman') {
                $query->whereColumn('stock_quantity', '>', 'low_stock_threshold');
            } elseif ($status === 'menipis') {
                $query->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                      ->where('stock_quantity', '>', 0);
            } elseif ($status === 'habis') {
                $query->where('stock_quantity', '<=', 0);
            }
        }

        // Search: SKU, Variant Name, or Product Name
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('sku', 'like', "%{$search}%")
                  ->orWhere('variant_name', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->query('sort_by', 'sku_asc');
        switch ($sortBy) {
            case 'stock_asc':
                $query->orderBy('stock_quantity', 'asc');
                break;
            case 'stock_desc':
                $query->orderBy('stock_quantity', 'desc');
                break;
            case 'sku_desc':
                $query->orderBy('sku', 'desc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->orderBy('sku', 'asc');
                break;
        }

        $perPage = (int) $request->query('per_page', 10);
        $paginator = $query->paginate($perPage);

        // Transform results with calculated status & formatting
        $transformed = $paginator->getCollection()->map(function ($variant) {
            $stock = (int) $variant->stock_quantity;
            $threshold = (int) $variant->low_stock_threshold;

            if ($stock <= 0) {
                $statusKey = 'habis';
                $statusLabel = 'Stok Habis';
                $statusColor = 'bg-red-50 text-red-600 border-red-200';
            } elseif ($stock <= $threshold) {
                $statusKey = 'menipis';
                $statusLabel = 'Stok Menipis';
                $statusColor = 'bg-amber-50 text-amber-600 border-amber-200';
            } else {
                $statusKey = 'aman';
                $statusLabel = 'Aman';
                $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-200';
            }

            $assetValue = (float) ($variant->price * $stock);
            $productImages = $variant->product?->images;
            $thumb = !empty($productImages) && is_array($productImages) ? $productImages[0] : null;

            return [
                'id' => $variant->id,
                'sku' => $variant->sku ?? '-',
                'product_id' => $variant->product?->id,
                'product_name' => $variant->product?->name ?? 'Produk Tanpa Nama',
                'variant_name' => $variant->variant_name,
                'full_name' => ($variant->product?->name ?? '') . ' - ' . $variant->variant_name,
                'category_name' => $variant->product?->category?->name ?? 'Umum',
                'price' => (float) $variant->price,
                'price_formatted' => 'Rp ' . number_format($variant->price, 0, ',', '.'),
                'stock_quantity' => $stock,
                'low_stock_threshold' => $threshold,
                'status' => $statusKey,
                'status_label' => $statusLabel,
                'status_color' => $statusColor,
                'asset_value' => $assetValue,
                'asset_value_formatted' => 'Rp ' . number_format($assetValue, 0, ',', '.'),
                'thumbnail' => $thumb ? asset(ltrim($thumb, '/')) : asset('images/dashboard/coffee-americano.jpg'),
            ];
        });

        $paginator->setCollection($transformed);

        return $this->paginatedResponse($paginator, 'Data inventaris stok berhasil dimuat.');
    }

    /**
     * Get inventory overview stats for top summary cards.
     */
    public function summary(Request $request): JsonResponse
    {
        $query = ProductVariant::whereHas('product', function ($q) {
            $q->where('status', '!=', 'archived');
        });

        if ($request->filled('store_id')) {
            $storeId = $request->query('store_id');
            $query->whereHas('product', fn($q) => $q->where('store_id', $storeId));
        }

        $allVariants = $query->get(['id', 'price', 'stock_quantity', 'low_stock_threshold']);

        $totalSku = $allVariants->count();
        $lowStockCount = $allVariants->filter(fn($v) => $v->stock_quantity <= $v->low_stock_threshold && $v->stock_quantity > 0)->count();
        $outOfStockCount = $allVariants->filter(fn($v) => $v->stock_quantity <= 0)->count();
        $safeStockCount = $allVariants->filter(fn($v) => $v->stock_quantity > $v->low_stock_threshold)->count();
        $totalAssetValue = $allVariants->sum(fn($v) => $v->price * $v->stock_quantity);

        return $this->successResponse([
            'total_sku' => $totalSku,
            'low_stock_count' => $lowStockCount,
            'out_of_stock_count' => $outOfStockCount,
            'safe_stock_count' => $safeStockCount,
            'total_asset_value' => (float) $totalAssetValue,
            'total_asset_value_formatted' => 'Rp ' . number_format($totalAssetValue, 0, ',', '.'),
        ], 'Ringkasan inventaris berhasil diambil.');
    }

    /**
     * Perform a manual stock adjustment (+ in, - out, or absolute set).
     */
    public function adjust(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'variant_id' => 'required|exists:product_variants,id',
            'adjustment_type' => 'required|in:in,out,adjustment',
            'quantity' => 'required|integer',
            'note' => 'nullable|string|max:255',
            'adjustment_number' => 'nullable|string|max:50',
        ], [
            'variant_id.required' => 'Varian produk wajib dipilih.',
            'variant_id.exists' => 'Varian produk tidak ditemukan.',
            'adjustment_type.required' => 'Jenis penyesuaian wajib dipilih (in, out, atau adjustment).',
            'quantity.required' => 'Jumlah penyesuaian kuantitas wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal.', 422, $validator->errors());
        }

        $validated = $validator->validated();

        try {
            $result = DB::transaction(function () use ($validated) {
                // Lock variant for concurrency safety
                $variant = ProductVariant::with('product')->lockForUpdate()->find($validated['variant_id']);

                $oldStock = (int) $variant->stock_quantity;
                $inputQty = (int) $validated['quantity'];
                $adjType = $validated['adjustment_type'];

                if ($adjType === 'in') {
                    $delta = abs($inputQty);
                    $newStock = $oldStock + $delta;
                } elseif ($adjType === 'out') {
                    $delta = -abs($inputQty);
                    $newStock = $oldStock + $delta;
                    if ($newStock < 0) {
                        throw new \InvalidArgumentException("Stok tidak mencukupi untuk pengurangan ini. Stok saat ini: {$oldStock}.");
                    }
                } else { // 'adjustment' -> set exact new stock quantity
                    if ($inputQty < 0) {
                        throw new \InvalidArgumentException("Stok tidak boleh bernilai negatif.");
                    }
                    $delta = $inputQty - $oldStock;
                    $newStock = $inputQty;
                }

                // Update variant stock
                $variant->stock_quantity = $newStock;
                $variant->save();

                // Generate adjustment document number
                $adjNum = $validated['adjustment_number'] ?? 'ADJ-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                // Create audit stock movement
                $movement = StockMovement::create([
                    'store_id' => $variant->product->store_id,
                    'variant_id' => $variant->id,
                    'movement_type' => $adjType,
                    'quantity_delta' => $delta,
                    'quantity_after' => $newStock,
                    'reference_type' => 'manual_adjustment',
                    'adjustment_number' => $adjNum,
                    'note' => $validated['note'] ?? 'Penyesuaian stok manual oleh admin/staf',
                    'created_by' => auth()->id(),
                ]);

                return [
                    'variant' => $variant->fresh(),
                    'movement' => $movement,
                    'previous_stock' => $oldStock,
                    'current_stock' => $newStock,
                    'delta' => $delta,
                ];
            });

            return $this->successResponse($result, 'Penyesuaian stok berhasil disimpan.');
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Throwable $e) {
            return $this->errorResponse('Gagal melakukan penyesuaian stok: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get stock movement audit trail.
     */
    public function movements(Request $request): JsonResponse
    {
        $query = StockMovement::with([
            'variant' => function ($q) {
                $q->select('id', 'product_id', 'sku', 'variant_name')
                  ->with('product:id,name');
            },
            'creator:id,full_name,role',
        ]);

        if ($request->filled('variant_id')) {
            $query->where('variant_id', $request->query('variant_id'));
        }

        if ($request->filled('movement_type')) {
            $query->where('movement_type', $request->query('movement_type'));
        }

        $perPage = (int) $request->query('per_page', 15);
        $movements = $query->latest('created_at')->paginate($perPage);

        return $this->paginatedResponse($movements, 'Riwayat mutasi stok berhasil dimuat.');
    }
}
