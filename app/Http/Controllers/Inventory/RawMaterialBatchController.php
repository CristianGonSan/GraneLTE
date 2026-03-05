<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Category;
use App\Models\Inventory\RawMaterialBatch;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RawMaterialBatchController extends Controller
{
    public function index(): View
    {
        return view('inventory.raw-material-batches.index');
    }

    public function show(int $id): View
    {
        abort_if(!RawMaterialBatch::where('id', $id)->exists(), 404);

        return view('inventory.raw-material-batches.show', [
            'batchId' => $id
        ]);
    }
}
