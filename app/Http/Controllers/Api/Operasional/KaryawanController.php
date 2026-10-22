<?php

namespace App\Http\Controllers\Api\Operasional;

use App\Http\Controllers\Controller;
use App\Models\PosShift;
use App\Models\Store;
use App\Models\StoreMember;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    private function resolveStoreId(?string $storeId = null): ?string
    {
        return $storeId ?: Store::value('id');
    }

    private function roleLabel(string $role): string
    {
        return match ($role) {
            'cashier' => 'Kasir Utama',
            'seller_staff' => 'Staf Operasional',
            'manager' => 'Manajer Toko',
            'seller_owner' => 'Owner / Pemilik',
            default => ucfirst(str_replace('_', ' ', $role)),
        };
    }

    private function shiftLabel(string $shift): string
    {
        return match ($shift) {
            'malam' => 'Shift Malam (16:00 - 24:00)',
            default => 'Shift Pagi (08:00 - 16:00)',
        };
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

        $membersQuery = StoreMember::where('store_id', $storeId)->with('user');

        if ($request->filled('search')) {
            $search = trim($request->query('search'));
            $membersQuery->whereHas('user', function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->query('status');
            if ($status === 'active') {
                $membersQuery->where('is_active', true);
            } elseif ($status === 'inactive') {
                $membersQuery->where('is_active', false);
            }
        }

        $members = $membersQuery->get();

        $allMembers = StoreMember::where('store_id', $storeId)->with('user')->get();
        $totalEmployees = $allMembers->count();
        $shiftPagiCount = $allMembers->filter(function ($m) {
            return data_get($m->user?->notification_prefs, 'shift', 'pagi') === 'pagi';
        })->count();
        $shiftMalamCount = $allMembers->filter(function ($m) {
            return data_get($m->user?->notification_prefs, 'shift') === 'malam';
        })->count();
        $activeShiftsCount = PosShift::where('store_id', $storeId)->where('status', 'open')->count();

        $transformed = $members->map(function ($member) {
            $user = $member->user;
            $shiftKey = data_get($user?->notification_prefs, 'shift', 'pagi');
            return [
                'id' => $user?->id,
                'name' => $user?->full_name,
                'email' => $user?->email,
                'phone' => $user?->phone ?? '-',
                'role' => $this->roleLabel($member->role),
                'role_key' => $member->role,
                'shift' => $this->shiftLabel($shiftKey),
                'shift_key' => $shiftKey,
                'is_active' => (bool) $member->is_active,
                'status_label' => $member->is_active ? 'Aktif' : 'Nonaktif',
                'joined_at' => $member->joined_at?->format('d M Y'),
                'initials' => collect(explode(' ', $user?->full_name ?? 'K'))
                    ->map(fn($w) => strtoupper(substr($w, 0, 1)))
                    ->take(2)
                    ->join(''),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $transformed,
            'summary' => [
                'total_employees' => $totalEmployees,
                'shift_pagi' => $shiftPagiCount,
                'shift_malam' => $shiftMalamCount,
                'active_shifts' => $activeShiftsCount,
            ],
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
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30',
            'role' => 'required|string|in:cashier,seller_staff,manager,seller_owner',
            'shift' => 'nullable|string|in:pagi,malam',
            'is_active' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $shiftKey = $request->input('shift', 'pagi');
        $isActive = $request->boolean('is_active', true);

        $user = User::create([
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'password_hash' => Hash::make($request->input('password') ?: 'password123'),
            'role' => $request->input('role'),
            'status' => $isActive ? 'active' : 'inactive',
            'notification_prefs' => ['shift' => $shiftKey],
            'email_verified_at' => now(),
        ]);

        StoreMember::create([
            'store_id' => $storeId,
            'user_id' => $user->id,
            'role' => $request->input('role'),
            'is_active' => $isActive,
            'joined_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil ditambahkan',
            'data' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone ?? '-',
                'role' => $this->roleLabel($user->role),
                'role_key' => $user->role,
                'shift' => $this->shiftLabel($shiftKey),
                'shift_key' => $shiftKey,
                'is_active' => $isActive,
                'status_label' => $isActive ? 'Aktif' : 'Nonaktif',
            ],
        ], 201);
    }

    public function show(string $id, Request $request): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->query('store_id'));
        $member = StoreMember::where('store_id', $storeId)->where('user_id', $id)->with('user')->first();

        if (!$member || !$member->user) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan',
            ], 404);
        }

        $user = $member->user;
        $shiftKey = data_get($user->notification_prefs, 'shift', 'pagi');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $member->role,
                'role_label' => $this->roleLabel($member->role),
                'shift' => $shiftKey,
                'shift_label' => $this->shiftLabel($shiftKey),
                'is_active' => (bool) $member->is_active,
                'joined_at' => $member->joined_at?->format('d M Y'),
            ],
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->input('store_id'));
        $member = StoreMember::where('store_id', $storeId)->where('user_id', $id)->first();
        $user = User::find($id);

        if (!$member || !$user) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:120',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'phone' => 'nullable|string|max:30',
            'role' => 'required|string|in:cashier,seller_staff,manager,seller_owner',
            'shift' => 'nullable|string|in:pagi,malam',
            'is_active' => 'nullable|boolean',
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $shiftKey = $request->input('shift', data_get($user->notification_prefs, 'shift', 'pagi'));
        $isActive = $request->has('is_active') ? $request->boolean('is_active') : $member->is_active;

        $userUpdates = [
            'full_name' => $request->input('full_name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'role' => $request->input('role'),
            'status' => $isActive ? 'active' : 'inactive',
            'notification_prefs' => array_merge($user->notification_prefs ?? [], ['shift' => $shiftKey]),
        ];

        if ($request->filled('password')) {
            $userUpdates['password_hash'] = Hash::make($request->input('password'));
        }

        $user->update($userUpdates);

        StoreMember::where('store_id', $storeId)->where('user_id', $id)->update([
            'role' => $request->input('role'),
            'is_active' => $isActive,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data karyawan berhasil diperbarui',
            'data' => [
                'id' => $user->id,
                'name' => $user->full_name,
                'email' => $user->email,
                'phone' => $user->phone ?? '-',
                'role' => $this->roleLabel($user->role),
                'role_key' => $user->role,
                'shift' => $this->shiftLabel($shiftKey),
                'shift_key' => $shiftKey,
                'is_active' => $isActive,
                'status_label' => $isActive ? 'Aktif' : 'Nonaktif',
            ],
        ]);
    }

    public function destroy(string $id, Request $request): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->input('store_id'));
        $member = StoreMember::where('store_id', $storeId)->where('user_id', $id)->first();

        if (!$member) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan',
            ], 404);
        }

        if ($member->role === 'seller_owner') {
            return response()->json([
                'success' => false,
                'message' => 'Pemilik usaha tidak dapat dihapus',
            ], 422);
        }

        StoreMember::where('store_id', $storeId)->where('user_id', $id)->delete();
        User::where('id', $id)->where('role', '!=', 'seller_owner')->delete();

        return response()->json([
            'success' => true,
            'message' => 'Karyawan berhasil dihapus',
        ]);
    }

    public function assignShift(Request $request, string $id): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->input('store_id'));
        $member = StoreMember::where('store_id', $storeId)->where('user_id', $id)->first();
        $user = User::find($id);

        if (!$member || !$user) {
            return response()->json([
                'success' => false,
                'message' => 'Karyawan tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'shift' => 'required|string|in:pagi,malam',
            'open_pos_shift' => 'nullable|boolean',
            'register_name' => 'nullable|string|max:100',
            'opening_cash' => 'nullable|numeric|min:0',
            'note' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $shiftKey = $request->input('shift');
        $prefs = $user->notification_prefs ?? [];
        $prefs['shift'] = $shiftKey;
        $user->update(['notification_prefs' => $prefs]);

        $newShift = null;
        if ($request->boolean('open_pos_shift')) {
            $newShift = PosShift::create([
                'store_id' => $storeId,
                'register_name' => $request->input('register_name', 'Kasir Utama #1'),
                'cashier_user_id' => $user->id,
                'opening_cash' => $request->input('opening_cash', 0),
                'status' => 'open',
                'note' => $request->input('note'),
                'opened_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Shift kerja berhasil diperbarui',
            'data' => [
                'user_id' => $user->id,
                'shift' => $this->shiftLabel($shiftKey),
                'shift_key' => $shiftKey,
                'pos_shift_id' => $newShift?->id,
            ],
        ]);
    }

    public function shifts(Request $request): JsonResponse
    {
        $storeId = $this->resolveStoreId($request->query('store_id'));
        $shifts = PosShift::where('store_id', $storeId)
            ->with('cashier')
            ->orderByDesc('opened_at')
            ->limit(20)
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'register_name' => $s->register_name,
                    'cashier_name' => $s->cashier?->full_name ?? 'Staf Kasir',
                    'opening_cash' => (float) $s->opening_cash,
                    'closing_cash' => $s->closing_cash ? (float) $s->closing_cash : null,
                    'status' => $s->status,
                    'note' => $s->note,
                    'opened_at' => $s->opened_at?->format('d M Y H:i'),
                    'closed_at' => $s->closed_at?->format('d M Y H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $shifts,
        ]);
    }
}
