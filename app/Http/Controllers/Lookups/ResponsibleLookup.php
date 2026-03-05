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
        $query = Responsible::query();

        if ($request->has('active')) {
            $request->boolean('active') ? $query->active() : $query->inactive();
        }

        if ($request->has('term')) {
            $term = $request->string('term');
            $query->where(
                fn($q) => $q
                    ->where('name', 'like', "%$term%")
                    ->orWhere('identifier', 'like', "$term%")
            );
        }

        $query->orderBy('name');

        $results = $query->paginate(16, ['id', 'name', 'identifier']);

        $map = $results->map(fn(Responsible $item) => [
            'id'          => $item->id,
            'text'        => $item->name,
            'description' => $item->identifier,
        ]);

        return response()->json([
            'results'    => $map,
            'pagination' => ['more' => $results->hasMorePages()],
        ]);
    }
}
