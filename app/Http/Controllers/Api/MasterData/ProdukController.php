<?php

namespace App\Http\Controllers\Api\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use App\Models\Store;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
    use ApiResponse;

    /**
     * Get list of products with filters, search, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category:id,name,slug', 'variants', 'store:id,name,slug'])
            ->where('status', '!=', 'archived');

        // Filter: Store
        if ($request->filled('store_id')) {
            $query->where('store_id', $request->query('store_id'));
        }

        // Filter: Category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->query('category_id'));
        } elseif ($request->filled('category_slug')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->query('category_slug'));
            });
        }

        // Filter: Status (active, draft, all)
        if ($request->filled('status') && $request->query('status') !== 'all') {
            $query->where('status', $request->query('status'));
        }

        // Search keyword (name, description, or variant SKU)
        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('variants', function ($vq) use ($search) {
                      $vq->where('sku', 'like', "%{$search}%")
                         ->orWhere('variant_name', 'like', "%{$search}%");
                  });
            });
        }

        // Sorting
        $sortBy = $request->query('sort_by', 'latest');
        switch ($sortBy) {
            case 'oldest':
                $query->oldest();
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
                break;
        }

        $perPage = (int) $request->query('per_page', 10);
        $products = $query->paginate($perPage);

        // Transform results to include calculated stats
        $transformed = $products->getCollection()->map(function ($product) {
            $totalStock = $product->variants->sum('stock_quantity');
            $minPrice = $product->variants->min('price') ?? 0;
            $maxPrice = $product->variants->max('price') ?? 0;
            $firstImage = !empty($product->images) && is_array($product->images) ? $product->images[0] : null;

            return [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'category' => [
                    'id' => $product->category?->id,
                    'name' => $product->category?->name,
                    'slug' => $product->category?->slug,
                ],
                'condition' => $product->condition,
                'status' => $product->status,
                'weight_gram' => $product->weight_gram,
                'images' => $product->images,
                'thumbnail' => $firstImage ? asset(ltrim($firstImage, '/')) : asset('images/dashboard/coffee-americano.jpg'),
                'total_stock' => $totalStock,
                'min_price' => (float) $minPrice,
                'max_price' => (float) $maxPrice,
                'price_formatted' => 'Rp ' . number_format($minPrice, 0, ',', '.'),
                'variants_count' => $product->variants->count(),
                'variants' => $product->variants,
                'created_at' => $product->created_at?->format('d M Y H:i'),
            ];
        });

        $products->setCollection($transformed);

        return $this->paginatedResponse($products, 'Katalog produk berhasil dimuat.');
    }

    /**
     * Get single product detail by ID or Slug.
     */
    public function show(string $id): JsonResponse
    {
        $product = Product::with(['category', 'variants', 'store'])
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('slug', $id);
            })
            ->first();

        if (! $product) {
            return $this->errorResponse('Produk tidak ditemukan.', 404);
        }

        return $this->successResponse($product, 'Detail produk berhasil diambil.');
    }

    /**
     * Create a new product along with variants and initial stock.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'nullable|exists:stores,id',
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:180',
            'description' => 'nullable|string',
            'condition' => 'nullable|in:baru,bekas',
            'status' => 'nullable|in:active,draft,archived',
            'weight_gram' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'images.*' => 'nullable|string',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.variant_name' => 'required|string|max:120',
            'variants.*.attributes' => 'nullable|array',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.compare_at_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'required|integer|min:0',
            'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
        ], [
            'name.required' => 'Nama produk wajib diisi.',
            'category_id.required' => 'Kategori produk wajib dipilih.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            'variants.required' => 'Minimal harus menambahkan satu varian produk.',
            'variants.*.variant_name.required' => 'Nama varian produk wajib diisi.',
            'variants.*.price.required' => 'Harga varian produk wajib diisi.',
            'variants.*.stock_quantity.required' => 'Jumlah stok awal varian wajib diisi.',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal.', 422, $validator->errors());
        }

        $validated = $validator->validated();

        // Resolve store_id (fallback to first store if not passed)
        $storeId = $validated['store_id'] ?? Store::value('id');
        if (! $storeId) {
            return $this->errorResponse('Toko tidak ditemukan. Pastikan toko sudah terdaftar.', 400);
        }

        try {
            $product = DB::transaction(function () use ($validated, $storeId, $request) {
                // Generate Unique Slug
                $baseSlug = Str::slug($validated['name']);
                $slug = $baseSlug;
                $counter = 1;
                while (Product::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }

                // Handle uploaded image files if multipart form
                $images = $validated['images'] ?? [];
                if ($request->hasFile('image_files')) {
                    foreach ($request->file('image_files') as $file) {
                        $path = $file->store('products', 'public');
                        $images[] = '/storage/' . $path;
                    }
                }

                // Create Product
                $newProduct = Product::create([
                    'store_id' => $storeId,
                    'category_id' => $validated['category_id'],
                    'name' => $validated['name'],
                    'slug' => $slug,
                    'description' => $validated['description'] ?? null,
                    'condition' => $validated['condition'] ?? 'baru',
                    'status' => $validated['status'] ?? 'active',
                    'weight_gram' => (int) ($validated['weight_gram'] ?? 0),
                    'images' => !empty($images) ? $images : ['/images/dashboard/coffee-americano.jpg'],
                ]);

                // Create Variants
                foreach ($validated['variants'] as $vData) {
                    $sku = !empty($vData['sku']) ? $vData['sku'] : strtoupper(Str::slug(substr($newProduct->name, 0, 3))) . '-' . strtoupper(Str::random(5));
                    $initialStock = (int) $vData['stock_quantity'];

                    $variant = ProductVariant::create([
                        'product_id' => $newProduct->id,
                        'sku' => $sku,
                        'variant_name' => $vData['variant_name'],
                        'attributes' => $vData['attributes'] ?? null,
                        'price' => $vData['price'],
                        'compare_at_price' => $vData['compare_at_price'] ?? null,
                        'stock_quantity' => $initialStock,
                        'low_stock_threshold' => (int) ($vData['low_stock_threshold'] ?? 5),
                        'is_active' => true,
                    ]);

                    // Record initial stock movement if > 0
                    if ($initialStock > 0) {
                        StockMovement::create([
                            'store_id' => $storeId,
                            'variant_id' => $variant->id,
                            'movement_type' => 'in',
                            'quantity_delta' => $initialStock,
                            'quantity_after' => $initialStock,
                            'reference_type' => 'manual_adjustment',
                            'note' => 'Stok awal penambahan produk baru',
                            'created_by' => auth()->id(),
                        ]);
                    }
                }

                return $newProduct->load(['category', 'variants']);
            });

            return $this->successResponse($product, 'Produk berhasil ditambahkan.', 201);
        } catch (\Throwable $e) {
            return $this->errorResponse('Gagal menyimpan produk: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Update an existing product and its variants.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $product = Product::with('variants')->find($id);
        if (! $product) {
            return $this->errorResponse('Produk tidak ditemukan.', 404);
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'name' => 'sometimes|required|string|max:180',
            'description' => 'nullable|string',
            'condition' => 'nullable|in:baru,bekas',
            'status' => 'nullable|in:active,draft,archived',
            'weight_gram' => 'nullable|integer|min:0',
            'images' => 'nullable|array',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.sku' => 'nullable|string|max:100',
            'variants.*.variant_name' => 'required_with:variants|string|max:120',
            'variants.*.price' => 'required_with:variants|numeric|min:0',
            'variants.*.compare_at_price' => 'nullable|numeric|min:0',
            'variants.*.stock_quantity' => 'nullable|integer|min:0',
            'variants.*.low_stock_threshold' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validasi gagal.', 422, $validator->errors());
        }

        $validated = $validator->validated();

        try {
            DB::transaction(function () use ($product, $validated) {
                // Update slug if name changed
                if (!empty($validated['name']) && $validated['name'] !== $product->name) {
                    $baseSlug = Str::slug($validated['name']);
                    $slug = $baseSlug;
                    $counter = 1;
                    while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                        $slug = "{$baseSlug}-{$counter}";
                        $counter++;
                    }
                    $product->slug = $slug;
                }

                $product->fill(array_filter([
                    'category_id' => $validated['category_id'] ?? null,
                    'name' => $validated['name'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'condition' => $validated['condition'] ?? null,
                    'status' => $validated['status'] ?? null,
                    'weight_gram' => $validated['weight_gram'] ?? null,
                    'images' => $validated['images'] ?? null,
                ], fn($val) => $val !== null));
                $product->save();

                // Update / Add variants
                if (!empty($validated['variants'])) {
                    foreach ($validated['variants'] as $vData) {
                        if (!empty($vData['id'])) {
                            // Update existing
                            $variant = ProductVariant::find($vData['id']);
                            if ($variant && $variant->product_id === $product->id) {
                                $variant->update(array_filter([
                                    'sku' => $vData['sku'] ?? $variant->sku,
                                    'variant_name' => $vData['variant_name'] ?? $variant->variant_name,
                                    'price' => $vData['price'] ?? $variant->price,
                                    'compare_at_price' => array_key_exists('compare_at_price', $vData) ? $vData['compare_at_price'] : $variant->compare_at_price,
                                    'low_stock_threshold' => $vData['low_stock_threshold'] ?? $variant->low_stock_threshold,
                                ]));
                            }
                        } else {
                            // Create new variant
                            ProductVariant::create([
                                'product_id' => $product->id,
                                'sku' => $vData['sku'] ?? strtoupper(Str::random(8)),
                                'variant_name' => $vData['variant_name'],
                                'price' => $vData['price'],
                                'compare_at_price' => $vData['compare_at_price'] ?? null,
                                'stock_quantity' => (int) ($vData['stock_quantity'] ?? 0),
                                'low_stock_threshold' => (int) ($vData['low_stock_threshold'] ?? 5),
                                'is_active' => true,
                            ]);
                        }
                    }
                }
            });

            return $this->successResponse($product->fresh(['category', 'variants']), 'Produk berhasil diperbarui.');
        } catch (\Throwable $e) {
            return $this->errorResponse('Gagal memperbarui produk: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Soft delete / archive product.
     */
    public function destroy(string $id): JsonResponse
    {
        $product = Product::find($id);
        if (! $product) {
            return $this->errorResponse('Produk tidak ditemukan.', 404);
        }

        // Archive product to preserve order history integrity
        $product->update(['status' => 'archived']);

        return $this->successResponse(null, 'Produk berhasil diarsipkan.');
    }

    /**
     * Get active categories list for dropdowns.
     */
    public function categories(): JsonResponse
    {
        $categories = Category::where('is_active', true)
            ->orderBy('sort_order', 'asc')
            ->get(['id', 'name', 'slug', 'parent_id']);

        return $this->successResponse($categories, 'Daftar kategori berhasil dimuat.');
    }
}
