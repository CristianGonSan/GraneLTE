<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Responsible;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ResponsibleController extends Controller
{
    public function index(): View
    {
        return view('inventory.responsibles.index');
    }

    public function create(): View
    {
        return view('inventory.responsibles.create');
    }

    public function edit($id): View
    {
        abort_if(!Responsible::where('id', $id)->exists(), 404);

        return view('inventory.responsibles.edit', [
            'responsibleId' => $id
        ]);
    }
}
