<?php

namespace App\Http\Controllers\Lookups;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Responsible;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ResponsibleLookup extends Controller
{
    public function select2(Request $request): JsonResponse
    {
        $query = Responsible::active();

        $term = $request->input('term');

        if ($term) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%$term%")
                    ->orWhere('identifier', 'like', "%$term%");
            });
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name', 'identifier']);

        $map = $results->map(
            fn($item) => [
                'id'            => $item->id,
                'text'          => $item->name,
                'description'   => $item->identifier
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
