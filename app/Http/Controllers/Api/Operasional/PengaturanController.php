<?php

namespace App\Http\Controllers\Api\Operasional;

use App\Http\Controllers\Controller;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PengaturanController extends Controller
{
    private function resolveStore(?string $storeId = null): ?Store
    {
        return $storeId 
            ? Store::with('owner')->find($storeId)
            : Store::with('owner')->first();
    }

    private function defaultSettings(): array
    {
        return [
            'tax' => [
                'enabled' => false,
                'rate' => 10,
                'service_fee_rate' => 0,
            ],
            'receipt' => [
                'header' => 'Terima kasih atas kunjungan Anda!',
                'footer' => 'Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan.',
                'show_logo' => true,
                'show_queue_number' => true,
                'wifi_info' => 'WiFi: UMKMGo_Free / pass: amikom123',
            ],
            'printer' => [
                'paper_width' => '58mm',
                'auto_print' => true,
                'kitchen_copy' => false,
            ],
        ];
    }

    public function show(Request $request): JsonResponse
    {
        $store = $this->resolveStore($request->query('store_id'));

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store tidak ditemukan',
            ], 404);
        }

        $currentSettings = array_replace_recursive($this->defaultSettings(), $store->settings ?? []);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $store->id,
                'name' => $store->name,
                'owner_name' => $store->owner?->full_name ?? '',
                'phone' => $store->phone ?? '',
                'email' => $store->email ?? '',
                'business_type' => $store->business_type ?? '',
                'address_line' => $store->address_line ?? '',
                'city' => $store->city ?? '',
                'province' => $store->province ?? '',
                'postal_code' => $store->postal_code ?? '',
                'description' => $store->description ?? '',
                'logo_url' => $store->logo_url ?? '',
                'status' => $store->status,
                'settings' => $currentSettings,
            ],
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $store = $this->resolveStore($request->input('store_id'));

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:150',
            'owner_name' => 'nullable|string|max:120',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:255',
            'business_type' => 'nullable|string|max:80',
            'address_line' => 'nullable|string',
            'city' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|string',
            'settings' => 'nullable|array',
            'settings.tax' => 'nullable|array',
            'settings.tax.enabled' => 'nullable|boolean',
            'settings.tax.rate' => 'nullable|numeric|min:0|max:100',
            'settings.tax.service_fee_rate' => 'nullable|numeric|min:0|max:100',
            'settings.receipt' => 'nullable|array',
            'settings.receipt.header' => 'nullable|string|max:255',
            'settings.receipt.footer' => 'nullable|string|max:255',
            'settings.receipt.show_logo' => 'nullable|boolean',
            'settings.receipt.show_queue_number' => 'nullable|boolean',
            'settings.receipt.wifi_info' => 'nullable|string|max:255',
            'settings.printer' => 'nullable|array',
            'settings.printer.paper_width' => 'nullable|string|in:58mm,80mm',
            'settings.printer.auto_print' => 'nullable|boolean',
            'settings.printer.kitchen_copy' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $storeUpdates = [];

        if ($request->has('name')) $storeUpdates['name'] = $request->input('name');
        if ($request->has('phone')) $storeUpdates['phone'] = $request->input('phone');
        if ($request->has('email')) $storeUpdates['email'] = $request->input('email');
        if ($request->has('business_type')) $storeUpdates['business_type'] = $request->input('business_type');
        if ($request->has('address_line')) $storeUpdates['address_line'] = $request->input('address_line');
        if ($request->has('city')) $storeUpdates['city'] = $request->input('city');
        if ($request->has('description')) $storeUpdates['description'] = $request->input('description');
        if ($request->has('logo_url')) $storeUpdates['logo_url'] = $request->input('logo_url');

        if ($request->filled('owner_name') && $store->owner) {
            $store->owner->update(['full_name' => $request->input('owner_name')]);
        }

        if ($request->has('settings')) {
            $currentSettings = array_replace_recursive($this->defaultSettings(), $store->settings ?? []);
            $newSettings = array_replace_recursive($currentSettings, $request->input('settings'));
            $storeUpdates['settings'] = $newSettings;
        }

        if (!empty($storeUpdates)) {
            $store->update($storeUpdates);
        }

        $store->load('owner');
        $mergedSettings = array_replace_recursive($this->defaultSettings(), $store->settings ?? []);

        return response()->json([
            'success' => true,
            'message' => 'Pengaturan toko berhasil diperbarui',
            'data' => [
                'id' => $store->id,
                'name' => $store->name,
                'owner_name' => $store->owner?->full_name ?? '',
                'phone' => $store->phone ?? '',
                'email' => $store->email ?? '',
                'business_type' => $store->business_type ?? '',
                'address_line' => $store->address_line ?? '',
                'city' => $store->city ?? '',
                'description' => $store->description ?? '',
                'logo_url' => $store->logo_url ?? '',
                'settings' => $mergedSettings,
            ],
        ]);
    }

    public function uploadLogo(Request $request): JsonResponse
    {
        $store = $this->resolveStore($request->input('store_id'));

        if (!$store) {
            return response()->json([
                'success' => false,
                'message' => 'Store tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'logo' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'File logo tidak valid',
                'errors' => $validator->errors(),
            ], 422);
        }

        $file = $request->file('logo');
        $filename = 'store_logo_' . $store->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('stores/logos', $filename, 'public');
        $logoUrl = Storage::url($path);

        $store->update(['logo_url' => $logoUrl]);

        return response()->json([
            'success' => true,
            'message' => 'Logo toko berhasil diunggah',
            'logo_url' => $logoUrl,
        ]);
    }
}
