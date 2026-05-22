<?php

namespace App\Livewire\Inventory\RawMaterialMovements;

use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalMovementShow extends Component
{
    public ?int $movementId = null;

    public function render(): View
    {
        return view(
            'livewire.inventory.raw-material-movements.modal-movement-show',
            [
                'movement' => $this->movement(),
            ]
        );
    }

    #[On('openComponentRawMaterialMovementShow')]
    public function openModal(int $movementId): void
    {
        abort_if(cannot('raw-material-movements.view'), 403);

        $this->movementId = $movementId;
        $this->dispatch('showModalRawMaterialMovementShow');
    }

    #[On('closeComponentRawMaterialMovementShow')]
    public function closeModal(): void
    {
        $this->movementId = null;
        $this->dispatch('hideModalRawMaterialMovementShow');
    }

    private ?RawMaterialMovement $movement = null;

    private function movement(): ?RawMaterialMovement
    {
        if ($this->movementId === null) {
            return null;
        }

        return $this->movement ??= RawMaterialMovement::with([
            'batch.material.unit',
            'warehouse',
            'document',
        ])->findOrFail($this->movementId);
    }
}
