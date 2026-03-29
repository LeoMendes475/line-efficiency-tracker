<?php

namespace App\Http\Controllers;

use App\Core\UseCases\GetProductionDashboard;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductionController extends Controller
{
    public function index(Request $request, GetProductionDashboard $useCase)
    {
        $selectedLines = $request->get('linha');

        // Ensure $selectedLines is always an array
        if (is_null($selectedLines)) {
            $selectedLines = ['todas'];
        } elseif (!is_array($selectedLines)) {
            $selectedLines = [$selectedLines];
        }

        $search = $request->get('search');

        if (in_array('todas', $selectedLines) || empty(array_filter($selectedLines))) {
            $selectedLine = 'todas';
        } else {
            $selectedLine = $selectedLines;
        }

        $data = $useCase->execute($selectedLine);

        if ($search) {
            $data['dados'] = array_filter($data['dados'], function($item) use ($search) {
                return stripos($item['linha'], $search) !== false;
            });
        }

        $collection = collect($data['dados']);
        $perPage = 10;
        $currentPage = $request->get('page', 1);
        $items = $collection->forPage($currentPage, $perPage);
        $data['dados'] = new LengthAwarePaginator($items, $collection->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'pageName' => 'page'
        ]);

        $data['dados']->appends($request->query());

        return view('dashboard', $data);
    }
}
