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
        $query = Unit::active();

        $term = $request->input('term');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('symbol', 'like', "$term%")
                    ->orWhere('name', 'like', "%$term%");
            });
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name', 'symbol']);

        $map = $results->map(
            fn($item) => [
                'id'            => $item->id,
                'text'          => $item->name,
                'description'   => $item->symbol
            ]
        );

        $json = [
            'results' => $map,
            'pagination' => [
                'more' => $results->hasMorePages(),
            ],
        ];

        return response()->json($json);
    }
}
