<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialDocument;
use App\Models\Inventory\RawMaterialMovement;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalCost       = RawMaterial::totalCost();
        $activeMaterials = RawMaterial::active()->count();

        $lowStockMaterials = RawMaterial::active()
            ->with('unit')
            ->get()
            ->filter(fn(RawMaterial $m): bool => $m->isLowStock())
            ->take(10); // 👈

        $expiringBatches = RawMaterialBatch::expiring(30)
            ->with(['material.unit'])
            ->limit(10)
            ->get();

        $recentDocuments = RawMaterialDocument::with(['creator'])
            ->latest()
            ->limit(10)
            ->get();

        $recentMovements = RawMaterialMovement::with(['batch.material.unit', 'warehouse'])
            ->latest()
            ->limit(10)
            ->get();

        return view('inventory.dashboard', compact(
            'totalCost',
            'activeMaterials',
            'lowStockMaterials',
            'expiringBatches',
            'recentDocuments',
            'recentMovements',
        ));
    }
}
