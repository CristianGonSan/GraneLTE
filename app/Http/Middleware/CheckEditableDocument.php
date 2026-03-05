<?php

namespace App\Http\Middleware;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus as DocumentStatus;
use App\Models\Inventory\RawMaterialDocument;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckEditableDocument
{
    public function handle(Request $request, Closure $next): Response
    {
        $id         = $request->route('document');
        $document   = RawMaterialDocument::findOrFail($id, ['id', 'status', 'created_by']);

        if ($document->status != DocumentStatus::DRAFT || $document->created_by != auth()->id()) {
            abort(403);
        }

        return $next($request);
    }
}
