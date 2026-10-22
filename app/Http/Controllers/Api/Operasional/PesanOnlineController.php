<?php

namespace App\Http\Controllers\Api\Operasional;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\Store;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PesanOnlineController extends Controller
{
    use ApiResponse;

    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['buyer:id,full_name,email,phone', 'items', 'store:id,name']);

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->query('store_id'));
        }

        $status = $request->query('status', 'all');
        if ($status !== 'all' && !empty($status)) {
            if ($status === 'new') {
                $query->whereIn('status', ['pending', 'paid']);
            } elseif ($status === 'processing') {
                $query->where('status', 'processing');
            } elseif ($status === 'shipped') {
                $query->where('status', 'shipped');
            } elseif ($status === 'delivered') {
                $query->where('status', 'delivered');
            } elseif ($status === 'cancelled') {
                $query->where('status', 'cancelled');
            } else {
                $query->where('status', $status);
            }
        }

        if ($request->filled('courier') && $request->query('courier') !== 'all') {
            $query->where('courier', $request->query('courier'));
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('courier', 'like', "%{$search}%")
                  ->orWhereHas('buyer', function ($bq) use ($search) {
                      $bq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        $storeId = $request->query('store_id', Store::value('id'));
        $baseCountsQuery = Order::query();
        if ($storeId) {
            $baseCountsQuery->where('store_id', $storeId);
        }

        $allOrdersForCounts = $baseCountsQuery->get(['id', 'status']);
        $tabCounts = [
            'all' => $allOrdersForCounts->count(),
            'new' => $allOrdersForCounts->whereIn('status', ['pending', 'paid'])->count(),
            'processing' => $allOrdersForCounts->where('status', 'processing')->count(),
            'shipped' => $allOrdersForCounts->where('status', 'shipped')->count(),
            'delivered' => $allOrdersForCounts->where('status', 'delivered')->count(),
            'cancelled' => $allOrdersForCounts->where('status', 'cancelled')->count(),
        ];

        $perPage = (int) $request->query('per_page', 10);
        $orders = $query->latest('placed_at')->paginate($perPage);

        $transformed = $orders->getCollection()->map(function ($order) {
            $itemsSummary = $order->items->map(function ($item) {
                return $item->quantity . 'x ' . $item->product_name_snapshot;
            })->implode(', ');

            $statusBadge = match ($order->status) {
                'pending', 'paid' => [
                    'label' => 'Baru',
                    'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                ],
                'processing' => [
                    'label' => 'Diproses',
                    'color' => 'bg-blue-50 text-blue-600 border-blue-100',
                ],
                'shipped' => [
                    'label' => 'Dalam Pengiriman',
                    'color' => 'bg-amber-50 text-amber-600 border-amber-100',
                ],
                'delivered' => [
                    'label' => 'Selesai',
                    'color' => 'bg-slate-100 text-slate-600 border-slate-200',
                ],
                'cancelled' => [
                    'label' => 'Dibatalkan',
                    'color' => 'bg-rose-50 text-rose-600 border-rose-100',
                ],
                default => [
                    'label' => ucfirst($order->status),
                    'color' => 'bg-slate-100 text-slate-600 border-slate-200',
                ]
            };

            $firstItem = $order->items->first();
            $thumbnail = null;
            if ($firstItem && $firstItem->product_id) {
                $prod = \App\Models\Product::find($firstItem->product_id);
                $images = $prod?->images;
                if (!empty($images) && is_array($images)) {
                    $thumbnail = asset(ltrim($images[0], '/'));
                }
            }
            if (!$thumbnail) {
                $thumbnail = asset('images/dashboard/coffee-americano.jpg');
            }

            return [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'checkout_number' => $order->checkout_number,
                'status' => $order->status,
                'status_badge' => $statusBadge,
                'buyer_name' => $order->buyer?->full_name ?? ($order->shipping_address['recipient_name'] ?? 'Pelanggan'),
                'buyer_phone' => $order->buyer?->phone ?? ($order->shipping_address['phone'] ?? '-'),
                'shipping_address' => $order->shipping_address,
                'courier' => $order->courier ?? 'Kurir Toko',
                'service_name' => $order->service_name,
                'tracking_number' => $order->tracking_number,
                'items_count' => $order->items->sum('quantity'),
                'items_summary' => $itemsSummary ?: 'Tidak ada item',
                'items' => $order->items,
                'subtotal_amount' => (float) $order->subtotal_amount,
                'discount_amount' => (float) $order->discount_amount,
                'shipping_amount' => (float) $order->shipping_amount,
                'total_amount' => (float) $order->total_amount,
                'total_formatted' => 'Rp ' . number_format($order->total_amount, 0, ',', '.'),
                'buyer_note' => $order->buyer_note,
                'thumbnail' => $thumbnail,
                'placed_at' => $order->placed_at?->format('d M Y H:i'),
                'shipped_at' => $order->shipped_at?->format('d M Y H:i'),
                'delivered_at' => $order->delivered_at?->format('d M Y H:i'),
            ];
        });

        $orders->setCollection($transformed);

        return response()->json([
            'success' => true,
            'message' => 'Daftar pesanan online berhasil dimuat.',
            'data' => $orders->items(),
            'counts' => $tabCounts,
            'meta' => [
                'current_page' => $orders->currentPage(),
                'from' => $orders->firstItem() ?? 0,
                'to' => $orders->lastItem() ?? 0,
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage(),
            ],
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $order = Order::with(['buyer', 'items.variant', 'items.product', 'payments'])
            ->where('id', $id)
            ->orWhere('order_number', $id)
            ->first();

        if (! $order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        return $this->successResponse($order, 'Detail pesanan online berhasil dimuat.');
    }

    public function accept(string $id): JsonResponse
    {
        $order = Order::where('id', $id)->orWhere('order_number', $id)->first();

        if (! $order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        if (! in_array($order->status, ['pending', 'paid'])) {
            return $this->errorResponse("Pesanan berstatus {$order->status} tidak dapat diproses lagi.", 422);
        }

        $history = $order->status_history ?? [];
        $history[] = [
            'from' => $order->status,
            'to' => 'processing',
            'changed_at' => now()->toIso8601String(),
            'changed_by' => auth()->id(),
            'note' => 'Pesanan diterima oleh penjual dan sedang dipersiapkan',
        ];

        $order->status = 'processing';
        $order->status_history = $history;
        $order->updated_at = now();
        $order->save();

        return $this->successResponse($order->fresh(['buyer', 'items']), 'Pesanan berhasil diterima dan sedang diproses.');
    }

    public function ship(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'courier' => 'required|string|max:80',
            'service_name' => 'nullable|string|max:80',
            'tracking_number' => 'required|string|max:120',
            'note' => 'nullable|string|max:255',
        ], [
            'courier.required' => 'Nama kurir wajib dipilih atau diisi.',
            'tracking_number.required' => 'Nomor resi pengiriman wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi pengiriman gagal.', 422, $validator->errors());
        }

        $order = Order::where('id', $id)->orWhere('order_number', $id)->first();

        if (! $order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        if ($order->status === 'cancelled') {
            return $this->errorResponse('Pesanan yang sudah dibatalkan tidak dapat dikirim.', 422);
        }

        $history = $order->status_history ?? [];
        $history[] = [
            'from' => $order->status,
            'to' => 'shipped',
            'changed_at' => now()->toIso8601String(),
            'changed_by' => auth()->id(),
            'note' => 'Pesanan diserahkan ke kurir ' . $request->input('courier') . ' dengan resi ' . $request->input('tracking_number'),
        ];

        $events = $order->tracking_events ?? [];
        $events[] = [
            'time' => now()->toIso8601String(),
            'status' => 'shipped',
            'description' => 'Paket telah diserahkan kepada kurir ' . $request->input('courier'),
        ];

        $order->status = 'shipped';
        $order->courier = $request->input('courier');
        $order->service_name = $request->input('service_name', 'Reguler');
        $order->tracking_number = $request->input('tracking_number');
        $order->shipment_status = 'in_transit';
        $order->shipped_at = now();
        $order->status_history = $history;
        $order->tracking_events = $events;
        $order->updated_at = now();
        $order->save();

        return $this->successResponse($order->fresh(['buyer', 'items']), 'Pesanan berhasil dikirim dan nomor resi telah diperbarui.');
    }

    public function complete(string $id): JsonResponse
    {
        $order = Order::where('id', $id)->orWhere('order_number', $id)->first();

        if (! $order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        $history = $order->status_history ?? [];
        $history[] = [
            'from' => $order->status,
            'to' => 'delivered',
            'changed_at' => now()->toIso8601String(),
            'changed_by' => auth()->id(),
            'note' => 'Pesanan telah selesai diterima oleh pembeli',
        ];

        $events = $order->tracking_events ?? [];
        $events[] = [
            'time' => now()->toIso8601String(),
            'status' => 'delivered',
            'description' => 'Pesanan telah diterima oleh pembeli',
        ];

        $order->status = 'delivered';
        $order->shipment_status = 'delivered';
        $order->delivered_at = now();
        $order->status_history = $history;
        $order->tracking_events = $events;
        $order->updated_at = now();
        $order->save();

        return $this->successResponse($order->fresh(['buyer', 'items']), 'Pesanan telah berhasil diselesaikan.');
    }

    public function cancel(Request $request, string $id): JsonResponse
    {
        $order = Order::with('items')->where('id', $id)->orWhere('order_number', $id)->first();

        if (! $order) {
            return $this->errorResponse('Pesanan tidak ditemukan.', 404);
        }

        if (in_array($order->status, ['delivered', 'cancelled'])) {
            return $this->errorResponse("Pesanan berstatus {$order->status} tidak dapat dibatalkan.", 422);
        }

        $reason = $request->input('cancel_reason', 'Dibatalkan oleh penjual');

        DB::transaction(function () use ($order, $reason) {
            foreach ($order->items as $item) {
                if ($item->variant_id) {
                    $variant = ProductVariant::find($item->variant_id);
                    if ($variant) {
                        $variant->increment('stock_quantity', $item->quantity);

                        StockMovement::create([
                            'store_id' => $order->store_id,
                            'variant_id' => $variant->id,
                            'movement_type' => 'in',
                            'quantity_delta' => $item->quantity,
                            'quantity_after' => $variant->stock_quantity,
                            'reference_type' => 'manual_adjustment',
                            'adjustment_number' => $order->order_number,
                            'note' => 'Pengembalian stok pembatalan pesanan #' . $order->order_number,
                            'created_by' => auth()->id(),
                        ]);
                    }
                }
            }

            $history = $order->status_history ?? [];
            $history[] = [
                'from' => $order->status,
                'to' => 'cancelled',
                'changed_at' => now()->toIso8601String(),
                'changed_by' => auth()->id(),
                'note' => 'Pembatalan pesanan: ' . $reason,
            ];

            $order->status = 'cancelled';
            $order->status_history = $history;
            $order->updated_at = now();
            $order->save();
        });

        return $this->successResponse($order->fresh(['buyer', 'items']), 'Pesanan berhasil dibatalkan dan stok telah dikembalikan.');
    }

    public function couriers(): JsonResponse
    {
        $couriers = [
            ['code' => 'JNE', 'name' => 'JNE Express'],
            ['code' => 'J&T', 'name' => 'J&T Express'],
            ['code' => 'SiCepat', 'name' => 'SiCepat Ekspres'],
            ['code' => 'Anteraja', 'name' => 'Anteraja'],
            ['code' => 'GoSend', 'name' => 'GoSend (Instant/SameDay)'],
            ['code' => 'GrabExpress', 'name' => 'GrabExpress'],
            ['code' => 'Shopee Xpress', 'name' => 'Shopee Xpress'],
            ['code' => 'Kurir Toko', 'name' => 'Kurir Internal Toko'],
        ];

        return $this->successResponse($couriers, 'Daftar kurir berhasil dimuat.');
    }
}
