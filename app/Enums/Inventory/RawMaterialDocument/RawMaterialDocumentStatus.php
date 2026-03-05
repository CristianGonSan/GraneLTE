<?php

namespace App\Enums\Inventory\RawMaterialDocument;

use App\Models\Inventory\RawMaterialDocument;
use App\Models\User;

enum RawMaterialDocumentStatus: string
{
    case DRAFT     = 'draft';
    case PENDING   = 'pending';
    case ACCEPTED  = 'accepted';
    case REJECTED  = 'rejected';
    case CANCELED  = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT     => 'Borrador',
            self::PENDING   => 'Pendiente',
            self::ACCEPTED  => 'Aceptado',
            self::REJECTED  => 'Rechazado',
            self::CANCELED  => 'Cancelado'
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::DRAFT     => 'El documento ha sido creado pero aún puede editarse y no ha sido enviado para revisión.',
            self::PENDING   => 'El documento fue enviado y está pendiente de revisión o aprobación.',
            self::ACCEPTED  => 'El documento ha sido revisado y aprobado oficialmente.',
            self::REJECTED  => 'El documento fue revisado pero no aprobado. Puede requerir correcciones.',
            self::CANCELED  => 'El documento fue cancelado después de haber sido aprobado y ya no tiene validez.'
        };
    }

    public function canChangeTo(self $next): bool
    {
        $allowedTransitions = match ($this) {
            self::DRAFT     => [self::PENDING],
            self::PENDING   => [self::ACCEPTED, self::REJECTED],
            self::ACCEPTED  => [self::CANCELED],
            self::REJECTED, self::CANCELED => [],
        };

        return \in_array($next, $allowedTransitions, true);
    }

    public static function canChangeBy(self $status, User $user): bool
    {
        $permission = match ($status) {
            self::DRAFT     => null,
            self::PENDING   => null,
            self::ACCEPTED  => 'raw-material-documents.accept',
            self::REJECTED  => 'raw-material-documents.reject',
            self::CANCELED  => 'raw-material-documents.cancel',
        };

        if ($permission && $user->cannot($permission)) {
            return false;
        }

        return true;
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
