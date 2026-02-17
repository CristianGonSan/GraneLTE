<?php

namespace App\Http\Middleware;

use App\Models\Inventory\RawMaterialDocument;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckDocumentType
{
    public function handle(Request $request, Closure $next, string $type): Response
    {
        $id         = $request->route('document');
        $document   = RawMaterialDocument::find($id, ['id', 'type']);

        if (!$document || $document->type->value != $type) {
            abort(404);
        }

        return $next($request);
    }
}
