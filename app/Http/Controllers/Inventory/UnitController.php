<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function index(): View
    {
        return view('inventory.units.index');
    }

    public function show(int $id): View
    {
        abort_if(!Unit::where('id', $id)->exists(), 404);

        return view('inventory.units.show', [
            'unitId' => $id
        ]);
    }

    public function create(): View
    {
        return view('inventory.units.create');
    }

    public function edit(int $id): View
    {
        abort_if(!Unit::where('id', $id)->exists(), 404);

        return view('inventory.units.edit', [
            'unitId' => $id
        ]);
    }
}
