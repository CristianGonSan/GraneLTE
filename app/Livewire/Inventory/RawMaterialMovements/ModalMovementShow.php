<?php

namespace App\Livewire\Inventory\RawMaterialMovements;

use App\Models\Inventory\RawMaterialMovement;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use Livewire\Component;

class ModalMovementShow extends Component
{
    public bool $showModal = false;
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

    #[On('showMovement')]
    public function openModal(?int $id): void
    {
        $this->movementId  = $id;
        $this->showModal   = true;
    }

    public function closeModal(): void
    {
        $this->movementId  = null;
        $this->showModal   = false;
    }

    private ?RawMaterialMovement $movement = null;

    private function movement(): RawMaterialMovement|null
    {
        if ($this->movementId === null) {
            return null;
        }
        return $this->movement ??= RawMaterialMovement::with([
            'batch.material.unit',
            'warehouse',
            'document'
        ])->findOrFail($this->movementId);
    }
}
