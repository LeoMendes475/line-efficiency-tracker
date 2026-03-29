<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Production;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function testDashboardLoadsWithData()
    {
        Production::create([
            'product_line' => 'Linha TV OLED',
            'quantity_produced' => 100,
            'quantity_defects' => 5,
            'efficiency' => 95.00,
            'production_date' => '2024-01-01'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200)
                ->assertViewHas('dados')
                ->assertViewHas('totais')
                ->assertViewHas('linhaSelecionada')
                ->assertViewHas('linhas');
    }

    public function testFilterByProductLine()
    {
        Production::create([
            'product_line' => 'Linha TV OLED',
            'quantity_produced' => 100,
            'quantity_defects' => 5,
            'efficiency' => 95.00,
            'production_date' => '2024-01-01'
        ]);

        Production::create([
            'product_line' => 'Linha Mobile',
            'quantity_produced' => 80,
            'quantity_defects' => 2,
            'efficiency' => 97.50,
            'production_date' => '2024-01-01'
        ]);

        $response = $this->get('/?linha=Linha+TV+OLED');

        $response->assertStatus(200)
                ->assertViewHas('linhaSelecionada', 'Linha TV OLED');

        $data = $response->viewData('dados');
        $this->assertCount(1, $data);
        $this->assertEquals('Linha TV OLED', $data[0]['linha']);
    }

    public function testSearchFunctionality()
    {
        Production::create([
            'product_line' => 'Linha TV OLED',
            'quantity_produced' => 100,
            'quantity_defects' => 5,
            'efficiency' => 95.00,
            'production_date' => '2024-01-01'
        ]);

        Production::create([
            'product_line' => 'Linha Mobile',
            'quantity_produced' => 80,
            'quantity_defects' => 2,
            'efficiency' => 97.50,
            'production_date' => '2024-01-01'
        ]);

        $response = $this->get('/?search=TV');

        $response->assertStatus(200);

        $data = $response->viewData('dados');
        $this->assertCount(1, $data);
        $this->assertEquals('Linha TV OLED', $data[0]['linha']);
    }

    public function testPagination()
    {
        for ($i = 0; $i < 15; $i++) {
            Production::create([
                'product_line' => 'Linha ' . $i,
                'quantity_produced' => 100 + $i,
                'quantity_defects' => 5,
                'efficiency' => 95.00,
                'production_date' => '2024-01-01'
            ]);
        }

        $response = $this->get('/');

        $response->assertStatus(200);

        $data = $response->viewData('dados');
        $this->assertCount(10, $data);

        $response = $this->get('/?page=2');

        $response->assertStatus(200);

        $data = $response->viewData('dados');
        $this->assertCount(5, $data);
    }

    public function testTotalsCalculation()
    {
        Production::create([
            'product_line' => 'Linha TV OLED',
            'quantity_produced' => 100,
            'quantity_defects' => 5,
            'efficiency' => 95.00,
            'production_date' => '2024-01-01'
        ]);

        Production::create([
            'product_line' => 'Linha Mobile',
            'quantity_produced' => 80,
            'quantity_defects' => 2,
            'efficiency' => 97.50,
            'production_date' => '2024-01-01'
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);

        $totais = $response->viewData('totais');

        $this->assertEquals(180, $totais['total_produzido']);
        $this->assertEquals(7, $totais['total_defeitos']);
        $this->assertEquals(2, $totais['total_linhas']);
    }

    public function testClearFilters()
    {
        Production::create([
            'product_line' => 'Linha TV OLED',
            'quantity_produced' => 100,
            'quantity_defects' => 5,
            'efficiency' => 95.00,
            'production_date' => '2024-01-01'
        ]);

        $response = $this->get('/?linha=Linha+TV+OLED&search=TV');

        $response->assertStatus(200)
                ->assertViewHas('linhaSelecionada', 'Linha TV OLED');

        $response = $this->get('/');

        $response->assertStatus(200)
                ->assertViewHas('linhaSelecionada', 'todas');
    }
}
