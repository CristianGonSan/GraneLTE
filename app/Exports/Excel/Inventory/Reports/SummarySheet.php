<?php

namespace App\Exports\Excel\Inventory\Reports;

use App\Models\Inventory\Category;
use App\Models\Inventory\RawMaterial;
use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialStock;
use App\Models\Inventory\Warehouse;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummarySheet implements WithTitle, WithEvents
{
    use RegistersEventListeners;

    public function title(): string
    {
        return 'Resumen';
    }

    public static function afterSheet(AfterSheet $event): void
    {
        $sheet = $event->sheet->getDelegate();
        $row   = 1;

        // Titulo principal
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("A{$row}", 'REPORTE GENERAL DE INVENTARIO');
        $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F3864']],
        ]);
        $row++;

        $sheet->setCellValue("A{$row}", 'Generado el: ' . now()->format('d/m/Y H:i'));
        $sheet->getStyle("A{$row}")->getFont()->setItalic(true);
        $row += 2;

        // Bloque: Metricas generales
        static::writeSectionHeader($sheet, $row, 'METRICAS GENERALES');
        $row++;

        $metrics = [
            ['Materiales activos',              RawMaterial::active()->count()],
            ['Lotes con existencia',            RawMaterialBatch::noEmpty()->count()],
            ['Lotes vencidos con existencia',   RawMaterialBatch::expired()->noEmpty()->count()],
            ['Costo total del inventario',    '$' . number_format((float) RawMaterial::totalCost(), 2)],
            ['Stocks negativos',                RawMaterialStock::where('current_quantity', '<', 0)->count()],
        ];

        foreach ($metrics as [$label, $value]) {
            $sheet->setCellValue("A{$row}", $label);
            $sheet->setCellValue("B{$row}", $value);
            $sheet->getStyle("A{$row}")->getFont()->setBold(true);
            $row++;
        }

        $row++;

        // Bloque: Por categoria
        static::writeSectionHeader($sheet, $row, 'POR CATEGORIA');
        $row++;
        static::writeTableHeadings($sheet, $row, ['Categoria', 'Materiales', 'Lotes con existencia', 'Costo Total']);
        $row++;

        /** @var Category $category */
        foreach (Category::active()->get() as $category) {
            $batchesWithStock = RawMaterialBatch::noEmpty()
                ->whereHas('material', fn(Builder $q): Builder => $q->where('category_id', $category->id))
                ->count();

            $sheet->setCellValue("A{$row}", $category->name);
            $sheet->setCellValue("B{$row}", $category->rawMaterials()->count());
            $sheet->setCellValue("C{$row}", $batchesWithStock);
            $sheet->setCellValue("D{$row}", $category->current_cost);
            $row++;
        }

        $row++;

        // Bloque: Por almacen
        static::writeSectionHeader($sheet, $row, 'POR ALMACEN');
        $row++;
        static::writeTableHeadings($sheet, $row, ['Almacen', 'Existencias', 'Materiales distintos', 'Costo Total']);
        $row++;

        foreach (Warehouse::active()->get() as $warehouse) {
            $stockQuery        = $warehouse->rawMaterialStocks()->available();
            $distinctMaterials = $warehouse->rawMaterialStocks()
                ->join('raw_material_batches as batches', 'batches.id', '=', 'raw_material_stocks.batch_id')
                ->where('raw_material_stocks.current_quantity', '>', 0)
                ->distinct()
                ->count('batches.material_id');

            $sheet->setCellValue("A{$row}", $warehouse->name);
            $sheet->setCellValue("B{$row}", (clone $stockQuery)->count());
            $sheet->setCellValue("C{$row}", $distinctMaterials);
            $sheet->setCellValue("D{$row}", $warehouse->current_cost);
            $row++;
        }

        $sheet->getColumnDimension('A')->setWidth(40);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(22);
        $sheet->getColumnDimension('D')->setWidth(20);
    }

    private static function writeSectionHeader(Worksheet $sheet, int $row, string $title): void
    {
        $sheet->mergeCells("A{$row}:D{$row}");
        $sheet->setCellValue("A{$row}", $title);
        $sheet->getStyle("A{$row}:D{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '2F75B6']],
        ]);
    }

    /**
     * @param array<int, string> $headings
     */
    private static function writeTableHeadings(Worksheet $sheet, int $row, array $headings): void
    {
        foreach ($headings as $i => $heading) {
            $col = chr(65 + $i);
            $sheet->setCellValue("{$col}{$row}", $heading);
        }

        $lastCol = chr(64 + count($headings));
        $sheet->getStyle("A{$row}:{$lastCol}{$row}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'BDD7EE']],
        ]);
    }
}
