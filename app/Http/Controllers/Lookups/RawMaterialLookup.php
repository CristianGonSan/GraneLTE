<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Inventory\RawMaterial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RawMaterialLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = RawMaterial::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->whereAny(['name', 'description'], 'like', "%$term%");
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name', 'description']);

        $map = $results->map(fn(RawMaterial $item) => [
            'id'          => $item->id,
            'text'        => $item->name,
            'description' => $item->description,
        ]);

        return response()->json([
            'results'    => $map,
            'pagination' => ['more' => $results->hasMorePages()],
        ]);
    }
}
