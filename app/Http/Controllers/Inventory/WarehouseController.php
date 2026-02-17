<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Warehouse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WarehouseController extends Controller
{
    public function index(): View
    {
        return view('inventory.warehouses.index');
    }

    public function create(): View
    {
        return view('inventory.warehouses.create');
    }

    public function edit($id): View
    {
        abort_if(!Warehouse::where('id', $id)->exists(), 404);

        return view('inventory.warehouses.edit', [
            'warehouseId' => $id
        ]);
    }
}
