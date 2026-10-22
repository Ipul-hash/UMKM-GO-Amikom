<?php

namespace Tests\Feature;

use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    public function test_admin_dashboard_renders_successfully(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('UMKMGo');
        $response->assertSee('Dashboard');
        $response->assertSee('Omzet Hari Ini');
        $response->assertSee('Rp 1.250.000');
    }

    public function test_all_admin_screens_render_successfully(): void
    {
        $screens = [
            '/admin/pos' => 'POS / Kasir',
            '/admin/products' => 'Produk',
            '/admin/inventory' => 'Inventory',
            '/admin/orders' => 'Pesanan Online',
            '/admin/finance' => 'Keuangan',
            '/admin/customers' => 'Pelanggan',
        ];

        foreach ($screens as $uri => $expectedText) {
            $response = $this->get($uri);
            $response->assertStatus(200);
            $response->assertSee('UMKMGo');
            $response->assertSee($expectedText);
        }
    }
}
