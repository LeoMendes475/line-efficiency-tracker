<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ProductionController;
use App\Core\UseCases\GetProductionDashboard;
use Illuminate\Http\Request;
use Mockery;

class ProductionControllerTest extends TestCase
{
    protected $controller;
    protected $useCaseMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->useCaseMock = Mockery::mock(GetProductionDashboard::class);
        $this->controller = new ProductionController($this->useCaseMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIndexReturnsViewWithData()
    {
        $mockData = [
            'dados' => [
                ['linha' => 'Linha TV OLED', 'produzido' => 100, 'defeitos' => 5, 'eficiencia' => 95.0, 'data' => '2024-01-01'],
                ['linha' => 'Linha Mobile', 'produzido' => 80, 'defeitos' => 2, 'eficiencia' => 97.5, 'data' => '2024-01-01']
            ],
            'total_production' => 180,
            'total_defects' => 7,
            'average_efficiency' => 96.25,
            'product_lines' => ['Linha TV OLED', 'Linha Mobile'],
            'defect_rate' => 3.89
        ];

        $this->useCaseMock
            ->shouldReceive('execute')
            ->once()
            ->with('todas')
            ->andReturn($mockData);

        $request = new Request();
        $response = $this->controller->index($request, $this->useCaseMock);

        $this->assertEquals('dashboard', $response->getName());
        $this->assertArrayHasKey('dados', $response->getData());
        $this->assertArrayHasKey('total_production', $response->getData());
    }

    public function testIndexPassesRequestToUseCase()
    {
        $request = new Request(['linha' => 'Linha TV OLED', 'search' => 'TV']);

        $mockData = [
            'dados' => [
                ['linha' => 'Linha TV OLED', 'produzido' => 100, 'defeitos' => 5, 'eficiencia' => 95.0, 'data' => '2024-01-01']
            ],
            'total_production' => 100,
            'total_defects' => 5,
            'average_efficiency' => 95.0,
            'product_lines' => ['Linha TV OLED'],
            'defect_rate' => 5.0
        ];

        $this->useCaseMock
            ->shouldReceive('execute')
            ->once()
            ->with('Linha TV OLED')
            ->andReturn($mockData);

        $response = $this->controller->index($request, $this->useCaseMock);
        $this->assertEquals('dashboard', $response->getName());
    }

    public function testIndexHandlesEmptyData()
    {
        $emptyData = [
            'dados' => [],
            'total_production' => 0,
            'total_defects' => 0,
            'average_efficiency' => 0,
            'product_lines' => [],
            'defect_rate' => 0
        ];

        $this->useCaseMock
            ->shouldReceive('execute')
            ->once()
            ->andReturn($emptyData);

        $request = new Request();
        $response = $this->controller->index($request, $this->useCaseMock);

        $this->assertEquals('dashboard', $response->getName());
        $this->assertEmpty($response->getData()['dados']);
    }
}
