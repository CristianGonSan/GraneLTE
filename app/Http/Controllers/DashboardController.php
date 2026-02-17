<?php

namespace App\Http\Controllers;

use App\Models\CattleRaising\Cattle;
use App\Models\CattleRaising\Milking;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    /**
     * Muestra el panel de control de la aplicación.
     *
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('dashboard');
    }
}
