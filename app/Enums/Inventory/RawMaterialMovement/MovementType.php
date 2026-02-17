<?php

namespace App\Enums\Inventory\RawMaterialMovement;

enum MovementType: string
{
    case RECEIPT        = 'receipt';        // Ingreso desde proveedor
    case ISSUE          = 'issue';          // Salida a consumo/cliente
    case TRANSFER_IN    = 'transfer_in';    // Entrada por traslado interno
    case TRANSFER_OUT   = 'transfer_out';   // Salida por traslado interno
    case ADJUSTMENT_IN  = 'adjustment_in';  // Ajuste positivo
    case ADJUSTMENT_OUT = 'adjustment_out'; // Ajuste negativo

    public function label(): string
    {
        return match ($this) {
            self::RECEIPT         => 'Entrada',
            self::ISSUE           => 'Salida',
            self::TRANSFER_IN     => 'Entrada por Transferencia',
            self::TRANSFER_OUT    => 'Salida por Transferencia',
            self::ADJUSTMENT_IN   => 'Ajuste positivo',
            self::ADJUSTMENT_OUT  => 'Ajuste negativo',
        };
    }

    public function isIncrement(): bool
    {
        return $this->sign() > 0;
    }

    public function isDecrement(): bool
    {
        return $this->sign() < 0;
    }

    /**
     * Determina el efecto sobre el stock (+ o -)
     */
    public function sign(): int
    {
        return match ($this) {
            self::RECEIPT, self::TRANSFER_IN, self::ADJUSTMENT_IN => 1,
            self::ISSUE, self::TRANSFER_OUT, self::ADJUSTMENT_OUT => -1,
        };
    }
}
