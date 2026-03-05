<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('inventory.categories.index');
    }

    public function show(int $id): View
    {
        abort_if(!Category::where('id', $id)->exists(), 404);

        return view('inventory.categories.show', [
            'categoryId' => $id
        ]);
    }

    public function create(): View
    {
        return view('inventory.categories.create');
    }

    public function edit(int $id): View
    {
        abort_if(!Category::where('id', $id)->exists(), 404);

        return view('inventory.categories.edit', [
            'categoryId' => $id
        ]);
    }
}
