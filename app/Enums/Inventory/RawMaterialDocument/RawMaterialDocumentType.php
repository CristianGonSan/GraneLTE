<?php

namespace App\Enums\Inventory\RawMaterialDocument;

enum RawMaterialDocumentType: string
{
    case RECEIPT    = 'receipt';
    case ISSUE      = 'issue';
    case TRANSFER   = 'transfer';
    case ADJUSTMENT     = 'adjustment';

    public function label(): string
    {
        return match ($this) {
            self::RECEIPT    => 'Entrada',
            self::ISSUE      => 'Salida',
            self::TRANSFER   => 'Transferencia',
            self::ADJUSTMENT => 'Ajuste'
        };
    }

    public static function options(): array
    {
        $options = [];

        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }

        return $options;
    }
}
