<?php

namespace App\Http\Controllers\Api\Operasional;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\FinancialEntry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PosSale;
use App\Models\PosShift;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\Store;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KasirController extends Controller
{
    use ApiResponse;

    /**
     * Get active products catalog for the POS Cashier grid.
     */
    public function catalog(Request $request): JsonResponse
    {
        $query = Product::with(['category:id,name,slug', 'variants' => function ($vq) {
            $vq->where('is_active', true)->orderBy('price', 'asc');
        }])->where('status', 'active');

        if ($request->filled('store_id')) {
            $query->where('store_id', $request->query('store_id'));
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        } elseif ($request->filled('category_slug') && $request->query('category_slug') !== 'all') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->query('category_slug'));
            });
        }

        // Search: Name, Description, or Variant SKU
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%")
                         ->orWhere('variant_name', 'like', "%{$search}%");
                  });
            });
        }

        $products = $query->latest()->get();

        // Transform for POS cards
        $items = $products->map(function ($product) {
            $primaryVariant = $product->variants->first();
            $totalStock = $product->variants->sum('stock_quantity');
            $price = $primaryVariant ? (float) $primaryVariant->price : 0;
            $images = $product->images;
            $firstImage = !empty($images) && is_array($images) ? $images[0] : null;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'category_name' => $product->category?->name ?? 'Umum',
                'category_id' => $product->category?->id,
                'thumbnail' => $firstImage ? asset(ltrim($firstImage, '/')) : asset('images/dashboard/coffee-americano.jpg'),
                'variant_id' => $primaryVariant?->id,
                'variant_name' => $primaryVariant?->variant_name ?? 'Standar',
                'sku' => $primaryVariant?->sku ?? '-',
                'price' => $price,
                'price_formatted' => 'Rp ' . number_format($price, 0, ',', '.'),
                'stock_quantity' => (int) ($primaryVariant?->stock_quantity ?? $totalStock),
                'total_stock' => (int) $totalStock,
                'variants' => $product->variants->map(fn($v) => [
                    'id' => $v->id,
                    'variant_name' => $v->variant_name,
                    'sku' => $v->sku,
                    'price' => (float) $v->price,
                    'price_formatted' => 'Rp ' . number_format($v->price, 0, ',', '.'),
                    'stock_quantity' => (int) $v->stock_quantity,
                ]),
            ];
        });

        return $this->successResponse([
            'products' => $items,
            'total' => $items->count(),
        ], 'Katalog kasir berhasil dimuat.');
    }

    /**
     * Get customer options for cashier selector.
     */
    public function customers(): JsonResponse
    {
        $customers = User::whereIn('role', ['buyer', 'seller_owner', 'seller_staff'])
            ->orderBy('full_name', 'asc')
            ->limit(30)
            ->get(['id', 'full_name', 'phone', 'email']);

        $list = collect([
            [
                'id' => null,
                'full_name' => 'Pelanggan Umum',
                'phone' => '-',
                'email' => '-',
            ]
        ])->concat($customers);

        return $this->successResponse($list, 'Daftar pelanggan berhasil dimuat.');
    }

    /**
     * Get or ensure active register shift for POS session.
     */
    public function activeShift(Request $request): JsonResponse
    {
        $storeId = $request->query('store_id', Store::value('id'));
        $cashierId = auth()->id() ?? User::where('role', 'seller_owner')->value('id') ?? User::value('id');

        $shift = PosShift::where('store_id', $storeId)
            ->where('status', 'open')
            ->latest('opened_at')
            ->first();

        if (! $shift && $storeId && $cashierId) {
            // Auto open default daily shift if none exists
            $shift = PosShift::create([
                'store_id' => $storeId,
                'register_name' => 'Kasir Utama 01',
                'cashier_user_id' => $cashierId,
                'opening_cash' => 200000,
                'status' => 'open',
                'note' => 'Shift kasir dibuka otomatis oleh sistem POS',
                'opened_at' => now(),
            ]);
        }

        return $this->successResponse($shift, 'Shift kasir aktif.');
    }

    /**
     * Process checkout transaction from POS cart.
     */
    public function checkout(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'nullable|exists:stores,id',
            'customer_user_id' => 'nullable|exists:users,id',
            'customer_name' => 'nullable|string|max:120',
            'items' => 'required|array|min:1',
            'items.*.variant_id' => 'required|exists:product_variants,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,qris,debit,transfer',
            'amount_paid' => 'required|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ], [
            'items.required' => 'Keranjang belanja kasir masih kosong.',
            'items.min' => 'Minimal harus ada 1 item di keranjang.',
            'items.*.quantity.min' => 'Kuantitas item minimal 1.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'amount_paid.required' => 'Jumlah uang yang dibayarkan wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi transaksi gagal.', 422, $validator->errors());
        }

        $validated = $validator->validated();

        try {
            $transactionResult = DB::transaction(function () use ($validated, $request) {
                // 1. Resolve store and cashier
                $storeId = $validated['store_id'] ?? Store::value('id');
                $store = Store::findOrFail($storeId);

                $cashierId = auth()->id() ?? User::where('role', 'seller_owner')->value('id') ?? User::value('id');
                $cashier = User::find($cashierId);

                // 2. Resolve or open active shift
                $shift = PosShift::where('store_id', $storeId)
                    ->where('status', 'open')
                    ->latest('opened_at')
                    ->first();

                if (! $shift) {
                    $shift = PosShift::create([
                        'store_id' => $storeId,
                        'register_name' => 'Kasir Utama 01',
                        'cashier_user_id' => $cashierId,
                        'opening_cash' => 200000,
                        'status' => 'open',
                        'note' => 'Shift dibuka otomatis saat transaksi POS',
                        'opened_at' => now(),
                    ]);
                }

                // 3. Process items and verify stock with pessimistic lock
                $processedItems = [];
                $calculatedSubtotal = 0;

                foreach ($validated['items'] as $itemData) {
                    $variant = ProductVariant::with('product')
                        ->lockForUpdate()
                        ->findOrFail($itemData['variant_id']);

                    $qty = (int) $itemData['quantity'];
                    $price = (float) $itemData['unit_price'];

                    // Stock availability check
                    if ($variant->stock_quantity < $qty) {
                        $productName = $variant->product?->name . ' (' . $variant->variant_name . ')';
                        throw new \InvalidArgumentException("Stok tidak mencukupi untuk {$productName}. Sisa stok: {$variant->stock_quantity}.");
                    }

                    // Deduct stock
                    $newStock = $variant->stock_quantity - $qty;
                    $variant->stock_quantity = $newStock;
                    $variant->save();

                    $lineTotal = $price * $qty;
                    $calculatedSubtotal += $lineTotal;

                    $processedItems[] = [
                        'product_id' => $variant->product_id,
                        'variant_id' => $variant->id,
                        'product_name' => $variant->product?->name ?? 'Produk',
                        'variant_name' => $variant->variant_name,
                        'sku' => $variant->sku ?? '-',
                        'unit_price' => $price,
                        'quantity' => $qty,
                        'line_total' => $lineTotal,
                    ];
                }

                // 4. Calculate Totals
                $discount = (float) ($validated['discount_amount'] ?? 0);
                $tax = (float) ($validated['tax_amount'] ?? 0);
                $grandTotal = max(0, $calculatedSubtotal - $discount + $tax);
                $amountPaid = (float) $validated['amount_paid'];

                // Check cash underpayment
                if ($validated['payment_method'] === 'cash' && $amountPaid < $grandTotal) {
                    throw new \InvalidArgumentException("Uang tunai yang dibayarkan kurang! Total: Rp " . number_format($grandTotal, 0, ',', '.') . ", Diterima: Rp " . number_format($amountPaid, 0, ',', '.'));
                }

                $changeAmount = max(0, $amountPaid - $grandTotal);

                // 5. Generate Receipt & Order Number
                $timestamp = date('Ymd');
                $randomSuffix = strtoupper(Str::random(5));
                $receiptNumber = 'POS-' . $timestamp . '-' . $randomSuffix;
                $checkoutNumber = 'CHK-' . $timestamp . '-' . $randomSuffix;
                $paymentNumber = 'PAY-' . $timestamp . '-' . $randomSuffix;

                // 6. Record Stock Movements (Audit Trail)
                foreach ($processedItems as $pItem) {
                    StockMovement::create([
                        'store_id' => $storeId,
                        'variant_id' => $pItem['variant_id'],
                        'movement_type' => 'out',
                        'quantity_delta' => -$pItem['quantity'],
                        'quantity_after' => ProductVariant::where('id', $pItem['variant_id'])->value('stock_quantity') ?? 0,
                        'reference_type' => 'pos_sale',
                        'adjustment_number' => $receiptNumber,
                        'note' => 'Penjualan POS Kasir #' . $receiptNumber,
                        'created_by' => $cashierId,
                    ]);
                }

                // 7. Create PosSale record
                $paymentsPayload = [
                    [
                        'method' => $validated['payment_method'],
                        'amount_paid' => $amountPaid,
                        'change_amount' => $changeAmount,
                        'paid_at' => now()->toIso8601String(),
                        'reference' => $validated['payment_method'] === 'qris' ? 'QRIS-' . strtoupper(Str::random(8)) : null,
                    ]
                ];

                $posSale = PosSale::create([
                    'store_id' => $storeId,
                    'shift_id' => $shift->id,
                    'cashier_user_id' => $cashierId,
                    'customer_user_id' => $validated['customer_user_id'] ?? null,
                    'receipt_number' => $receiptNumber,
                    'items' => $processedItems,
                    'payments' => $paymentsPayload,
                    'subtotal_amount' => $calculatedSubtotal,
                    'discount_amount' => $discount,
                    'tax_amount' => $tax,
                    'total_amount' => $grandTotal,
                    'status' => 'completed',
                    'note' => $validated['note'] ?? null,
                    'sold_at' => now(),
                ]);

                // 8. Create Order and OrderItems (Synced with Orders module)
                $order = Order::create([
                    'checkout_number' => $checkoutNumber,
                    'order_number' => $receiptNumber,
                    'buyer_user_id' => $validated['customer_user_id'] ?? $cashierId,
                    'store_id' => $storeId,
                    'status' => 'delivered', // Immediate physical POS completion
                    'shipping_address' => [
                        'type' => 'in_store_pickup',
                        'recipient_name' => $validated['customer_name'] ?? 'Pelanggan Umum (Kasir POS)',
                        'address_line1' => $store->name . ' (Transaksi Kasir Fisik)',
                    ],
                    'subtotal_amount' => $calculatedSubtotal,
                    'shipping_amount' => 0,
                    'discount_amount' => $discount,
                    'service_fee' => 0,
                    'total_amount' => $grandTotal,
                    'buyer_note' => $validated['note'] ?? 'Transaksi Kasir POS Offline',
                    'courier' => 'POS In-Store',
                    'shipment_status' => 'delivered',
                    'shipped_at' => now(),
                    'delivered_at' => now(),
                    'placed_at' => now(),
                ]);

                foreach ($processedItems as $pItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $pItem['product_id'],
                        'variant_id' => $pItem['variant_id'],
                        'product_name_snapshot' => $pItem['product_name'],
                        'variant_snapshot' => ['variant_name' => $pItem['variant_name'], 'sku' => $pItem['sku']],
                        'sku_snapshot' => $pItem['sku'],
                        'unit_price' => $pItem['unit_price'],
                        'quantity' => $pItem['quantity'],
                        'discount_amount' => 0,
                        'line_total' => $pItem['line_total'],
                        'created_at' => now(),
                    ]);
                }

                // 9. Create Payment record
                Payment::create([
                    'payment_number' => $paymentNumber,
                    'kind' => 'payment',
                    'checkout_number' => $checkoutNumber,
                    'order_id' => $order->id,
                    'method' => $validated['payment_method'],
                    'provider' => $validated['payment_method'] === 'qris' ? 'QRIS Nasional' : 'Tunai Kasir',
                    'provider_reference' => $receiptNumber,
                    'amount' => $grandTotal,
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // 10. Record Financial Entry (Income)
                FinancialEntry::create([
                    'store_id' => $storeId,
                    'entry_date' => now(),
                    'direction' => 'income',
                    'entry_type' => 'pos_sale',
                    'category' => 'Penjualan Kasir POS',
                    'amount' => $grandTotal,
                    'description' => "Penjualan Kasir POS #{$receiptNumber}",
                    'source_table' => 'pos_sales',
                    'source_id' => $posSale->id,
                    'created_by' => $cashierId,
                ]);

                return [
                    'pos_sale' => $posSale,
                    'order' => $order,
                    'receipt_number' => $receiptNumber,
                    'store_name' => $store->name,
                    'cashier_name' => $cashier?->full_name ?? 'Kasir Utama',
                    'customer_name' => $validated['customer_name'] ?? 'Pelanggan Umum',
                    'items' => $processedItems,
                    'subtotal' => $calculatedSubtotal,
                    'subtotal_formatted' => 'Rp ' . number_format($calculatedSubtotal, 0, ',', '.'),
                    'discount' => $discount,
                    'discount_formatted' => 'Rp ' . number_format($discount, 0, ',', '.'),
                    'tax' => $tax,
                    'tax_formatted' => 'Rp ' . number_format($tax, 0, ',', '.'),
                    'total' => $grandTotal,
                    'total_formatted' => 'Rp ' . number_format($grandTotal, 0, ',', '.'),
                    'payment_method' => strtoupper($validated['payment_method']),
                    'amount_paid' => $amountPaid,
                    'amount_paid_formatted' => 'Rp ' . number_format($amountPaid, 0, ',', '.'),
                    'change' => $changeAmount,
                    'change_formatted' => 'Rp ' . number_format($changeAmount, 0, ',', '.'),
                    'date' => now()->format('d/m/Y H:i:s'),
                ];
            });

            return $this->successResponse($transactionResult, 'Transaksi kasir berhasil diselesaikan.', 201);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), 422);
        } catch (\Throwable $e) {
            return $this->errorResponse('Terjadi kesalahan saat memproses transaksi: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get printable receipt data by receipt number or PosSale ID.
     */
    public function receipt(string $id): JsonResponse
    {
        $sale = PosSale::with(['store', 'cashier'])
            ->where('id', $id)
            ->orWhere('receipt_number', $id)
            ->first();

        if (! $sale) {
            return $this->errorResponse('Data struk tidak ditemukan.', 404);
        }

        $payments = $sale->payments ?? [];
        $firstPayment = !empty($payments) && is_array($payments) ? $payments[0] : null;

        return $this->successResponse([
            'id' => $sale->id,
            'receipt_number' => $sale->receipt_number,
            'store_name' => $sale->store?->name ?? 'UMKMGo Store',
            'cashier_name' => $sale->cashier?->full_name ?? 'Kasir',
            'customer_name' => 'Pelanggan Umum',
            'items' => $sale->items ?? [],
            'subtotal' => (float) $sale->subtotal_amount,
            'subtotal_formatted' => 'Rp ' . number_format($sale->subtotal_amount, 0, ',', '.'),
            'discount' => (float) $sale->discount_amount,
            'discount_formatted' => 'Rp ' . number_format($sale->discount_amount, 0, ',', '.'),
            'tax' => (float) $sale->tax_amount,
            'tax_formatted' => 'Rp ' . number_format($sale->tax_amount, 0, ',', '.'),
            'total' => (float) $sale->total_amount,
            'total_formatted' => 'Rp ' . number_format($sale->total_amount, 0, ',', '.'),
            'payment_method' => strtoupper($firstPayment['method'] ?? 'CASH'),
            'amount_paid' => (float) ($firstPayment['amount_paid'] ?? $sale->total_amount),
            'amount_paid_formatted' => 'Rp ' . number_format($firstPayment['amount_paid'] ?? $sale->total_amount, 0, ',', '.'),
            'change' => (float) ($firstPayment['change_amount'] ?? 0),
            'change_formatted' => 'Rp ' . number_format($firstPayment['change_amount'] ?? 0, 0, ',', '.'),
            'date' => $sale->sold_at?->format('d/m/Y H:i:s') ?? now()->format('d/m/Y H:i:s'),
            'note' => $sale->note,
        ], 'Data struk berhasil dimuat.');
    }
}
