<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Core\UseCases\GetProductionDashboard;
use App\Core\Repositories\ProductionRepositoryInterface;
use App\Production;
use Mockery;

class GetProductionDashboardTest extends TestCase
{
    protected $repository;
    protected $useCase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(ProductionRepositoryInterface::class);
        $this->useCase = new GetProductionDashboard($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testExecuteReturnsAllData()
    {
        $mockData = [
            (object)[
                'product_line' => 'Linha TV OLED',
                'quantity_produced' => 100,
                'quantity_defects' => 5,
                'efficiency' => 95.00,
                'production_date' => '2024-01-01'
            ],
            (object)[
                'product_line' => 'Linha Mobile',
                'quantity_produced' => 80,
                'quantity_defects' => 2,
                'efficiency' => 97.50,
                'production_date' => '2024-01-01'
            ]
        ];

        $this->repository->shouldReceive('getAllProduction')
                        ->once()
                        ->andReturn($mockData);

        $result = $this->useCase->execute('todas');

        $this->assertArrayHasKey('dados', $result);
        $this->assertArrayHasKey('totais', $result);
        $this->assertArrayHasKey('linhaSelecionada', $result);
        $this->assertArrayHasKey('linhas', $result);

        $this->assertEquals('todas', $result['linhaSelecionada']);
        $this->assertCount(2, $result['linhas']);
        $this->assertEquals(180, $result['totais']['total_produzido']);
        $this->assertEquals(7, $result['totais']['total_defeitos']);
    }

    public function testExecuteFiltersByLine()
    {
        $mockData = [
            (object)[
                'product_line' => 'Linha TV OLED',
                'quantity_produced' => 100,
                'quantity_defects' => 5,
                'efficiency' => 95.00,
                'production_date' => '2024-01-01'
            ],
            (object)[
                'product_line' => 'Linha Mobile',
                'quantity_produced' => 80,
                'quantity_defects' => 2,
                'efficiency' => 97.50,
                'production_date' => '2024-01-01'
            ]
        ];

        $this->repository->shouldReceive('getAllProduction')
                        ->once()
                        ->andReturn($mockData);

        $result = $this->useCase->execute('Linha TV OLED');

        $this->assertEquals('Linha TV OLED', $result['linhaSelecionada']);
        $this->assertCount(1, $result['dados']);
        $this->assertEquals('Linha TV OLED', $result['dados'][0]['linha']);
        $this->assertEquals(100, $result['totais']['total_produzido']);
    }

    public function testEfficiencyCalculation()
    {
        $mockData = [
            (object)[
                'product_line' => 'Linha TV OLED',
                'quantity_produced' => 100,
                'quantity_defects' => 10,
                'efficiency' => 90.00,
                'production_date' => '2024-01-01'
            ]
        ];

        $this->repository->shouldReceive('getAllProduction')
                        ->once()
                        ->andReturn($mockData);

        $result = $this->useCase->execute('todas');

        $this->assertEquals(90.00, $result['totais']['eficiencia_geral']);
    }

    public function testExecuteHandlesEmptyData()
    {
        $this->repository->shouldReceive('getAllProduction')
                        ->once()
                        ->andReturn([]);

        $result = $this->useCase->execute('todas');

        $this->assertEmpty($result['dados']);
        $this->assertEquals(0, $result['totais']['total_produzido']);
        $this->assertEquals(0, $result['totais']['total_defeitos']);
        $this->assertEquals(0, $result['totais']['eficiencia_geral']);
    }
}
