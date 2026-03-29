<?php

namespace App\Core\Entities;

class ProductionReport
{
    public $id;
    public $product_line;
    public $quantity_produced;
    public $quantity_defects;
    public $efficiency;
    public $production_date;

    public function __construct(string $line, int $produced, int $defects, float $efficiency, string $date)
    {
        $this->product_line = $line;
        $this->quantity_produced = $produced;
        $this->quantity_defects = $defects;
        $this->efficiency = $efficiency;
        $this->production_date = $date;
    }

    public function calculateEfficiency()
    {
        return (float) $this->efficiency;
    }
}
