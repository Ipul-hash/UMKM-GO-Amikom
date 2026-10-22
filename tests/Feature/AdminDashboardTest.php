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
        $response->assertSee('Toko Kopi &amp; Fashion Andi', false);
        $response->assertSee('Omzet Hari Ini');
        $response->assertSee('Rp 1.250.000');
        $response->assertSee('Coffe Americano');
        $response->assertSee('Matcha Latte');
        $response->assertSee('POS / Kasir Quick Access');
        $response->assertSee('Buka Kasir');
        $response->assertSee('Kelola Produk');
        $response->assertSee('Laporan Penjualan');
    }
}
