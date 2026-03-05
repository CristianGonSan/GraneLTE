<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Unit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UnitLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = Unit::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn($q) => $q
                    ->where('symbol', 'like', "$term%")
                    ->orWhere('name', 'like', "%$term%")
            );
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name', 'symbol']);

        $map = $results->map(fn(Unit $item) => [
            'id'          => $item->id,
            'text'        => $item->name,
            'description' => $item->symbol,
        ]);

        return response()->json([
            'results'    => $map,
            'pagination' => ['more' => $results->hasMorePages()],
        ]);
    }
}
