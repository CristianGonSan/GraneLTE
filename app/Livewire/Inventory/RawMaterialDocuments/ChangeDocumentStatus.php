<?php

namespace App\Livewire\Inventory\RawMaterialDocuments;

use App\Enums\Inventory\RawMaterialDocument\RawMaterialDocumentStatus;
use App\Exports\Excel\Inventory\RawMaterialDocument\RawMaterialDocumentExport;
use App\Models\Inventory\RawMaterialDocument;
use App\Traits\SweetAlert2\FlashToast;
use App\Traits\SweetAlert2\Livewire\Toast;
use DomainException;
use Illuminate\View\View;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class ChangeDocumentStatus extends Component
{
    use Toast, FlashToast;

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

            if (!$document->canChangeTo($newStatus)) {
                $this->toastError("Acción invalida");
                return;
            }

            if ($newStatus !== RawMaterialDocumentStatus::PENDING) {
                $document->validated_by = auth()->id();
                $document->validated_at = now();
            }

            if ($newStatus === RawMaterialDocumentStatus::ACCEPTED) {
                $document->execute();
            }

            $document->status = $newStatus;
            $document->save();

            $this->toastSuccess("El documento cambió a {$newStatus->label()}");
        } catch (DomainException $e) {
            $this->toastError($e->getMessage());
        } catch (Throwable $e) {
            report($e); // log técnico
            $this->toastError('Ocurrió un error inesperado al procesar el documento.');
        }
    }

    public function delete(): void
    {
        $document = $this->document();

        if (!$document->canDelete()) {
            $this->toastError('No tienes permiso para realizar esta acción');
            return;
        }

        $document->hardDelete();
        $this->flashToastSuccess('Documento eliminado');
        redirect()->route('raw-material-documents.index');
    }

    public function export(): BinaryFileResponse
    {
        return Excel::download(
            new RawMaterialDocumentExport($this->documentId),
            "documento_#{$this->documentId}" . '_' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    private ?RawMaterialDocument $document = null;

    private function document(): RawMaterialDocument
    {
        return $this->document ??= RawMaterialDocument::findOrFail($this->documentId);
    }
}
