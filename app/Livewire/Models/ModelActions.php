<?php

namespace App\Livewire\Models;

use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Alert;
use App\Traits\SweetAlert2\Livewire\Toast;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\View\View;

class ModelActions extends Component
{
    use FlashToast, Toast, Alert;

    public string $modelClass;
    public int $recordId;


    public function mount(string $modelClass, int $recordId): void
    {
        if (!class_exists($modelClass)) {
            throw new \Exception("El modelo {$modelClass} no existe.");
        }

        $this->modelClass   = $modelClass;
        $this->recordId      = $recordId;
    }

    public function render(): View
    {
        $record   = $this->getRecord();
        $isActive = $record->is_active;

        return view('inventory.models.model-actions', [
            'label'      => $isActive ? 'Activo' : 'Inactivo',
            'confirm'    => $isActive ? 'Desactivar registro' : 'Activar registro',
            'theme'      => $isActive ? 'outline-success' : 'outline-secondary',
            'created_at' => $record->created_at->diffForHumans(),
            'updated_at' => $record->updated_at->diffForHumans(),
        ]);
    }

    public function toggleActive(): void
    {
        $this->toastSuccess(
            $this->getRecord()->toggleActive()
                ? 'Registro activado'
                : 'Registro desactivado'
        );
    }

    public function delete(): void
    {
        $record = $this->getRecord();

        if ($record->isInUse()) {
            $this->alertError(
                'El Registro está en uso, se recomienda desactivarlo.',
                'Registro en uso'
            );
        } else {
            $record->delete();
            $this->flashToastSuccess('Registro eliminado.');
            redirect()->route('raw-materials.index');
        }
    }

    private ?Model $model = null;

    protected function getRecord(): Model
    {
        return $this->model ??= $this->modelClass::findOrFail($this->recordId, [
            'id',
            'is_active',
            'created_at',
            'updated_at'
        ]);
    }
}
