<?php

namespace App\Http\Controllers\Exports;

use Illuminate\View\View;

class ExportController
{
    public function index(): View
    {
        return view('exports.index');
    }
}
