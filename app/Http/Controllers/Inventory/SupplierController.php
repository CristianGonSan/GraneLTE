<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Supplier;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public function index(): View
    {
        return view('inventory.suppliers.index');
    }

    public function create(): View
    {
        return view('inventory.suppliers.create');
    }

    public function edit($id): View
    {
        abort_if(!Supplier::where('id', $id)->exists(), 404);

        return view('inventory.suppliers.edit', [
            'supplierId' => $id
        ]);
    }
}
