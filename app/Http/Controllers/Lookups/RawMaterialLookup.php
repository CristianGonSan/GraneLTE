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
        $query = RawMaterial::active();

        $term = $request->input('term');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%$term%")
                    ->orWhere('description', 'like', "%$term%");
            });
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name', 'description']);

        $map = $results->map(
            fn($item) => [
                'id'            => $item->id,
                'text'          => $item->name,
                'description'   => $item->description
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
