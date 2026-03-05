<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\RawMaterial;
use Illuminate\View\View;

class RawMaterialController extends Controller
{
    public function index(): View
    {
        return view('inventory.raw-materials.index');
    }

    public function show(int $id): View
    {
        abort_if(!RawMaterial::where('id', $id)->exists(), 404);

        return view('inventory.raw-materials.show', [
            'rawMaterialId' => $id
        ]);
    }

    public function create(): View
    {
        return view('inventory.raw-materials.create');
    }

    public function edit(int $id): View
    {
        abort_if(!RawMaterial::where('id', $id)->exists(), 404);

        return view('inventory.raw-materials.edit', [
            'rawMaterialId' => $id
        ]);
    }
}
