<?php

namespace App\Livewire\Inventory\RawMaterialDocuments;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Models\Inventory\RawMaterialDocument;
use App\Traits\SweetAlert2\Livewire\WithSweetAlert;
use App\Traits\SweetAlert2\WithSweetAlertFlash;
use DomainException;
use Illuminate\View\View;
use Livewire\Component;
use Throwable;

class ChangeDocumentStatus extends Component
{
    use WithSweetAlert, WithSweetAlertFlash;

    public int $documentId;

    public function mount(int $documentId): void
    {
        $this->documentId = $documentId;
    }

    public function render(): View
    {
        return view('livewire.inventory.raw-material-documents.status-manage', [
            'document' => $this->document()
        ]);
    }

    public function changeStatus(RawMaterialDocumentStatus $newStatus): void
    {
        try {
            $document = $this->document();
            $user     = auth()->user();

            if (!$document->validateStatusChange($newStatus, $user)) {
                $this->dispatchToast('error', "Acción invalida");
                return;
            }

            if ($newStatus !== RawMaterialDocumentStatus::PENDING) {
                $document->validated_by = $user->id;
                $document->validated_at = now();
            }

            if ($newStatus === RawMaterialDocumentStatus::ACCEPTED) {
                $document->execute();
            }

            $document->status = $newStatus;
            $document->save();

            $this->dispatchToast('success', "El documento cambió a {$newStatus->label()}");
        } catch (DomainException $e) {
            $this->dispatchToast('error', $e->getMessage());
        } catch (Throwable $e) {
            report($e); // log técnico
            $this->dispatchToast('error', 'Ocurrió un error inesperado al procesar el documento.');
        }
    }

    public function delete(): void
    {
        $document = $this->document();

        if (!$document->validateDelete(auth()->user())) {
            $this->dispatchToast('error', 'Solo se puede eliminar si es un borrador y por el propietario');
            return;
        }

        $document->hardDelete();
        $this->flashToast('success', 'Documento eliminado.');
        redirect()->route('raw-material-documents.index');
    }

    private ?RawMaterialDocument $document = null;

    private function document(): RawMaterialDocument
    {
        return $this->document ??= RawMaterialDocument::findOrFail($this->documentId);
    }
}
