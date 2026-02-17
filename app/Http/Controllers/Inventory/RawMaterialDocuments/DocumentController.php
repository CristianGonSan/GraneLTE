<?php

namespace App\Http\Controllers\Inventory\RawMaterialDocuments;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(): View
    {
        return view('inventory.raw-material-documents.index');
    }
}
