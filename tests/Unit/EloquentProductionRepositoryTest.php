<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Infrastructure\Persistence\Eloquent\EloquentProductionRepository;
use App\Production;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EloquentProductionRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = new EloquentProductionRepository();
    }

    public function testGetAllProductionReturnsArray()
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

        $result = $this->repository->getAllProduction();

        $this->assertIsArray($result);
        $this->assertCount(2, $result);

        $this->assertEquals('Linha TV OLED', $result[0]->product_line);
        $this->assertEquals(100, $result[0]->quantity_produced);
        $this->assertEquals(5, $result[0]->quantity_defects);
        $this->assertEquals(95.00, $result[0]->efficiency);
        $this->assertEquals('2024-01-01', $result[0]->production_date);
    }

    public function testGetAllProductionReturnsEmptyArrayWhenNoData()
    {
        $result = $this->repository->getAllProduction();

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function testDataMapping()
    {
        Production::create([
            'product_line' => 'Test Line',
            'quantity_produced' => 50,
            'quantity_defects' => 1,
            'efficiency' => 98.00,
            'production_date' => '2024-01-15'
        ]);

        $result = $this->repository->getAllProduction();

        $this->assertCount(1, $result);

        $item = $result[0];
        $this->assertObjectHasAttribute('product_line', $item);
        $this->assertObjectHasAttribute('quantity_produced', $item);
        $this->assertObjectHasAttribute('quantity_defects', $item);
        $this->assertObjectHasAttribute('efficiency', $item);
        $this->assertObjectHasAttribute('production_date', $item);

        $this->assertEquals('Test Line', $item->product_line);
        $this->assertEquals(50, $item->quantity_produced);
        $this->assertEquals(1, $item->quantity_defects);
        $this->assertEquals(98.00, $item->efficiency);
        $this->assertEquals('2024-01-15', $item->production_date);
    }
}
