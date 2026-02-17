<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class InfoController
{
    public function index(): View
    {
        return view('info');
    }
}
