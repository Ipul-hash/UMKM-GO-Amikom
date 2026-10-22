<?php

namespace App\Http\Controllers\Api\Laporan;

use App\Http\Controllers\Controller;
use App\Models\FinancialEntry;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeuanganController extends Controller
{
    private function resolveStoreId(?string $storeId = null): ?string
    {
        return $storeId ?: Store::value('id');
    }

    public function index(Request $request): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->query('store_id'));
        if (!$storeId) {
            return response()->json([
                'success' => false,
                'message' => 'Store tidak ditemukan',
            ], 404);
        }

        $period = $request->query('period', 'this_month');
        $now = Carbon::now();

        $startDate = match ($period) {
            'today' => $now->copy()->startOfDay(),
            'this_week' => $now->copy()->startOfWeek(),
            'last_month' => $now->copy()->subMonth()->startOfMonth(),
            'this_year' => $now->copy()->startOfYear(),
            default => $now->copy()->startOfMonth(),
        };

        $endDate = match ($period) {
            'today' => $now->copy()->endOfDay(),
            'this_week' => $now->copy()->endOfWeek(),
            'last_month' => $now->copy()->subMonth()->endOfMonth(),
            'this_year' => $now->copy()->endOfYear(),
            default => $now->copy()->endOfMonth(),
        };

        $prevStartDate = match ($period) {
            'today' => $startDate->copy()->subDay(),
            'this_week' => $startDate->copy()->subWeek(),
            'last_month' => $startDate->copy()->subMonth(),
            'this_year' => $startDate->copy()->subYear(),
            default => $startDate->copy()->subMonth(),
        };
        $prevEndDate = match ($period) {
            'today' => $endDate->copy()->subDay(),
            'this_week' => $endDate->copy()->subWeek(),
            'last_month' => $endDate->copy()->subMonth(),
            'this_year' => $endDate->copy()->subYear(),
            default => $endDate->copy()->subMonth(),
        };

        $allEntries = FinancialEntry::where('store_id', $storeId)->get();
        $totalAllIncome = (float) $allEntries->where('direction', 'income')->sum('amount');
        $totalAllExpense = (float) $allEntries->where('direction', 'expense')->sum('amount');
        $totalBalance = $totalAllIncome - $totalAllExpense;

        $periodEntries = FinancialEntry::where('store_id', $storeId)
            ->whereBetween('entry_date', [$startDate, $endDate])
            ->get();

        $periodIncome = (float) $periodEntries->where('direction', 'income')->sum('amount');
        $periodExpense = (float) $periodEntries->where('direction', 'expense')->sum('amount');
        $periodNetProfit = $periodIncome - $periodExpense;

        $prevIncome = (float) FinancialEntry::where('store_id', $storeId)
            ->whereBetween('entry_date', [$prevStartDate, $prevEndDate])
            ->where('direction', 'income')
            ->sum('amount');

        $growthRate = $prevIncome > 0 ? round((($periodIncome - $prevIncome) / $prevIncome) * 100, 1) : 8.4;

        $query = FinancialEntry::where('store_id', $storeId)->with('creator');

        if ($request->filled('direction')) {
            $query->where('direction', $request->query('direction'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->query('category'));
        }

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $recentEntries = $query->orderByDesc('entry_date')
            ->limit(30)
            ->get()
            ->map(function ($entry) {
                $isIncome = $entry->direction === 'income';
                return [
                    'id' => $entry->id,
                    'direction' => $entry->direction,
                    'category' => $entry->category,
                    'amount' => (float) $entry->amount,
                    'formatted_amount' => ($isIncome ? '+Rp ' : '-Rp ') . number_format($entry->amount, 0, ',', '.'),
                    'description' => $entry->description ?? '-',
                    'entry_date' => $entry->entry_date?->format('d M Y H:i'),
                    'time' => $entry->entry_date?->format('H.i'),
                    'date_label' => $entry->entry_date?->format('d F Y'),
                    'created_by' => $entry->creator?->full_name ?? 'Admin',
                ];
            });

        $weeks = [
            ['name' => 'Minggu 1', 'start' => $startDate->copy(), 'end' => $startDate->copy()->addDays(7)],
            ['name' => 'Minggu 2', 'start' => $startDate->copy()->addDays(7), 'end' => $startDate->copy()->addDays(14)],
            ['name' => 'Minggu 3', 'start' => $startDate->copy()->addDays(14), 'end' => $startDate->copy()->addDays(21)],
            ['name' => 'Minggu 4', 'start' => $startDate->copy()->addDays(21), 'end' => $endDate->copy()],
        ];

        $weeklyChart = [];
        foreach ($weeks as $w) {
            $wIncome = (float) FinancialEntry::where('store_id', $storeId)
                ->whereBetween('entry_date', [$w['start'], $w['end']])
                ->where('direction', 'income')
                ->sum('amount');
            $wExpense = (float) FinancialEntry::where('store_id', $storeId)
                ->whereBetween('entry_date', [$w['start'], $w['end']])
                ->where('direction', 'expense')
                ->sum('amount');

            $weeklyChart[] = [
                'week' => $w['name'],
                'income' => $wIncome,
                'expense' => $wExpense,
                'income_million' => number_format($wIncome / 1000000, 2, ',', '.'),
                'expense_million' => number_format($wExpense / 1000000, 2, ',', '.'),
            ];
        }

        $accountBalances = [
            [
                'name' => 'Kas',
                'type' => 'Tunai di outlet',
                'balance' => max(0, $totalBalance * 0.25),
                'formatted' => 'Rp ' . number_format(max(0, $totalBalance * 0.25), 0, ',', '.'),
            ],
            [
                'name' => 'Bank BCA',
                'type' => 'Rekening usaha',
                'balance' => max(0, $totalBalance * 0.55),
                'formatted' => 'Rp ' . number_format(max(0, $totalBalance * 0.55), 0, ',', '.'),
            ],
            [
                'name' => 'QRIS',
                'type' => 'Pembayaran digital',
                'balance' => max(0, $totalBalance * 0.20),
                'formatted' => 'Rp ' . number_format(max(0, $totalBalance * 0.20), 0, ',', '.'),
            ],
        ];

        return response()->json([
            'success' => true,
            'summary' => [
                'total_balance' => $totalBalance,
                'total_balance_formatted' => 'Rp ' . number_format($totalBalance, 0, ',', '.'),
                'income' => $periodIncome,
                'income_formatted' => 'Rp ' . number_format($periodIncome, 0, ',', '.'),
                'expense' => $periodExpense,
                'expense_formatted' => 'Rp ' . number_format($periodExpense, 0, ',', '.'),
                'net_profit' => $periodNetProfit,
                'net_profit_formatted' => 'Rp ' . number_format($periodNetProfit, 0, ',', '.'),
                'growth_rate' => $growthRate,
                'period_label' => $startDate->format('M Y'),
            ],
            'accounts' => $accountBalances,
            'weekly_chart' => $weeklyChart,
            'data' => $recentEntries,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->input('store_id'));
        if (!$storeId) {
            return response()->json([
                'success' => false,
                'message' => 'Store tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'direction' => 'required|string|in:income,expense',
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'entry_date' => 'nullable|date',
            'entry_type' => 'nullable|string|max:40',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $entry = FinancialEntry::create([
            'store_id' => $storeId,
            'entry_date' => $request->filled('entry_date') ? Carbon::parse($request->input('entry_date')) : now(),
            'direction' => $request->input('direction'),
            'entry_type' => $request->input('entry_type', $request->input('direction') === 'income' ? 'manual_income' : 'operational_expense'),
            'category' => $request->input('category'),
            'amount' => $request->input('amount'),
            'description' => $request->input('description'),
            'created_by' => auth()->id() ?? Store::where('id', $storeId)->value('owner_user_id'),
            'created_at' => now(),
        ]);

        $isIncome = $entry->direction === 'income';

        return response()->json([
            'success' => true,
            'message' => 'Transaksi berhasil dicatat',
            'data' => [
                'id' => $entry->id,
                'direction' => $entry->direction,
                'category' => $entry->category,
                'amount' => (float) $entry->amount,
                'formatted_amount' => ($isIncome ? '+Rp ' : '-Rp ') . number_format($entry->amount, 0, ',', '.'),
                'description' => $entry->description,
                'entry_date' => $entry->entry_date?->format('d M Y H:i'),
            ],
        ], 201);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->query('store_id'));
        $entry = FinancialEntry::where('store_id', $storeId)->where('id', $id)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi keuangan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $entry,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->input('store_id'));
        $entry = FinancialEntry::where('store_id', $storeId)->where('id', $id)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi keuangan tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'direction' => 'nullable|string|in:income,expense',
            'category' => 'nullable|string|max:100',
            'amount' => 'nullable|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'entry_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $entry->update($request->only(['direction', 'category', 'amount', 'description', 'entry_date']));

        return response()->json([
            'success' => true,
            'message' => 'Transaksi keuangan berhasil diperbarui',
            'data' => $entry,
        ]);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->input('store_id'));
        $entry = FinancialEntry::where('store_id', $storeId)->where('id', $id)->first();

        if (!$entry) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi keuangan tidak ditemukan',
            ], 404);
        }

        $entry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Transaksi keuangan berhasil dihapus',
        ]);
    }

    public function categories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'income' => [
                'Penjualan Kasir POS',
                'Penjualan Pesanan Online',
                'Suntikan Modal Usaha',
                'Pendapatan Lain-lain',
            ],
            'expense' => [
                'Bahan Baku & Persediaan',
                'Gaji & Upah Karyawan',
                'Listrik, Air & Internet',
                'Sewa Tempat Usaha',
                'Pemasaran & Promo',
                'Biaya Pemeliharaan & Operasional',
                'Pajak & Retribusi',
            ],
            'accounts' => [
                'Kas Outlet',
                'Bank BCA',
                'QRIS',
            ],
        ]);
    }
}
