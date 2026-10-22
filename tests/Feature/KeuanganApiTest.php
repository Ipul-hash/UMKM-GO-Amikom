<?php

namespace Tests\Feature;

use App\Models\FinancialEntry;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KeuanganApiTest extends TestCase
{
    use RefreshDatabase;

    protected Store $store;
    protected User $user;
    protected FinancialEntry $entry;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'seller_owner',
            'status' => 'active',
        ]);

        $this->store = Store::create([
            'owner_user_id' => $this->user->id,
            'name' => 'Toko Amikom Utama',
            'slug' => 'toko-amikom-utama',
            'phone' => '081234567890',
            'email' => 'toko@amikom.ac.id',
            'status' => 'active',
        ]);

        $this->entry = FinancialEntry::create([
            'store_id' => $this->store->id,
            'entry_date' => now(),
            'direction' => 'income',
            'entry_type' => 'pos_sales',
            'category' => 'Penjualan Kasir POS',
            'amount' => 150000.00,
            'description' => 'Penjualan Shift Pagi',
            'created_by' => $this->user->id,
        ]);
    }

    public function test_can_get_finance_index_and_summary_kpis(): void
    {
        $response = $this->getJson('/api/v1/laporan/keuangan');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'summary' => [
                    'total_balance',
                    'total_balance_formatted',
                    'income',
                    'income_formatted',
                    'expense',
                    'expense_formatted',
                    'net_profit',
                    'net_profit_formatted',
                    'growth_rate',
                ],
                'accounts',
                'weekly_chart',
                'data',
            ]);

        $this->assertNotEmpty($response->json('data'));
    }

    public function test_can_get_finance_categories(): void
    {
        $response = $this->getJson('/api/v1/laporan/keuangan/categories');

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonStructure([
                'success',
                'income',
                'expense',
                'accounts',
            ]);
    }

    public function test_can_filter_finance_entries(): void
    {
        FinancialEntry::create([
            'store_id' => $this->store->id,
            'entry_date' => now(),
            'direction' => 'expense',
            'entry_type' => 'operational_expense',
            'category' => 'Bahan Baku',
            'amount' => 50000.00,
            'description' => 'Beli Es Batu',
            'created_by' => $this->user->id,
        ]);

        $resIncome = $this->getJson('/api/v1/laporan/keuangan?direction=income');
        $resIncome->assertStatus(200);
        $this->assertCount(1, $resIncome->json('data'));

        $resExpense = $this->getJson('/api/v1/laporan/keuangan?direction=expense');
        $resExpense->assertStatus(200);
        $this->assertCount(1, $resExpense->json('data'));
    }

    public function test_can_create_income_and_expense_entries(): void
    {
        $incomePayload = [
            'direction' => 'income',
            'category' => 'Penjualan Pesanan Online',
            'amount' => 75000,
            'description' => 'Pesanan #ORD-0099',
        ];

        $resIncome = $this->postJson('/api/v1/laporan/keuangan', $incomePayload);
        $resIncome->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.category', 'Penjualan Pesanan Online');

        $this->assertDatabaseHas('financial_entries', [
            'store_id' => $this->store->id,
            'direction' => 'income',
            'amount' => 75000,
        ]);

        $expensePayload = [
            'direction' => 'expense',
            'category' => 'Listrik, Air & Internet',
            'amount' => 300000,
            'description' => 'Tagihan PLN Okt 2026',
        ];

        $resExpense = $this->postJson('/api/v1/laporan/keuangan', $expensePayload);
        $resExpense->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.category', 'Listrik, Air & Internet');
    }

    public function test_can_show_and_update_entry(): void
    {
        $resShow = $this->getJson('/api/v1/laporan/keuangan/' . $this->entry->id);
        $resShow->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.id', $this->entry->id);

        $updatePayload = [
            'amount' => 175000,
            'description' => 'Penjualan Shift Pagi Revisi',
        ];

        $resUpdate = $this->putJson('/api/v1/laporan/keuangan/' . $this->entry->id, $updatePayload);
        $resUpdate->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseHas('financial_entries', [
            'id' => $this->entry->id,
            'amount' => 175000,
            'description' => 'Penjualan Shift Pagi Revisi',
        ]);
    }

    public function test_can_delete_entry(): void
    {
        $resDelete = $this->deleteJson('/api/v1/laporan/keuangan/' . $this->entry->id);
        $resDelete->assertStatus(200)
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('financial_entries', [
            'id' => $this->entry->id,
        ]);
    }
}
