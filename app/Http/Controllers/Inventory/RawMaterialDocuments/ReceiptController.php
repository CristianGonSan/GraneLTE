<?php

namespace App\Http\Controllers\Inventory\RawMaterialDocuments;

use App\Enums\Inventory\RawMaterialTransaction\DocumentType;
use App\Http\Controllers\Controller;
use App\Models\Inventory\RawMaterialDocument;
use Illuminate\View\View;

class ReceiptController extends Controller
{
    public function index(): View
    {
        return view('inventory.raw-material-documents.index');
    }

    public function create(): View
    {
        return view('inventory.raw-material-documents.receipts.create');
    }

    public function show(int $id): View
    {
        $document = RawMaterialDocument::findOrFail($id);

        return view('inventory.raw-material-documents.receipts.show', [
            'document' => $document
        ]);
    }

    public function edit(int $id): View
    {
        return view('inventory.raw-material-documents.receipts.edit', [
            'documentId' => $id
        ]);
    }
}
