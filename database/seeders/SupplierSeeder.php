<?php

namespace Database\Seeders;

use App\Models\Store;
use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $store = Store::first();
        if (! $store || Supplier::count() > 0) {
            return;
        }

        Supplier::create([
            'store_id' => $store->id,
            'name' => 'PT Biji Kopi Nusantara',
            'supply_category' => 'Biji Kopi',
            'pic_name' => 'Pak Haryanto',
            'phone' => '0812-9988-7766',
            'email' => 'haryanto@bijikopi.co.id',
            'city' => 'Bandung',
            'address' => 'Jl. Kopi Arabika No. 12, Bandung',
            'status' => 'active',
        ]);

        Supplier::create([
            'store_id' => $store->id,
            'name' => 'CV Packaging Murah',
            'supply_category' => 'Cup & Kemasan',
            'pic_name' => 'Ibu Ratna',
            'phone' => '0813-4455-6677',
            'email' => 'ratna@packagingmurah.com',
            'city' => 'Jakarta',
            'address' => 'Kawasan Industri Pulogadung Blok C, Jakarta',
            'status' => 'active',
        ]);

        Supplier::create([
            'store_id' => $store->id,
            'name' => 'Distro Fashion Grosir',
            'supply_category' => 'Pakaian & Tas',
            'pic_name' => 'Mas Reza',
            'phone' => '0857-1122-3344',
            'email' => 'reza@distrogrosir.id',
            'city' => 'Solo',
            'address' => 'Pusat Grosir Solo Lt. 2 No. 45, Solo',
            'status' => 'active',
        ]);
    }
}
