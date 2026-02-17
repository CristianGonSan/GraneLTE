<?php

namespace App\Livewire\Inventory\RawMaterialDocuments;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Models\Inventory\RawMaterialDocument;
use App\Traits\SweetAlert2\Livewire\WithSweetAlert;
use DomainException;
use Illuminate\View\View;
use Livewire\Component;
use Throwable;

class ChangeDocumentStatus extends Component
{
    use WithSweetAlert;

    public int $documentId;

    private ?RawMaterialDocument $document = null;

    public function mount(RawMaterialDocument $document): void
    {
        $this->documentId = $document->id;
        $this->document   = $document;
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

    private function document(): RawMaterialDocument
    {
        if ($this->document === null) {
            $this->document = RawMaterialDocument::findOrFail($this->documentId);
        }

        return $this->document;
    }
}
