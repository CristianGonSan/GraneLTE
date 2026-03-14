<?php

namespace App\Exports\Excel\Inventory\Reports;

use App\Models\Inventory\RawMaterialBatch;
use App\Models\Inventory\RawMaterialStock;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnomaliesSheet implements WithTitle, WithEvents
{
    use RegistersEventListeners;

    public function title(): string
    {
        return 'Anomalias';
    }

    public static function afterSheet(AfterSheet $event): void
    {
        $sheet = $event->sheet->getDelegate();
        $row   = 1;

        // Titulo principal
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("A{$row}", 'ANOMALIAS DE INVENTARIO');
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F3864']],
        ]);
        $row++;

        $sheet->setCellValue("A{$row}", 'Generado el: ' . now()->format('d/m/Y H:i'));
        $sheet->getStyle("A{$row}")->getFont()->setItalic(true);
        $row += 2;

        // Bloque: Stocks negativos
        static::writeSectionHeader($sheet, $row, 'STOCKS NEGATIVOS', 'C0392B');
        $row++;
        static::writeTableHeadings($sheet, $row, ['Almacen', 'Material', 'Lote', 'Unidad', 'Cantidad Actual']);
        $row++;

        $negativeStocks = RawMaterialStock::query()
            ->where('raw_material_stocks.current_quantity', '<', 0)
            ->with(['batch.material.unit', 'warehouse'])
            ->get();

        if ($negativeStocks->isEmpty()) {
            $sheet->mergeCells("A{$row}:E{$row}");
            $sheet->setCellValue("A{$row}", 'Sin anomalias.');
            $sheet->getStyle("A{$row}")->getFont()->setItalic(true);
            $row++;
        } else {
            /** @var RawMaterialStock $stock */
            foreach ($negativeStocks as $stock) {
                $sheet->setCellValue("A{$row}", $stock->warehouse->name);
                $sheet->setCellValue("B{$row}", $stock->batch->material->name);
                $sheet->setCellValue("C{$row}", $stock->batch->code);
                $sheet->setCellValue("D{$row}", $stock->batch->material->unit->symbol);
                $sheet->setCellValue("E{$row}", $stock->current_quantity);
                $sheet->getStyle("E{$row}")->getFont()->setBold(true)->getColor()->setRGB('C0392B');
                $row++;
            }
        }

        $row++;

        // Bloque: Lotes vencidos con existencia
        static::writeSectionHeader($sheet, $row, 'LOTES VENCIDOS CON EXISTENCIA', 'C0392B');
        $row++;
        static::writeTableHeadings($sheet, $row, ['Codigo Lote', 'Material', 'Proveedor', 'Unidad', 'Cant. Actual', 'Fec. Vencimiento']);
        $row++;

        $expiredBatches = RawMaterialBatch::expired()->noEmpty()
            ->with(['material.unit', 'supplier'])
            ->orderBy('expiration_date', 'asc')
            ->get();

        if ($expiredBatches->isEmpty()) {
            $sheet->mergeCells("A{$row}:F{$row}");
            $sheet->setCellValue("A{$row}", 'Sin anomalias.');
            $sheet->getStyle("A{$row}")->getFont()->setItalic(true);
            $row++;
        } else {
            /** @var RawMaterialBatch $batch */
            foreach ($expiredBatches as $batch) {
                $sheet->setCellValue("A{$row}", $batch->code);
                $sheet->setCellValue("B{$row}", $batch->material->name);
                $sheet->setCellValue("C{$row}", $batch->supplier->name);
                $sheet->setCellValue("D{$row}", $batch->material->unit->symbol);
                $sheet->setCellValue("E{$row}", $batch->current_quantity);
                $sheet->setCellValue("F{$row}", $batch->expiration_date->format('d/m/Y'));
                $sheet->getStyle("F{$row}")->getFont()->setBold(true)->getColor()->setRGB('C0392B');
                $row++;
            }
        }

        $sheet->getColumnDimension('A')->setWidth(28);
        $sheet->getColumnDimension('B')->setWidth(35);
        $sheet->getColumnDimension('C')->setWidth(25);
        $sheet->getColumnDimension('D')->setWidth(10);
        $sheet->getColumnDimension('E')->setWidth(16);
        $sheet->getColumnDimension('F')->setWidth(18);
    }

    private static function writeSectionHeader(Worksheet $sheet, int $row, string $title, string $colorRgb = '2F75B6'): void
    {
        $sheet->mergeCells("A{$row}:F{$row}");
        $sheet->setCellValue("A{$row}", $title);
        $sheet->getStyle("A{$row}:F{$row}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $colorRgb]],
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
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FADBD8']],
        ]);
    }
}
