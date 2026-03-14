<?php

namespace App\Exports\Pdf\Inventory;

use App\Models\Inventory\RawMaterial;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RawMaterialPdfExport
{
    /**
     * Genera y retorna el reporte PDF de un material como respuesta en streaming.
     *
     * Carga el material con sus relaciones necesarias para el reporte:
     * - unit: unidad de medida del material.
     * - category: categoria a la que pertenece.
     * - batches.supplier: proveedor asociado a cada lote.
     * - batches.stocks.warehouse: almacenes donde cada lote tiene existencias.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return StreamedResponse
     */
    public static function generate(int $id): StreamedResponse
    {
        $material = RawMaterial::with([
            'unit',
            'category',
            'batches.supplier',
            'batches.stocks.warehouse',
        ])->findOrFail($id);

        $material->load(['unit', 'category', 'batches.supplier']);

        $output = Pdf::loadView('pdf.inventory.raw-material', [
            'material' => $material,
        ])->setPaper('a4', 'portrait')->output();

        $filename = sprintf(
            'material-%s-%s.pdf',
            $material->abbreviation,
            now()->format('Ymd')
        );

        return new StreamedResponse(function () use ($output): void {
            echo $output;
        }, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "inline; filename=\"{$filename}\"",
            'Content-Length'      => \strlen($output),
        ]);
    }
}
