<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Core\Entities\ProductionReport;
use App\Core\Repositories\ProductionRepositoryInterface;
use App\Production as EloquentModel;

class EloquentProductionRepository implements ProductionRepositoryInterface
{
    public function getAllProduction(): array
    {
        $items = EloquentModel::all();

        return $items->map(function($item) {
            return new ProductionReport(
                $item->product_line,
                $item->quantity_produced,
                $item->quantity_defects,
                $item->efficiency,
                $item->production_date
            );
        })->toArray();
    }
}
