<?php

namespace Tests\Feature;

use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PengaturanApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = User::factory()->create([
            'full_name' => 'Adam Bagas',
            'email' => 'adam@umkmgo.id',
            'role' => 'seller_owner',
            'status' => 'active',
        ]);

        $this->store = Store::create([
            'owner_user_id' => $this->owner->id,
            'name' => 'Toko Kopi & Fashion Andi',
            'slug' => 'toko-kopi-fashion-andi',
            'phone' => '081288889999',
            'email' => 'admin@umkmgo.id',
            'business_type' => 'Kafe & Retail',
            'address_line' => 'Jl. Merdeka No. 12',
            'city' => 'Purwokerto',
            'status' => 'active',
        ]);
    }

    public function test_can_get_store_settings_and_profile(): void
    {
        $response = $this->getJson('/api/v1/operasional/pengaturan');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Toko Kopi & Fashion Andi')
            ->assertJsonPath('data.owner_name', 'Adam Bagas')
            ->assertJsonStructure([
                'success',
                'data' => [
                    'id',
                    'name',
                    'owner_name',
                    'phone',
                    'email',
                    'business_type',
                    'address_line',
                    'city',
                    'settings' => [
                        'tax' => ['enabled', 'rate', 'service_fee_rate'],
                        'receipt' => ['header', 'footer', 'show_logo', 'show_queue_number', 'wifi_info'],
                        'printer' => ['paper_width', 'auto_print', 'kitchen_copy'],
                    ],
                ],
            ]);
    }

    public function test_can_update_store_profile(): void
    {
        $payload = [
            'name' => 'Kopi Andi Specialty Store',
            'owner_name' => 'Adam Bagas Pratama',
            'phone' => '081299991111',
            'email' => 'contact@kopiandi.id',
            'business_type' => 'Specialty Coffee & Roastery',
            'address_line' => 'Jl. Prof. Dr. Suharso No. 88',
            'city' => 'Banyumas',
        ];

        $response = $this->putJson('/api/v1/operasional/pengaturan', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.name', 'Kopi Andi Specialty Store')
            ->assertJsonPath('data.owner_name', 'Adam Bagas Pratama')
            ->assertJsonPath('data.phone', '081299991111');

        $this->assertDatabaseHas('stores', [
            'id' => $this->store->id,
            'name' => 'Kopi Andi Specialty Store',
            'phone' => '081299991111',
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $this->owner->id,
            'full_name' => 'Adam Bagas Pratama',
        ]);
    }

    public function test_can_update_tax_and_fees_settings(): void
    {
        $payload = [
            'settings' => [
                'tax' => [
                    'enabled' => true,
                    'rate' => 11,
                    'service_fee_rate' => 5,
                ],
            ],
        ];

        $response = $this->putJson('/api/v1/operasional/pengaturan', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.settings.tax.enabled', true)
            ->assertJsonPath('data.settings.tax.rate', 11)
            ->assertJsonPath('data.settings.tax.service_fee_rate', 5);
    }

    public function test_can_update_receipt_settings(): void
    {
        $payload = [
            'settings' => [
                'receipt' => [
                    'header' => 'Selamat Berbelanja di Kopi Andi',
                    'footer' => 'Simpan struk ini sebagai bukti pembayaran sah',
                    'show_logo' => true,
                    'show_queue_number' => false,
                    'wifi_info' => 'FreeWiFi: KopiAndi / pass: kopienak',
                ],
            ],
        ];

        $response = $this->putJson('/api/v1/operasional/pengaturan', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.settings.receipt.header', 'Selamat Berbelanja di Kopi Andi')
            ->assertJsonPath('data.settings.receipt.wifi_info', 'FreeWiFi: KopiAndi / pass: kopienak');
    }

    public function test_can_update_pos_printer_settings(): void
    {
        $payload = [
            'settings' => [
                'printer' => [
                    'paper_width' => '80mm',
                    'auto_print' => true,
                    'kitchen_copy' => true,
                ],
            ],
        ];

        $response = $this->putJson('/api/v1/operasional/pengaturan', $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.settings.printer.paper_width', '80mm')
            ->assertJsonPath('data.settings.printer.kitchen_copy', true);
    }

    public function test_can_upload_store_logo(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('logo.png', 400, 400);

        $response = $this->postJson('/api/v1/operasional/pengaturan/upload-logo', [
            'logo' => $file,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertNotNull($response->json('logo_url'));

        $this->assertNotEmpty($this->store->fresh()->logo_url);
    }
}
